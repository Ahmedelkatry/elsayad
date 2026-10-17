<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ahmed;
use App\Imports\employeesImport;
use App\Models\badel_transport;
use App\Models\count;
use App\Models\counts_emps;
use App\Models\dis_transport;
use App\Models\disemp;
use App\Models\employees;
use App\Models\hafz;
use App\Models\hafzeid;
use App\Models\ipzkt;
use App\Models\loan;
use App\Models\nozkt;
use App\Models\please;
use App\Models\purchases;
use App\Models\salf;
use App\Models\section;
use App\Models\shift;
use App\Models\taminat;
use App\Models\User;
use App\Models\vacation;
use App\Models\zkt;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Rats\Zkteco\Lib\ZKTeco;

class EmployeesController extends Controller implements HasMiddleware
{
    public function index()
    {
        $sections = section::get();
        $shifts = shift::get();
        $employees = employees::with('companyLocations')->get();
        $companyLocations = \App\Models\CompanyLocation::orderBy('name')->get();

        return view('employees.employees', compact('employees', 'sections', 'shifts', 'companyLocations'));
    }

    public function create()
    {
        $sections = section::get();
        $shifts = shift::get();
        $zkts = ipzkt::get();
        $next_num = (employees::max('num') ?? 0) + 1;

        return view('employees.addemp', compact('sections', 'shifts', 'zkts', 'next_num'));
    }

    public function store(Request $request)
    {
        if ($this->hasReachedEmployeeLimit()) {
            return back()->withErrors(['error' => 'لقد وصلت للحد الأقصى لعدد الموظفين المسموح به في خطتك الحالية.'])->withInput();
        }

        $request->validate([
            'num' => 'required|integer|unique:employees,num',
            'name' => 'required|unique:employees,name',
            'age' => 'required',
            'mobile' => 'required',
            'title' => 'required',
            'section_id' => 'required',
            'shift_id' => 'required',
            'kabd' => 'required',
        ], [
            'num.unique' => 'رقم البصمه موجود بالفعل',
            'name.unique' => 'اسم الموظف موجود بالفعل',
        ]);

        $data = $request->all();
        if (isset($data['ipz']) && is_array($data['ipz'])) {
            $data['ipz'] = implode(',', $data['ipz']);
        }
        if ($request->filled('phone_password')) {
            $data['phone_password'] = bcrypt($request->phone_password);
        }
        $employee = employees::create($data);

        // Enqueue command for ADMS devices
        $admsDevices = \App\Models\ipzkt::whereNotNull('sn')->where('sn', '!=', '')->get();
        foreach ($admsDevices as $device) {
            $pin = $employee->num;
            $name = $employee->name;
            $cmdString = "DATA USER PIN={$pin}\tName={$name}\tPri=0";

            \App\Models\ZktCommand::create([
                'sn' => $device->sn,
                'command' => $cmdString,
            ]);
        }

        return back();
    }

    // public function employees_deleteAll(Request $request)
    // {
    //     $emps = employees::find($request->ids)->toArray();

    // }

