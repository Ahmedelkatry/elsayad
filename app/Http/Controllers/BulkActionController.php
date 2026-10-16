<?php

namespace App\Http\Controllers;

use App\Models\employees;
use App\Models\salf;
use App\Models\disemp;
use App\Models\hafz;
use App\Models\vacation;
use App\Models\please;
use App\Models\User;
use App\Notifications\salf_notiy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class BulkActionController extends Controller
{
    public function index()
    {
        $employees = employees::orderBy('name', 'asc')->get();
        return view('employees.bulk_action', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employees_id' => 'required|array',
            'employees_id.*' => 'exists:employees,id',
        ]);

        $employeeIds = $request->input('employees_id');

        // Extract parallel array inputs
        $salfs = (array) $request->input('salf');
        $salfDates = (array) $request->input('salf_date');
        $salfDecs = (array) $request->input('salf_dec');

        $disValues = (array) $request->input('dis_value');
        $disDates = (array) $request->input('dis_date');
        $disDecs = (array) $request->input('dis_dec');

        $hafzs = (array) $request->input('hafz');
        $hafzDates = (array) $request->input('hafz_date');
        $hafzDecs = (array) $request->input('hafz_dec');

        $vacationDates = (array) $request->input('vacation_date');
        $disvacValues = (array) $request->input('disvac');
        $vacationDecs = (array) $request->input('vacation_dec');

        $pleaseDates = (array) $request->input('please_date');
        $inOuts = (array) $request->input('in_out');
        $pleaseMinutess = (array) $request->input('please_minutes');
        $pleaseDisTypes = (array) $request->input('please_dis_type');
        $pleaseDisVals = (array) $request->input('please_dis_val');
        $pleaseDecs = (array) $request->input('please_dec');

        foreach ($employeeIds as $key => $empId) {
            if (empty($empId)) {
                continue;
            }

            $emp = employees::findOrFail($empId);

            // 1. Save Loan/Salf if value is filled
            $salfVal = $salfs[$key] ?? null;
            if ($salfVal !== null && $salfVal !== '') {
                $salfRecord = salf::create([
                    'employees_id' => $empId,
                    'section_id' => $emp->section_id,
                    'salf' => $salfVal,
                    'date' => ($salfDates[$key] ?? null) ?: date('Y-m-d'),
                    'dec' => $salfDecs[$key] ?? null,
                ]);

                $user = User::where('num', $emp->num)->first();
                if ($user !== null) {
                    Notification::send($user, new salf_notiy([
                        'masg' => 'تم قبول سلفه بقيمه ' . $salfVal . ' جنيه',
                        'id' => $salfRecord->id,
                        'date' => $salfRecord->date,
                        'dec' => $salfRecord->dec,
                        'type' => 'salf',
                    ]));
                }
            }

            // 2. Save Deduction/Disemp if value is filled
            $disVal = $disValues[$key] ?? null;
            if ($disVal !== null && $disVal !== '') {
                $disRecord = disemp::create([
                    'employees_id' => $empId,
                    'section_id' => $emp->section_id,
                    'dis' => $disVal,
                    'date' => ($disDates[$key] ?? null) ?: date('Y-m-d'),
                    'dec' => $disDecs[$key] ?? null,
                ]);

                $user = User::where('num', $emp->num)->first();
                if ($user !== null) {
                    Notification::send($user, new salf_notiy([
                        'masg' => 'خصم ' . $disVal,
                        'id' => $disRecord->id,
                        'date' => $disRecord->date,
                        'dec' => $disRecord->dec,
                        'type' => 'dis',
                    ]));
                }
            }

            // 3. Save Incentive/Hafz if value is filled
            $hafzVal = $hafzs[$key] ?? null;
            if ($hafzVal !== null && $hafzVal !== '') {
                $hafzRecord = hafz::create([
                    'employees_id' => $empId,
                    'section_id' => $emp->section_id,
                    'hafz' => $hafzVal,
                    'date' => ($hafzDates[$key] ?? null) ?: date('Y-m-d'),
                    'dec' => $hafzDecs[$key] ?? null,
                ]);

                $user = User::where('num', $emp->num)->first();
                if ($user !== null) {
                    Notification::send($user, new salf_notiy([
                        'masg' => 'حافز ' . $hafzVal,
                        'id' => $hafzRecord->id,
                        'date' => $hafzRecord->date,
                        'dec' => $hafzRecord->dec,
                        'type' => 'hafz',
                    ]));
                }
            }

            // 4. Save Vacation if date is filled
            $vacDate = $vacationDates[$key] ?? null;
            if ($vacDate !== null && $vacDate !== '') {
                vacation::create([
                    'employees_id' => $empId,
                    'date' => $vacDate,
                    'disvac' => ($disvacValues[$key] ?? null) ?: 0,
                    'dec' => $vacationDecs[$key] ?? null,
                ]);
            }

            // 5. Save Permission/Please if date is filled
            $plzDate = $pleaseDates[$key] ?? null;
            if ($plzDate !== null && $plzDate !== '') {
                $disMinutes = 0;
                $disValAmount = 0;

                $plzDisType = $pleaseDisTypes[$key] ?? 'dis';
                $plzDisVal = $pleaseDisVals[$key] ?? 0;

                if ($plzDisType === 'dis') {
                    $disMinutes = $plzDisVal;
                } elseif ($plzDisType === 'dis_value') {
                    $disValAmount = $plzDisVal;
                }

                please::create([
                    'employees_id' => $empId,
                    'date' => $plzDate,
                    'in_out' => $inOuts[$key] ?? 0,
                    'minutes' => $pleaseMinutess[$key] ?? 0,
                    'dis' => $disMinutes,
                    'dis_value' => $disValAmount,
                    'dec' => $pleaseDecs[$key] ?? null,
                ]);
            }
        }

        return redirect()->route('employees.index')->with('Add', 'تم تسجيل الإجراءات الجماعية بنجاح');
    }
}
