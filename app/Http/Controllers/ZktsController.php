<?php

namespace App\Http\Controllers;

use App\Models\employees;
use App\Models\section;
use App\Models\zkt;
use Illuminate\Http\Request;

class ZktsController extends Controller
{
    /**
     * عرض قائمة سجلات الحضور والغياب (zkts)
     */
    public function index(Request $request)
    {
        $hasFilter = $request->filled('search') || 
                     $request->filled('date_from') || 
                     $request->filled('date_to') || 
                     $request->filled('absent') || 
                     $request->filled('employee_id');

        if (!$hasFilter) {
            $zkts = zkt::whereRaw('1 = 0')->paginate(50);
            $employees = employees::orderBy('name')->get();
            $presentCount = 0;
            $absentCount = 0;
            return view('zkts.index', compact('zkts', 'employees', 'presentCount', 'absentCount'));
        }

        $query = zkt::query()
            ->select('zkts.*')
            ->leftJoin('employees', 'zkts.numid', '=', 'employees.num');

        // فلترة بالاسم أو الرقم
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('zkts.name', 'like', "%{$search}%")
                  ->orWhere('zkts.numid', 'like', "%{$search}%");
            });
        }

        // فلترة بالتاريخ
        if ($request->filled('date_from')) {
            $query->where('zkts.date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('zkts.date', '<=', $request->date_to);
        }

        // فلترة بحالة الحضور (بناءً على وجود البصمة الفعلية لا على قيمة عمود absent)
        if ($request->filled('absent')) {
            if ($request->absent === 'حاضر') {
                // حاضر = عنده دخول أو خروج (أي منهما)
                $query->where(function ($q) {
                    $q->whereNotNull('zkts.in')->orWhereNotNull('zkts.out');
                });
            } elseif ($request->absent === 'غائب') {
                // غائب = مفيش لا دخول ولا خروج
                $query->whereNull('zkts.in')->whereNull('zkts.out');
            } else {
                // باقي الحالات (نهاية وردية وغيرها) تُفلتر بالعمود مباشرة
                $query->where('zkts.absent', $request->absent)
                      ->whereNull('zkts.in')
                      ->whereNull('zkts.out');
            }
        }

        // فلترة بالموظف
        if ($request->filled('employee_id')) {
            $emp = employees::find($request->employee_id);
            if ($emp) {
                $query->where('zkts.numid', $emp->num);
            }
        }

        // ===== إخفاء أيام الإجازة الأسبوعية للموظف إلا لو حضر =====
        $query->where(function ($q) {
            $q->orWhereNull('employees.free_d')
              ->orWhereNotNull('zkts.in')
              ->orWhereNotNull('zkts.out')
              ->orWhereRaw("JSON_SEARCH(employees.free_d, 'one', 
                  CASE DAYOFWEEK(zkts.date)
                      WHEN 1 THEN 'الأحد'
                      WHEN 2 THEN 'الاثنين'
                      WHEN 3 THEN 'الثلاثاء'
                      WHEN 4 THEN 'الأربعاء'
                      WHEN 5 THEN 'الخميس'
                      WHEN 6 THEN 'الجمعة'
                      WHEN 7 THEN 'السبت'
                  END
              ) IS NULL");
        });

        // حساب الإحصائيات بناءً على الاستعلام المفلتر
        $presentCount = (clone $query)->where(function($q) {
            $q->whereNotNull('zkts.in')->orWhereNotNull('zkts.out');
        })->count();

        $absentCount = (clone $query)->whereNull('zkts.in')->whereNull('zkts.out')->count();

        $zkts = $query->orderBy('zkts.date', 'desc')->orderBy('zkts.numid')->paginate(50)->withQueryString();
        $employees = employees::orderBy('name')->get();

        return view('zkts.index', compact('zkts', 'employees', 'presentCount', 'absentCount'));
    }

    /**
     * نموذج إضافة سجل حضور جديد
     */
    public function create()
    {
        $employees = employees::orderBy('name')->get();
        return view('zkts.create', compact('employees'));
    }

    /**
     * حفظ سجل حضور جديد
     */
    public function store(Request $request)
    {
        $request->validate([
            'numid'  => 'required',
            'date'   => 'required|date',
            'in'     => 'nullable|date_format:H:i',
            'out'    => 'nullable|date_format:H:i',
        ], [
            'numid.required' => 'يجب اختيار الموظف',
            'date.required'  => 'يجب إدخال التاريخ',
        ]);

        $emp = employees::where('num', $request->numid)->first();

        $exists = zkt::where('numid', $request->numid)->where('date', $request->date)->exists();
        if ($exists) {
            return back()->withErrors(['numid' => 'يوجد سجل لهذا الموظف في هذا التاريخ مسبقاً'])->withInput();
        }

        zkt::create([
            'numid'   => $request->numid,
            'name'    => $emp ? $emp->name : $request->name,
            'date'    => $request->date,
            'in'      => $request->in ? $request->in . ':00' : null,
            'out'     => $request->out ? $request->out . ':00' : null,
            'break_out' => $request->break_out ? $request->break_out . ':00' : null,
            'break_in'  => $request->break_in ? $request->break_in . ':00' : null,
            'absent'  => ($request->in || $request->out) ? 'حاضر' : 'غائب',
        ]);

        return redirect()->route('zkts.index')->with('success', 'تم إضافة سجل الحضور بنجاح');
    }

    /**
     * عرض سجل حضور محدد
     */
    public function show($id)
    {
        $zkt = zkt::findOrFail($id);
        return view('zkts.show', compact('zkt'));
    }

    /**
     * نموذج تعديل سجل حضور
     */
    public function edit($id)
    {
        $zkt = zkt::findOrFail($id);
        $employees = employees::orderBy('name')->get();
        return view('zkts.edit', compact('zkt', 'employees'));
    }

    /**
     * حفظ التعديلات على سجل حضور
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'in'   => 'nullable|date_format:H:i',
            'out'  => 'nullable|date_format:H:i',
        ], [
            'date.required' => 'يجب إدخال التاريخ',
        ]);

        $zkt = zkt::findOrFail($id);

        $zkt->update([
            'date'      => $request->date,
            'in'        => $request->in ? $request->in . ':00' : null,
            'out'       => $request->out ? $request->out . ':00' : null,
            'break_out' => $request->break_out ? $request->break_out . ':00' : null,
            'break_in'  => $request->break_in ? $request->break_in . ':00' : null,
            'absent'    => ($request->in || $request->out) ? 'حاضر' : 'غائب',
        ]);

        return redirect()->route('zkts.index')->with('success', 'تم تحديث سجل الحضور بنجاح');
    }

    /**
     * حذف سجل حضور
     */
    public function destroy($id)
    {
        $zkt = zkt::findOrFail($id);
        $zkt->delete();

        return redirect()->route('zkts.index')->with('success', 'تم حذف سجل الحضور بنجاح');
    }

    /**
     * حذف متعدد
     */
    public function bulkDelete(Request $request)
    {
        if ($request->filled('ids')) {
            zkt::whereIn('id', $request->ids)->delete();
        }
        return redirect()->route('zkts.index')->with('success', 'تم حذف السجلات المحددة بنجاح');
    }
}