    public function show(Request $request, $id)
    {

        $salfs = salf::where('employees_id', $id)->whereBetween('date', [$request->dates, $request->datee])
            ->get();
        $diss = disemp::where('employees_id', $id)->whereBetween('date', [$request->dates, $request->datee])->get();
        $vacs = vacation::where('employees_id', $id)->whereBetween('date', [$request->dates, $request->datee])->get();
        $hafzs = hafz::where('employees_id', $id)->whereBetween('date', [$request->dates, $request->datee])->get();
        $plzs = please::where('employees_id', $id)->whereBetween('date', [$request->dates, $request->datee])->get();
        $purchases = purchases::where('employees_id', $id)->whereBetween('date', [$request->dates, $request->datee])->get();

        $loanss = loan::where('employees_id', $id)->exists();
        $startDate = Carbon::parse($request->dates)->format('Y-m-d');
        $endDate = Carbon::parse($request->datee)->format('Y-m-d');
        $loans = loan::where('employees_id', $id)->where('date_start', '<=', $startDate)
            ->where('date_end', '>=', $endDate)
            ->get();
        $loans = loan::where('employees_id', $id)->whereBetween('date_start', [$startDate, $endDate])->get();
        $loans = loan::where('employees_id', $id)->whereBetween('date_end', [$startDate, $endDate])->get();

        return view('employees.show', compact('salfs', 'diss', 'vacs', 'hafzs', 'plzs', 'id', 'loans', 'purchases'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $emp = employees::find($id);
        $sections = section::get();
        $shifts = shift::get();
        $zkts = ipzkt::get();

        return view('employees.editemp', compact('sections', 'shifts', 'emp', 'zkts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            // "num" => 'required|integer|unique:employees,num',
            'name' => 'required',
            'section_id' => 'required',
            'shift_id' => 'required',
            'kabd' => 'required',
        ], [

            'mobile.unique' => 'رقم الهاتف موجود بالفعل',

        ]);

        // dd( iconv("UTF-8","Windows-1256",$request->name));
        //  dd( iconv("Windows-1256","UTF-8",$request->name));
        $ipsList = is_array($request->ipz) ? $request->ipz : [];
        $connectedDevices = [];

        foreach ($ipsList as $ipOrSn) {
            if (empty($ipOrSn)) {
                continue;
            }

            $device = \App\Models\ipzkt::where('ip', $ipOrSn)->orWhere('sn', $ipOrSn)->first();
            $deviceName = $device ? $device->name : 'جهاز غير معروف';

            // جهاز ADMS (SN فقط بدون IP) → أضف أمر التحديث للـ Queue
            if ($device && $device->sn && ! $device->ip) {
                $pin = $request->num;
                $name = $request->name;
                $pass = $request->password === '' ? null : $request->password;
                $cmdString = "DATA USER PIN={$pin}\tName={$name}\tPri=".($request->role_zkt ?? 0)
                    .($pass ? "\tPasswd={$pass}" : '');

                \App\Models\ZktCommand::create([
                    'sn' => $device->sn,
                    'command' => $cmdString,
                ]);

                continue;
            }

            // جهاز IP عادي
            $targetIp = ($device && $device->ip) ? $device->ip : $ipOrSn;
            $isOnline = false;
            if ($targetIp && filter_var($targetIp, FILTER_VALIDATE_IP)) {
                $errno = 0;
                $errstr = '';
                $sock = @fsockopen($targetIp, 4370, $errno, $errstr, 1.2);
                if ($sock) {
                    fclose($sock);
                    $zk = new ZKTeco($targetIp, 4370);
                    if (@$zk->connect()) {
                        $isOnline = true;
                        $connectedDevices[] = [
                            'zk'     => $zk,
                            'device' => $device,
                            'ip'     => $targetIp,
                        ];
                    }
                }
            }

            if (! $isOnline) {
                // حذف سجل المزامنة للجهاز لإعادة إرسال البيانات المحدثة تلقائياً عند تشغيل الجهاز
                $keysToDelete = array_unique(array_filter([
                    $deviceName,
                    $device ? $device->name : null,
                    $device ? $device->ip : null,
                    $targetIp,
                ]));
                \Illuminate\Support\Facades\DB::table('zkt_device_sync')
                    ->whereIn('device_key', $keysToDelete)
                    ->where('emp_num', (string) $request->num)
                    ->delete();
            }
        }

        $pass = ($request->password === '' || $request->password === null) ? null : $request->password;
        foreach ($connectedDevices as $item) {
            $zk  = $item['zk'];
            $dev = $item['device'];
            try {
                @$zk->testVoice();
                @$zk->setUser(
                    (int) $request->num,
                    (string) $request->num,
                    $request->name,
                    $pass,
                    (int) ($request->role_zkt ?? 0)
                );
                @$zk->disconnect();

                $keysToSave = array_unique(array_filter([
                    $dev ? $dev->name : null,
                    $dev ? $dev->ip : null,
                    $item['ip'],
                ]));
                foreach ($keysToSave as $k) {
                    \Illuminate\Support\Facades\DB::table('zkt_device_sync')->updateOrInsert(
                        ['device_key' => $k, 'emp_num' => (string) $request->num],
                        ['synced_at' => now(), 'updated_at' => now()]
                    );
                }
            } catch (\Throwable $e) {
                \Log::warning("Direct ZKTeco update failed for {$item['ip']}: " . $e->getMessage());
            }
        }

        if ($request->filled('phone_password')) {
            $request->validate([
                'phone_password' => 'same:phone_password_confirmation',
            ]);
        }

        $empD = employees::find($id);
        $empD->num = $request->num;
        $empD->name = $request->name;
        $empD->password = $request->password;
        if ($request->filled('phone_password')) {
            $empD->phone_password = bcrypt($request->phone_password);
        }
        $empD->age = $request->age;
        $empD->mobile = $request->mobile;
        $empD->role_zkt = $request->role_zkt;
        $empD->title = $request->title;
        $empD->friday = $request->friday;
        $empD->free_d = $request->free_d;
        $empD->choosesys = $request->choosesys;
        $empD->check = $request->check;
        $empD->ipz = is_array($request->ipz) ? implode(',', $request->ipz) : $request->ipz;
        $empD->section_id = $request->section_id;
        $empD->shift_id = $request->shift_id;
        $empD->kabd = $request->kabd;

        if ($request->sysemp == 1) { //شهري ثابت
            $value = 30;

            if ($request->check == 1) {
                $dayy = $request->free_d;
                // الحصول على أول وآخر يوم في الشهر الحالي
                $startOfMonth = Carbon::now()->startOfMonth();
                $endOfMonth = Carbon::now()->endOfMonth();

                // حساب عدد أيام الإجازات في الشهر الحالي
                $holidayCount = 0;
                if ($dayy == null) {
                    ($empD->sysemp = $value);
                }

                // التكرار عبر الأيام في الشهر الحالي
                for ($day = $startOfMonth; $day <= $endOfMonth; $day->modify('+1 day')) {
                    // الحصول على اسم اليوم باللغة العربية
                    $dayName = Carbon::parse($day)->locale('ar')->dayName;

                    if (in_array($dayName, $dayy)) {
                        $holidayCount++;
                    }
                }
                $holday = $holidayCount++;
                ($empD->sysemp = $value - $holday);
            } else {
                ($empD->sysemp = $value);
            }
            // dd($empD->sysemp = $value);
        } elseif ($request->sysemp == 2) { // شهري متغير
            $value = 30;
            if ($request->check == 1) {
                $dayy = $request->free_d;

                // الحصول على أول وآخر يوم في الشهر الحالي
                $startOfMonth = Carbon::now()->startOfMonth();
                $endOfMonth = Carbon::now()->endOfMonth();

                // حساب عدد أيام الإجازات في الشهر الحالي
                $holidayCount = 0;
                if ($value == null || $dayy == null) {
                    ($empD->sysemp = $value);
                }

                // التكرار عبر الأيام في الشهر الحالي
                for ($day = $startOfMonth; $day <= $endOfMonth; $day->modify('+1 day')) {
                    // الحصول على اسم اليوم باللغة العربية
                    $dayName = Carbon::parse($day)->locale('ar')->dayName;

                    if (in_array($dayName, $dayy)) {
                        $holidayCount++;
                    }
                }
                $holday = $holidayCount++;

                if ($value == 30) {

                    $day = Carbon::now()->daysInMonth;
                    ($empD->sysemp = $day - $holday);
                }
                // else {
                //     return $value;
                // }

                elseif ($value == 28) {

                    $day = 28;
                    $empD->sysemp = $day - $holday;
                }
                // else {
                //     return $value;
                // }

                elseif ($value == 29) {
                    $day = 29;
                    $empD->sysemp = $day - $holday;
                } elseif ($value == 31) {
                    $day = 31;
                    $empD->sysemp = $day - $holday;
                } else {
                    $empD->sysemp = $value - $holday;
                }
            } else {
                $empD->sysemp = $day = Carbon::now()->daysInMonth;
            }
        } else { //غير شهري
            // dd($request->sysemp);
            $value = $request->sysemp;
            if ($request->check == 1) {
                $dayy = $request->free_d;

                // الحصول على أول وآخر يوم في الشهر الحالي
                $startOfMonth = Carbon::now()->startOfMonth();
                $endOfMonth = Carbon::now()->startOfMonth()->modify('+'.$request->sysemp.' days');

                // حساب عدد أيام الإجازات في الشهر الحالي
                $holidayCount = 0;
                if ($dayy == null) {
                    ($empD->sysemp = $value);
                }

                // التكرار عبر الأيام في الشهر الحالي
                for ($day = $startOfMonth; $day <= $endOfMonth; $day->modify('+1 day')) {
                    // الحصول على اسم اليوم باللغة العربية
                    $dayName = Carbon::parse($day)->locale('ar')->dayName;

                    if (in_array($dayName, $dayy)) {
                        $holidayCount++;
                    }
                }
                $holday = $holidayCount++;

                ($empD->sysemp = $request->sysemp - $holday);

                // if ($value == 30) {

                //     $day = Carbon::now()->daysInMonth;
                //     $empD->sysemp = $day - $holday;
                // }
                // // else {
                // //     return $value;
                // // }

                // elseif ($value == 28) {

                //     $day = 28;
                //     $empD->sysemp = $day - $holday;
                // }
                // // else {
                // //     return $value;
                // // }

                // elseif ($value == 29) {
                //     $day = 29;
                //     $empD->sysemp = $day - $holday;
                // } elseif ($value == 31) {
                //     $day = 31;
                //     $empD->sysemp = $day - $holday;
                // } else {
                //     $empD->sysemp = $value - $holday;
                // }

            } else {

                ($empD->sysemp = $request->sysemp);
            }
        }

        $empD->save();

        // إعادة تعيين مزامنة الأجهزة حتى تسحب جميع ماكينات الفروع التحديثات تلقائياً
        \Illuminate\Support\Facades\DB::table('zkt_device_sync')->where('emp_num', $empD->num)->delete();

        // (ADMS devices were already handled in the loop above via ZktCommand queue)

        session()->flash('Add');

        return redirect('/employees');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->idd ?? $request->route('employee') ?? $request->id;
        $empToDelete = employees::find($id);

        if (!$empToDelete) {
            return back()->with('error', 'الموظف غير موجود!');
        }

        $pin = $empToDelete->num ?? $request->idzkt;
        $name = $empToDelete->name;
        $ipsList = $empToDelete->ipz ? array_filter(explode(',', $empToDelete->ipz)) : [];

        // 1. تسجيل أمر الحذف في جدول zkt_deleted_users لمزامنة الوسيط (IP Mediator)
        if (!empty($ipsList)) {
            foreach ($ipsList as $ipOrSn) {
                \App\Models\ZktDeletedUser::create([
                    'num'        => $pin,
                    'name'       => $name,
                    'device_key' => $ipOrSn,
                    'executed'   => 0,
                ]);
            }
        } else {
            \App\Models\ZktDeletedUser::create([
                'num'        => $pin,
                'name'       => $name,
                'device_key' => null, // لكل الأجهزة
                'executed'   => 0,
            ]);
        }

        // 2. إرسال أمر الحذف لجميع أجهزة ADMS (Cloud / SN)
        $admsDevices = \App\Models\ipzkt::whereNotNull('sn')->where('sn', '!=', '')->get();
        foreach ($admsDevices as $device) {
            if (empty($ipsList) || in_array($device->sn, $ipsList) || in_array($device->ip, $ipsList)) {
                \App\Models\ZktCommand::create([
                    'sn'      => $device->sn,
                    'command' => "DATA DELETE userinfo PIN={$pin}",
                ]);
            }
        }

        // 3. محاولة الحذف المباشر (Direct Socket IP) إن كان الجهاز متصلاً بالشبكة المحلية للسيرفر
        foreach ($ipsList as $ipOrSn) {
            if (empty($ipOrSn)) {
                continue;
            }

            $device = \App\Models\ipzkt::where('ip', $ipOrSn)->orWhere('sn', $ipOrSn)->first();
            // محاولة الاتصال المباشر فقط إن كان عنوان IP وليس مجرد SN
            if ($ipOrSn && (!$device || $device->ip)) {
                try {
                    $zk = new ZKTeco($ipOrSn);
                    if (@$zk->connect()) {
                        @$zk->removeUser((int)$pin);
                        @$zk->disconnect();

                        // تحديث حالة الحذف كمنفذة لهذا الجهاز
                        \App\Models\ZktDeletedUser::where('num', $pin)
                            ->where(function($q) use ($ipOrSn) {
                                $q->where('device_key', $ipOrSn)->orWhereNull('device_key');
                            })->update(['executed' => 1]);
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning("Direct ZKTeco removal failed for $ipOrSn, user $pin: " . $e->getMessage());
                }
            }
        }

        // 4. حذف سجلات البصمة والمزامنة للموظف
        zkt::where('numid', $pin)->delete();
        nozkt::where('numid', $pin)->delete();
        \Illuminate\Support\Facades\DB::table('zkt_device_sync')->where('emp_num', $pin)->delete();

        // 5. حذف حساب المستخدم المرتبط إن وجد
        if ($empToDelete->email) {
            $user = user::where('email', $empToDelete->email)->first();
            if ($user) {
                $user->delete();
            }
        }

        // 6. حذف الموظف من قاعدة البيانات
        $empToDelete->delete();

        return back()->with('delete', 'تم حذف الموظف بنجاح وجاري إزالته من ماكينة البصمة.');
    }

    public function delete_account($id)
    {
        $employee = employees::where('num', $id)->first();
        if ($employee) {
            $employee->update([
                'phone_password' => null,
            ]);
        }

        return back();
    }

    public static function middleware()
    {
        return [
            ahmed::class,
        ];
    }

    public function import(Request $request)
    {

        //   dd($request->all());
        //  try{
        //
        Excel::import(new employeesImport, $request->file('file', \Maatwebsite\Excel\Excel::XLS));
        //      return response()->json(['data'=>'Users imported successfully.',201]);
        //  }catch(\Exception $ex){
        //      Log::info($ex);
        //      return response()->json(['data'=>'Some error has occur.',400]);
        //
        //  }
        //
    }

    public function ec(Request $request)
    {
        $f = $request->file;
        // $ff = $f->store("public_uplouds");
        $n = $f->getClientOriginalName();
        $fc = file_get_contents($request->file);
        // return $fc;
        $enc = Crypt::encryptString($fc);

        Storage::put($n, $enc);
        //  Storage::put("local/".$f);
    }

    public function store_addzkt(Request $request)
    {
        if ($this->hasReachedEmployeeLimit()) {
            return back()->withErrors(['num' => 'لقد وصلت للحد الأقصى لعدد الموظفين المسموح به في خطتك الحالية.'])->withInput();
        }

        // dd($request->num);
        $request->validate([
            'num' => 'required|integer|unique:employees,num',
            'name' => 'required|unique:employees,name',
            'section_id' => 'required',
            'shift_id' => 'required',
            'kabd' => 'required',
        ], [
            'num.unique' => 'رقم البصمه موجود بالفعل',
            // 'email.unique' => 'الايميل موجود بالفعل',
            'name.unique' => 'اسم الموظف موجود بالفعل',
        ]);

        // dd( iconv("UTF-8","Windows-1256",$request->name));
        //  dd( iconv("Windows-1256","UTF-8",$request->name));
        $ipsList = is_array($request->ipz) ? $request->ipz : [];
        $connectedDevices = [];
        $offlineDevices = [];

        foreach ($ipsList as $ipOrSn) {
            if (empty($ipOrSn)) {
                continue;
            }

            // البحث عن الجهاز بالـ IP أو الـ SN
            $device = \App\Models\ipzkt::where('ip', $ipOrSn)
                ->orWhere('sn', $ipOrSn)
                ->first();

            // لو الجهاز ADMS (SN فقط بدون IP) → أضف للـ Queue وتخطَّ socket
            if ($device && $device->sn && ! $device->ip) {
                $pin = $request->num;
                $name = $request->name;
                $pass = $request->password === '' ? null : $request->password;
                $cmdString = "DATA USER PIN={$pin}\tName={$name}\tPri=".($request->role_zkt ?? 0)
                    .($pass ? "\tPasswd={$pass}" : '');

                \App\Models\ZktCommand::create([
                    'sn' => $device->sn,
                    'command' => $cmdString,
                ]);

                continue; // لا نحاول socket لجهاز ADMS
            }

            // جهاز IP عادي
            $deviceName = $device ? $device->name : ($ipOrSn ?: 'جهاز غير معروف');
            $targetIp = ($device && $device->ip) ? $device->ip : $ipOrSn;

            $isOnline = false;
            if ($targetIp && filter_var($targetIp, FILTER_VALIDATE_IP)) {
                $errno = 0;
                $errstr = '';
                $sock = @fsockopen($targetIp, 4370, $errno, $errstr, 1.2);
                if ($sock) {
                    fclose($sock);
                    $zk = new ZKTeco($targetIp, 4370);
                    if (@$zk->connect()) {
                        $isOnline = true;
                        $connectedDevices[] = [
                            'zk'     => $zk,
                            'device' => $device,
                            'ip'     => $targetIp,
                            'name'   => $deviceName,
                        ];
                    }
                }
            }

            if (! $isOnline) {
                $offlineDevices[] = $deviceName;
                \Log::info("Employee Create: Device [$deviceName] ($targetIp) is offline. Employee {$request->num} queued for auto-sync.");
            }
        }

        $pass = ($request->password === '' || $request->password === null) ? null : $request->password;
        foreach ($connectedDevices as $item) {
            $zk     = $item['zk'];
            $dev    = $item['device'];
            $devKey = $dev ? ($dev->name ?: $dev->ip) : $item['ip'];

            try {
                @$zk->testVoice();
                @$zk->setUser(
                    (int) $request->num,
                    (string) $request->num,
                    $request->name,
                    $pass,
                    (int) ($request->role_zkt ?? 0),
                );
                @$zk->disconnect();

                // تسجيل المزامنة الفورية لهذا الجهاز
                $keysToSave = array_unique(array_filter([
                    $devKey,
                    $dev ? $dev->name : null,
                    $dev ? $dev->ip : null,
                    $item['ip'],
                ]));
                foreach ($keysToSave as $k) {
                    \Illuminate\Support\Facades\DB::table('zkt_device_sync')->updateOrInsert(
                        ['device_key' => $k, 'emp_num' => (string) $request->num],
                        ['synced_at' => now(), 'updated_at' => now()]
                    );
                }
            } catch (\Throwable $e) {
                \Log::warning("Immediate ZKTeco setUser failed for {$item['ip']}: " . $e->getMessage());
            }
        }
        if ($request->mobile) {
            $request->validate([
                'mobile' => 'required|unique:users,phone',
                'password_email' => 'required|same:confirm-password',
            ]);
        }
        // dd($request->password_email);

        if ($request->mobile && $request->password_email) {
            $request->validate([
                'mobile' => 'required|unique:users,phone',
                'password_email' => 'required|same:confirm-password',
            ]);

            User::create([
                'name' => $request->name,
                'num' => $request->num,

                'phone' => $request->mobile ?: null,
                'password' => bcrypt($request->password_email),
                'roles_name' => ['emp'],
                'Status' => 'مفعل',
            ]);
        }

        // $data = $request->except("password_email", "confirm-password");
        // employees::create($data);

        $empD = new employees;
        $empD->num = $request->num;
        $empD->name = $request->name;
        $empD->email = $request->email;
        $empD->password = $request->password;
        $empD->password_email = $request->password_email;
        $empD->age = $request->age;
        $empD->mobile = $request->mobile;
        $empD->role_zkt = $request->role_zkt;
        $empD->title = $request->title;
        $empD->friday = $request->friday;
        $empD->free_d = $request->free_d;
        $empD->choosesys = $request->choosesys;
        $empD->check = $request->check;
        $empD->ipz = is_array($request->ipz) ? implode(',', $request->ipz) : $request->ipz;
        $empD->section_id = $request->section_id;
        $empD->shift_id = $request->shift_id;
        $empD->kabd = $request->kabd;

        if ($request->sysemp == 1) { //شهري ثابت
            $value = 30;

            if ($request->check == 1) {
                $dayy = $request->free_d;
                // الحصول على أول وآخر يوم في الشهر الحالي
                $startOfMonth = Carbon::now()->startOfMonth();
                $endOfMonth = Carbon::now()->endOfMonth();

                // حساب عدد أيام الإجازات في الشهر الحالي
                $holidayCount = 0;
                if ($dayy == null) {
                    ($empD->sysemp = $value);
                }

                // التكرار عبر الأيام في الشهر الحالي
                for ($day = $startOfMonth; $day <= $endOfMonth; $day->modify('+1 day')) {
                    // الحصول على اسم اليوم باللغة العربية
                    $dayName = Carbon::parse($day)->locale('ar')->dayName;

                    if (in_array($dayName, $dayy)) {
                        $holidayCount++;
                    }
                }
                $holday = $holidayCount++;
                ($empD->sysemp = $value - $holday);
            } else {
                ($empD->sysemp = $value);
            }
            // dd($empD->sysemp = $value);
        } elseif ($request->sysemp == 2) { // شهري متغير
            $value = 30;
            if ($request->check == 1) {
                $dayy = $request->free_d;

                // الحصول على أول وآخر يوم في الشهر الحالي
                $startOfMonth = Carbon::now()->startOfMonth();
                $endOfMonth = Carbon::now()->endOfMonth();

                // حساب عدد أيام الإجازات في الشهر الحالي
                $holidayCount = 0;
                if ($value == null || $dayy == null) {
                    ($empD->sysemp = $value);
                }

                // التكرار عبر الأيام في الشهر الحالي
                for ($day = $startOfMonth; $day <= $endOfMonth; $day->modify('+1 day')) {
                    // الحصول على اسم اليوم باللغة العربية
                    $dayName = Carbon::parse($day)->locale('ar')->dayName;

                    if (in_array($dayName, $dayy)) {
                        $holidayCount++;
                    }
                }
                $holday = $holidayCount++;

                if ($value == 30) {

                    $day = Carbon::now()->daysInMonth;
                    ($empD->sysemp = $day - $holday);
                }
                // else {
                //     return $value;
                // }

                elseif ($value == 28) {

                    $day = 28;
                    $empD->sysemp = $day - $holday;
                }
                // else {
                //     return $value;
                // }

                elseif ($value == 29) {
                    $day = 29;
                    $empD->sysemp = $day - $holday;
                } elseif ($value == 31) {
                    $day = 31;
                    $empD->sysemp = $day - $holday;
                } else {
                    $empD->sysemp = $value - $holday;
                }
            } else {
                $empD->sysemp = $day = Carbon::now()->daysInMonth;
            }
        } else { //غير شهري
            // dd($request->sysemp);
            $value = $request->sysemp;
            if ($request->check == 1) {
                $dayy = $request->free_d;

                // الحصول على أول وآخر يوم في الشهر الحالي
                $startOfMonth = Carbon::now()->startOfMonth();
                $endOfMonth = Carbon::now()->startOfMonth()->modify('+'.$request->sysemp.' days');

                // حساب عدد أيام الإجازات في الشهر الحالي
                $holidayCount = 0;
                if ($dayy == null) {
                    ($empD->sysemp = $value);
                }

                // التكرار عبر الأيام في الشهر الحالي
                for ($day = $startOfMonth; $day <= $endOfMonth; $day->modify('+1 day')) {
                    // الحصول على اسم اليوم باللغة العربية
                    $dayName = Carbon::parse($day)->locale('ar')->dayName;

                    if (in_array($dayName, $dayy)) {
                        $holidayCount++;
                    }
                }
                $holday = $holidayCount++;

                ($empD->sysemp = $request->sysemp - $holday);

                // if ($value == 30) {

                //     $day = Carbon::now()->daysInMonth;
                //     $empD->sysemp = $day - $holday;
                // }
                // // else {
                // //     return $value;
                // // }

                // elseif ($value == 28) {

                //     $day = 28;
                //     $empD->sysemp = $day - $holday;
                // }
                // // else {
                // //     return $value;
                // // }

                // elseif ($value == 29) {
                //     $day = 29;
                //     $empD->sysemp = $day - $holday;
                // } elseif ($value == 31) {
                //     $day = 31;
                //     $empD->sysemp = $day - $holday;
                // } else {
                //     $empD->sysemp = $value - $holday;
                // }

            } else {

                ($empD->sysemp = $request->sysemp);
            }
        }

        // dd("done");
        $empD->save();

        if (!empty($offlineDevices)) {
            session()->flash('Add', 'تمت إضافة الموظف بنجاح في النظام! جهاز البصمة [' . implode(', ', $offlineDevices) . '] مغلق أو غير متصل حالياً، وسيتم رفعه للبصمة تلقائياً فور تشغيلها.');
        } else {
            session()->flash('Add', 'تمت إضافة الموظف ومزامنته مع أجهزة البصمة بنجاح.');
        }

        return redirect('/employees');
    }

    public function store_nozkt(Request $request)
    {
        if ($this->hasReachedEmployeeLimit()) {
            return back()->withErrors(['error' => 'لقد وصلت للحد الأقصى لعدد الموظفين المسموح به في خطتك الحالية.'])->withInput();
        }

        $request->validate([
            'num' => 'required|integer|unique:employees,num',
            'name' => 'required|unique:employees,name',
            'section_id' => 'required',
            'shift_id' => 'required',
            'kabd' => 'required',
        ], [
            'num.unique' => 'رقم البصمه موجود بالفعل',
            'name.unique' => 'اسم الموظف موجود بالفعل',
        ]);

        if ($request->mobile) {
            $request->validate([
                'mobile' => 'required|unique:users,phone',
                'password' => 'required|min:6',
            ], [
                'mobile.unique' => 'رقم الهاتف مستخدم بالفعل',
                'password.min' => 'يجب ألا تقل كلمة المرور عن 6 أحرف',
            ]);
        }

        if ($request->mobile && $request->password) {
            User::create([
                'name' => $request->name,
                'num' => $request->num,
                'phone' => $request->mobile ?: null,
                'password' => bcrypt($request->password),
                'roles_name' => ['emp'],
                'Status' => 'مفعل',
            ]);
        }

        $empD = new employees;
        $empD->num = $request->num;
        $empD->name = $request->name;
        $empD->email = $request->email;
        $empD->password = null;
        $empD->password_email = $request->password;
        $empD->age = $request->age;
        $empD->mobile = $request->mobile;
        $empD->role_zkt = 0;
        $empD->title = $request->title;
        $empD->friday = $request->friday;
        $empD->free_d = $request->free_d;
        $empD->choosesys = $request->choosesys;
        $empD->check = $request->check;
        $empD->ipz = null;
        $empD->section_id = $request->section_id;
        $empD->shift_id = $request->shift_id;
        $empD->kabd = $request->kabd;

        if ($request->sysemp == 1) { //شهري ثابت
            $value = 30;

            if ($request->check == 1) {
                $dayy = $request->free_d;
                $startOfMonth = Carbon::now()->startOfMonth();
                $endOfMonth = Carbon::now()->endOfMonth();

                $holidayCount = 0;
                if ($dayy == null) {
                    $empD->sysemp = $value;
                }

                for ($day = $startOfMonth; $day <= $endOfMonth; $day->modify('+1 day')) {
                    $dayName = Carbon::parse($day)->locale('ar')->dayName;

                    if (in_array($dayName, $dayy)) {
                        $holidayCount++;
                    }
                }
                $holday = $holidayCount++;
                $empD->sysemp = $value - $holday;
            } else {
                $empD->sysemp = $value;
            }
        } elseif ($request->sysemp == 2) { // شهري متغير
            $value = 30;
            if ($request->check == 1) {
                $dayy = $request->free_d;

                $startOfMonth = Carbon::now()->startOfMonth();
                $endOfMonth = Carbon::now()->endOfMonth();

                $holidayCount = 0;
                if ($value == null || $dayy == null) {
                    $empD->sysemp = $value;
                }

                for ($day = $startOfMonth; $day <= $endOfMonth; $day->modify('+1 day')) {
                    $dayName = Carbon::parse($day)->locale('ar')->dayName;

                    if (in_array($dayName, $dayy)) {
                        $holidayCount++;
                    }
                }
                $holday = $holidayCount++;

                if ($value == 30) {
                    $day = Carbon::now()->daysInMonth;
                    $empD->sysemp = $day - $holday;
                } elseif ($value == 28) {
                    $day = 28;
                    $empD->sysemp = $day - $holday;
                } elseif ($value == 29) {
                    $day = 29;
                    $empD->sysemp = $day - $holday;
                } elseif ($value == 31) {
                    $day = 31;
                    $empD->sysemp = $day - $holday;
                } else {
                    $empD->sysemp = $value - $holday;
                }
            } else {
                $empD->sysemp = Carbon::now()->daysInMonth;
            }
        } else { //غير شهري
            $value = $request->sysemp;
            if ($request->check == 1) {
                $dayy = $request->free_d;

                $startOfMonth = Carbon::now()->startOfMonth();
                $endOfMonth = Carbon::now()->startOfMonth()->modify('+'.$request->sysemp.' days');

                $holidayCount = 0;
                if ($dayy == null) {
                    $empD->sysemp = $value;
                }

                for ($day = $startOfMonth; $day <= $endOfMonth; $day->modify('+1 day')) {
                    $dayName = Carbon::parse($day)->locale('ar')->dayName;

                    if (in_array($dayName, $dayy)) {
                        $holidayCount++;
                    }
                }
                $holday = $holidayCount++;

                $empD->sysemp = $request->sysemp - $holday;
            } else {
                $empD->sysemp = $request->sysemp;
            }
        }

        $empD->save();

        session()->flash('Add');

        return redirect('/employees');
    }

    /**
     * Check if the tenant has reached the employee limit based on their subscription plan.
     *
     * @return bool
     */
    protected function hasReachedEmployeeLimit()
    {
        // No employee limit enforced in single-tenant mode
        return false;
    }

    public function show_emp_index(Request $request)
    {
        $id = employees::where('num', Auth::user()->num)->first()->id;

        $salfs = salf::where('employees_id', $id)->whereBetween('date', [$request->dates, $request->datee])
            ->get();
        $diss = disemp::where('employees_id', $id)->whereBetween('date', [$request->dates, $request->datee])->get();
        $vacs = vacation::where('employees_id', $id)->whereBetween('date', [$request->dates, $request->datee])->get();
        $hafzs = hafz::where('employees_id', $id)->whereBetween('date', [$request->dates, $request->datee])->get();
        $plzs = please::where('employees_id', $id)->whereBetween('date', [$request->dates, $request->datee])->get();

        $purchases = purchases::where('employees_id', $id)->whereBetween('date', [$request->dates, $request->datee])->get();

        $loanss = loan::where('employees_id', $id)->exists();
        $startDate = Carbon::parse($request->dates)->format('Y-m-d');
        $endDate = Carbon::parse($request->datee)->format('Y-m-d');
        $loans = loan::where('employees_id', $id)->where('date_start', '<=', $startDate)
            ->where('date_end', '>=', $endDate)
            ->get();
        $loans = loan::where('employees_id', $id)->whereBetween('date_start', [$startDate, $endDate])->get();
        $loans = loan::where('employees_id', $id)->whereBetween('date_end', [$startDate, $endDate])->get();

        return view('employees.show_emp', compact('salfs', 'diss', 'vacs', 'hafzs', 'plzs', 'id', 'loans', 'purchases', 'loans'));
    }

    public function MarkAsRead_all(Request $request)
    {

        $userUnreadNotification = Auth::user()->unreadNotifications;

        if ($userUnreadNotification) {
            $userUnreadNotification->markAsRead();

            return back();
        }
    }

    public function resetEmp($id)
    {
        user::where('num', $id)->update([
            'user_agent' => null,
            'user_agent_mobile' => null
        ]);

        return back()->with('success', 'تم إعادة المزامنة وتصفير المعرفات بنجاح.');
    }

    public function in_out(Request $request)
    {
        $numid = Auth::user()->num;
        $id = employees::where('num', $numid)->first()->id;
        $emp_name = Auth::user()->name;
        $cos = count::where('numid', $numid)->whereBetween('date', [date($request->searchs), date($request->searche)])
            ->get();
        // dd($cos);
        $disemp = disemp::where('employees_id', $id)->where(function ($q) use ($request) {
            $q->whereBetween('date', [$request->searchs, $request->searche]);
        })->get();
        $salfs = salf::where('employees_id', $id)->where(function ($q) use ($request) {
            $q->whereBetween('date', [$request->searchs, $request->searche]);
        })->get();

        $hafz = hafz::where('employees_id', $id)->where(function ($q) use ($request) {
            $q->whereBetween('date', [($request->searchs), $request->searche]);
        })->get();

        $purchas = purchases::where('employees_id', $id)->where(function ($q) use ($request) {
            $q->whereBetween('date', [$request->searchs, $request->searche]);
        })->get();
        // }
        // dd($purchases);

        $bet = 0;
        $countday = 0;
        $res = 0;
        $ree = 0;

        if ($request->searchs == '') {
            $empkabd = 0;
            $absent = 0;
            $disabsent = 0;
            $overtime = 0;
            $friday = 0;
            $sysemp = 0;
            $loan = 0;
            $dis_trans = 0;
            $badel_trans = 0;
            $countdd = 0;
            $id_emp = '';
            $emp_name = '';
            $taminat = 0;
            $purchases = 0;
            $badel_sys = 0;
            $eid = 0;
            $hafz_eid = 0;
            $early_new = 0;
        } else {

            $badel_sys = 0;

            $emp_name = employees::where('num', $numid)->first()->name;
            $empps = employees::where('num', $numid)->first()->countday;

            $earlynew = counts_emps::where('employees_id', $id)
                ->whereBetween('date', [$request->searchs, $request->searche])->sum(DB::raw('TIME_TO_SEC(early)'));
            //  dd($early_count);
            //  ( $early_c=Carbon::parse($early_count)->secondsSinceMidnight() );
            ($early_new = gmdate('H:i:s', $earlynew));

            //بدل انتظام ميجا

            /*
            $early_count = count::where("employees_id", $request->name)
                ->whereBetween('date', array($request->searchs, $request->searche))->sum("early");

            //  ( $early_c=Carbon::parse($early_count)->secondsSinceMidnight() );
            ($early_c = Carbon::createFromTimestamp($early_count)->secondsSinceMidnight());


            $absent_count = count::where("employees_id", $request->name)
                ->whereBetween('date', array($request->searchs, $request->searche))->sum("absent");

            $shift_hdays = employees::find($request->name)->shift->workh;
            //  dd($shift_hdays*60*60*2);

            if (
                $early_c < $shift_hdays * 60 * 60 * 2 && $absent_count == 0 ||
                $absent_count == 1  && $early_count < $shift_hdays * 60 * 60

            ) {
                // dd("ok");
                $badel_sys = 2 * $empps;
            } else {
                $badel_sys = 0;
                // dd("no");
            }
*/

            //////////////عديه العيد

            $hafz_eid = 0;

            $hafz_eid_exists = hafzeid::whereBetween('date', [date($request->searchs), date($request->searche)])->exists();

            if ($hafz_eid_exists) {
                $hafz_eid = hafzeid::whereBetween('date', [date($request->searchs), date($request->searche)])
                    ->first()->hafzeid * $empps;
                // dd($hafz_eid);
            }

            $purchases = 0;

            $purchasesex = purchases::where('employees_id', $id)
                ->whereBetween('date', [date($request->searchs), date($request->searche)])->exists();

            if ($purchasesex) {
                $purchases = purchases::where('employees_id', $id)
                    ->whereBetween('date', [date($request->searchs), date($request->searche)])
                    ->first()->mony;
            }

            $taminat = 0;

            $taminats = taminat::where('employees_id', $id)->exists();
            if ($taminats) {
                $taminat = taminat::where('employees_id', $id)->first()->mony;
            }

            $dis_trans = 0;

            $dis_transport = dis_transport::where('employees_id', $id)->exists();
            if ($dis_transport) {
                $dis_trans = dis_transport::where('employees_id', $id)
                    ->first()->mony;
            }

            $badel_trans = 0;

            $badel_transport = badel_transport::where('employees_id', $id)->exists();
            if ($badel_transport) {
                $badel_trans = badel_transport::where('employees_id', $id)->first()->mony;
            }

            $id_emp = $id;

            $res = carbon::parse($request->searchs);
            $ree = carbon::parse($request->searche);
            ($bet = $res->diffInDays($ree) + 1);

            ($count_null_absent = counts_emps::whereNull('absent')
                ->where('employees_id', $id)
                ->whereBetween('date', [date($request->searchs), date($request->searche)])->get()->count());

            ($countdd = $bet - $count_null_absent);

            $loan = 0;
            $loan_exstis = loan::where('employees_id', $id)->exists();
            if ($loan_exstis) {

                $date_start = Carbon::parse(loan::where('employees_id', $id)->first()->date_start);
                $date_end = Carbon::parse(loan::where('employees_id', $id)->first()->date_end);
                $loan_month = loan::where('employees_id', $id)->first()->month;

                if ($res->gte($date_start) && $ree->lte($date_end)) {
                    $loan = loan::where('employees_id', $id)
                        ->where('date_start', '<=', $res)
                        ->where('date_end', '>=', $ree)->get()->sum('mony_now');
                }
            }

            $countday = employees::where('num', $numid)->first()->countday;
            $idd = employees::where('num', $numid)->first()->id;

            $empkabd = employees::when($idd, function ($i) use ($idd) {
                return $i->where('id', $idd);
            })->first()->kabd;

            $sysemp = employees::find($idd)->sysemp;
            $absent = $cos->where('absent', 1)->sum('absent');

            //////////////////
            $disabsent = $absent * shift::where('id', employees::when($idd, function ($i) use ($idd) {
                return $i->where('id', $idd);
            })->first()->shift_id)->first()->absent * $empkabd / $sysemp;

            //////////////////////

            // $ovt = $cos->sum("othours");
            // $overtime = shift::where("id", employees::where("id", $idd)->first()->shift_id)->first()->ot *
            //     $ovt / 60 / 60 * employees::where("id", $idd)->first()->kabd / $sysemp / shift::where("id", employees::where("id", $idd)->first()->shift_id)->first()->workh;

            $overtime = $cos->sum('count_ot');

            // $friday = shift::where("id", employees::where("id", $request->name)->first()->shift_id)->first()->friday *
            $friday = employees::where('num', $numid)->first()->friday *
                $cos->sum('friday') * employees::where('num', $numid)->first()->kabd / $sysemp;
        }

        $employees = employees::get();

        $disin = $cos->sum('dis');
        $disout = $cos->sum('disout');

        $disdis = $disemp->sum('dis');
        $dissalf = $salfs->sum('salf');
        $hafzs = $hafz->sum('hafz');
        $dayss = $cos->sum('dayh');
        $eid = $cos->sum('eid');

        $fri = $cos->sum('friday');
        $othours = $cos->sum('othours');

        $count_vacation = vacation::where('employees_id', $request->name)->get()->count();

        ($afterdis = $disin + $disout + $disdis + $dissalf + $disabsent + $loan + $dis_trans + $badel_trans + $taminat + $purchases);

        $afterot =
            $countday *
            $sysemp -
            $afterdis
            // $disin - $loan - $disout - $disdis - $dissalf - $badel_trans - $dis_trans - $disabsent
            + $overtime + $friday + $hafzs + $badel_sys + $hafz_eid;

        // dd($afterot);
        $salary_days =
            $countday *
            $bet -
            $afterdis
            // $disin - $loan - $disout - $disdis - $dissalf - $badel_trans - $dis_trans - $disabsent
            + $overtime + $friday + $hafzs + $badel_sys + $hafz_eid;

        return view('employees.counts', compact(
            'cos',
            'disemp',
            'employees',
            'salfs',
            'disin',
            'disout',
            'absent',
            'disdis',
            'dissalf',
            'empkabd',
            'afterdis',
            'disabsent',
            'overtime',
            'afterot',
            'dayss',
            'friday',
            'fri',
            'othours',
            'hafzs',
            'hafz',
            'ree',
            'res',
            'bet',
            'countday',
            'sysemp',
            'loan',
            'dis_trans',
            'badel_trans',
            'id_emp',
            'count_vacation',
            'taminat',
            'purchases',
            'badel_sys',
            'emp_name',
            'eid',
            'hafz_eid',
            'early_new',
            'salary_days',
            'purchas',

        ));
    }
}
