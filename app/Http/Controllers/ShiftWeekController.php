<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\shift;
use App\Models\section;
use App\Models\employees;
use App\Models\shift_week;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ShiftWeekController extends Controller
{

    public function index(Request $request)
    {
        if ($request) {
            $start = Carbon::parse($request->start);
            $end = Carbon::parse($request->end);

            $diff = $start->diffInDays($end);

            $days = [];

            for ($i = 0; $i <= $diff; $i++) {
                $days[] = $start->copy()->addDays($i);
            }
            // dd($days);
            // $secs = (employees::get());
            // $secs = (section::with("employees")->whereNotNull("ipz")->get());

            // $secs = section::with(['employees' => function ($q) {   /// بدها فلتر عشان يجيب الموظفين اللي عندهم اي بي زي
            //     $q->whereNotNull('ipz');
            // }])
            //     ->get();

            // $secs = section::with('employees') //
            //     ->whereHas('employees', function ($q) { /// بدها فلتر عشان يجيب الأقسام اللي عندها موظفين اي بي زي
            //         $q->whereNotNull('ipz');
            //     })
            //     ->get();

            $secs = section::with('employees')->get();


            // dd($secs);
            $shifts = shift::get();
            return view('shift_week.shift_week', compact('days', 'secs', 'shifts'));
        }

        return view('shift_week.shift_week');
    }


    public function create()
    {
        //
    }


    // public function store(Request $request)
    // {
    //     foreach ($request->days as $day) {

    //         $date = $day['date'];
    //         $shiftId = $day['shift_id'];
    //         $employees = $day['employee_ids'] ?? [];

    //         foreach ($employees as $empId) {

    //             shift_week::create([
    //                 'date' => $date,
    //                 'employees_id' => $empId,
    //                 'shift_id' => $shiftId,
    //             ]);
    //         }
    //     }
    //     return back();
    // }

    public function store(Request $request)
    {
        // 1. التحقق من وجود بيانات
        if (!$request->has('days')) {
            return back()->with('error', 'لا توجد بيانات لإرسالها');
        }

        try {
            DB::transaction(function () use ($request) {
                foreach ($request->days as $day) {
                    $date = $day['date'];
                    $shiftId = $day['shift_id'];

                    // جلب المصفوفة والتأكد من أنها تحتوي على أرقام (IDs) صحيحة فقط
                    // array_filter تمسح أي قيم null أو فارغة
                    $employees = isset($day['employee_ids']) ? array_filter($day['employee_ids']) : [];

                    // 2. نمسح القديم فقط لهذا التاريخ
                    // ملاحظة: إذا كنت تريد السماح بمسح اليوم بالكامل، اترك الحذف كما هو.
                    shift_week::where('date', $date)->delete();

                    // 3. إضافة الجديد (فقط إذا كان هناك موظفون مختارون)
                    foreach ($employees as $empId) {
                        if (!empty($empId)) { // تأكيد إضافي أن ID الموظف ليس فارغاً
                            shift_week::create([
                                'date'         => $date,
                                'employees_id' => $empId, // تأكد من الاسم في الداتابيز
                                'shift_id'     => $shiftId,
                            ]);
                        }
                    }
                }
            });

            return back()->with('success', 'تم حفظ التعديلات بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء الحفظ: ' . $e->getMessage());
        }
    }

    public function show(shift_week $shift_week)
    {
        //
    }


    public function edit(Request $request)
    {
        // الحصول على التواريخ من الطلب أو تحديد تواريخ افتراضية (مثلاً الأسبوع الحالي)
        $start_date = $request->start ? Carbon::parse($request->start) : Carbon::now()->startOfWeek();
        $end_date = $request->end ? Carbon::parse($request->end) : Carbon::now()->endOfWeek();

        $diff = $start_date->diffInDays($end_date);
        $days = [];
        for ($i = 0; $i <= $diff; $i++) {
            $days[] = $start_date->copy()->addDays($i);
        }

        // $secs = section::with("employees")->get();
        // $secs = section::with(['employees' => function ($q) {   /// بدها فلتر عشان يجيب الموظفين اللي عندهم اي بي زي
        //     $q->whereNotNull('ipz');
        // }])
        //     ->get();

        // $secs = section::with('employees') //
        //     ->whereHas('employees', function ($q) { /// بدها فلتر عشان يجيب الأقسام اللي عندها موظفين اي بي زي
        //         $q->whereNotNull('ipz');
        //     })
        //     ->get();

        $secs = section::with('employees')->get();


        // dd($secs);
        $shifts = shift::get();

        // جلب البيانات الموجودة مسبقاً مجمعة حسب التاريخ
        $existingShifts = shift_week::whereBetween('date', [$start_date->format('Y-m-d'), $end_date->format('Y-m-d')])
            ->with('employees')
            ->get()
            ->groupBy('date');

        return view('shift_week.shift_week_edit', compact('days', 'secs', 'existingShifts', 'shifts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, shift_week $shift_week)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(shift_week $shift_week)
    {
        //
    }
}
