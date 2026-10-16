<?php

declare(strict_types=1);

use App\Http\Controllers\BadelTransportController;
use App\Http\Controllers\ChangeShiftController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisempController;
use App\Http\Controllers\DisTransportController;
use App\Http\Controllers\EidController;
use App\Http\Controllers\EmployeeLocationController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\ForgetFingerController;
use App\Http\Controllers\ganeralController;
use App\Http\Controllers\HafzController;
use App\Http\Controllers\HafzeidController;
use App\Http\Controllers\HeadSectionsController;
use App\Http\Controllers\IpzktController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LocationRequestController;
use App\Http\Controllers\MikroController;
use App\Http\Controllers\NozktController;
use App\Http\Controllers\PleaseController;
use App\Http\Controllers\PlzotController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchasesController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalfController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ShiftWeekController;
use App\Http\Controllers\show_notifyController;
use App\Http\Controllers\show_talp;
use App\Http\Controllers\SupShiftController;
use App\Http\Controllers\TaminatController;
use App\Http\Controllers\TlbpleaseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacationController;
use App\Http\Controllers\ZktController;
use App\Http\Controllers\ZktsController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'web',
])->group(function () {

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

       Route::get('/test', function () {
        for($i=0;$i<30;$i++){
           DB::table("zkts")->insert([
                "numid" => "1",
                "in" => "19:00:00",
                "out" => "23:00:00",
                "absent" => "حاضر",
                "date" => now()->startOfMonth()->addday($i),
            ]);
        }
        return "done";
    });

    Route::get('/dashboard', function () {
        if (session('dashboard_version') == 'old') {
            return view('index1');
        }

        return view('index');
    })->middleware(['auth', 'verified', 'ahmed', 'CheckAppExpiry'])->name('dashboard');

    Route::get('/dashboard-version/{version}', [DashboardController::class, 'setVersion'])->name('set.dashboard');
    Route::post('/request-plan', [DashboardController::class, 'requestPlan'])->name('tenant.request-plan');

    // ADMS - استقبال البيانات من أجهزة ZKT عبر بروتوكول ADMS (SN-based)
    // يجب أن يكون خارج middleware المصادقة لأن الجهاز لا يملك session
    Route::match(['get', 'post'], '/cdata/iclock/cdata', [IpzktController::class, 'adms'])
        ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
    Route::match(['get', 'post'], '/iclock/cdata', [IpzktController::class, 'adms'])
        ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

    // مسار سحب الأوامر اللي الجهاز هيضيفه برضه
    Route::match(['get', 'post'], '/cdata/iclock/getrequest', [IpzktController::class, 'adms'])
        ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
    Route::match(['get', 'post'], '/iclock/getrequest', [IpzktController::class, 'adms'])
        ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

    // المسار الجديد لتأكيد تنفيذ الأوامر
    Route::match(['get', 'post'], '/cdata/iclock/devicecmd', [IpzktController::class, 'adms'])
        ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
    Route::match(['get', 'post'], '/iclock/devicecmd', [IpzktController::class, 'adms'])
        ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

    Route::middleware(['auth', 'verified', 'ahmed', 'CheckAppExpiry'])->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::get('/check-attendance-location', [EmployeeLocationController::class, 'index'])->name('check_attendance_location');
        Route::get('/employee-location-history', [EmployeeLocationController::class, 'history'])->name('employee.location_history');
        Route::get('/employee-locations-report', [EmployeeLocationController::class, 'report'])->name('employee.locations_report');

        // On-Demand Location Request Routes
        Route::post('/location-request/send', [LocationRequestController::class, 'sendRequest'])->name('location_request.send');
        Route::get('/location-request/check', [LocationRequestController::class, 'checkRequest'])->name('location_request.check');
        Route::post('/location-request/respond', [LocationRequestController::class, 'respondRequest'])->name('location_request.respond');
        Route::get('/location-request/latest', [LocationRequestController::class, 'getLatestResponses'])->name('location_request.latest');
        Route::get('/location-request/live', [LocationRequestController::class, 'getLiveLocations'])->name('location_request.live');
        Route::delete('/location-request/{id}', [LocationRequestController::class, 'destroy'])->name('location_request.destroy');

        Route::resource('/hafz', HafzController::class);
        Route::resource('/zkt_name', IpzktController::class);
        Route::get('/check_zkt_status/{ip}', [IpzktController::class, 'check_status'])->name('check_zkt_status')->where('ip', '[0-9A-Za-z._-]+');
        Route::resource('/ganeral', ganeralController::class);
        Route::resource('/disemp', DisempController::class);
        Route::resource('/employees', EmployeesController::class);
        Route::get('/delete_account/{id}', [EmployeesController::class, 'delete_account'])->name('delete_account');
        Route::post('/employees_deleteAll', [EmployeesController::class, 'employees_deleteAll'])->name('employees_deleteAll');

        // مواقع البصمة المسموحة للموظفين (GPS) - القديمة
        Route::get('/employee-locations/{employee_id}', [\App\Http\Controllers\EmployeeAllowedLocationController::class, 'getByEmployee'])->name('employee.locations.get');
        Route::post('/employee-locations', [\App\Http\Controllers\EmployeeAllowedLocationController::class, 'store'])->name('employee.locations.store');
        Route::delete('/employee-locations/{id}', [\App\Http\Controllers\EmployeeAllowedLocationController::class, 'destroy'])->name('employee.locations.destroy');

        // مواقع الشركة المركزية (الجديدة)
        Route::get('/company-locations', [\App\Http\Controllers\CompanyLocationController::class, 'index'])->name('company.locations.index');
        Route::post('/company-locations', [\App\Http\Controllers\CompanyLocationController::class, 'store'])->name('company.locations.store');
        Route::put('/company-locations/{id}', [\App\Http\Controllers\CompanyLocationController::class, 'update'])->name('company.locations.update');
        Route::delete('/company-locations/{id}', [\App\Http\Controllers\CompanyLocationController::class, 'destroy'])->name('company.locations.destroy');
        Route::get('/company-locations/employee/{employee_id}', [\App\Http\Controllers\CompanyLocationController::class, 'getEmployeeLocations'])->name('company.locations.employee');
        Route::post('/company-locations/employee/{employee_id}/sync', [\App\Http\Controllers\CompanyLocationController::class, 'syncEmployeeLocations'])->name('company.locations.sync');
        Route::resource('/sections', SectionController::class);
        Route::post('/section_bulk_delete', [SectionController::class, 'bulk_delete'])->name('section.bulk_delete');
        Route::post('/sections/{id}/assign_managers', [SectionController::class, 'assignManagers'])->name('sections.assign_managers');
        Route::resource('/shifts', ShiftController::class);
        Route::resource('/sup_shifts', SupShiftController::class);
        Route::resource('/zkt', ZktController::class);
        Route::resource('/zkts', ZktsController::class);
        Route::delete('/zkts_bulk_delete', [ZktsController::class, 'bulkDelete'])->name('zkts.bulk_delete');
        Route::resource('/mikro', MikroController::class);
        Route::resource('/nozkt', NozktController::class);
        Route::resource('/ple', PleaseController::class);
        Route::resource('/chshift', ChangeShiftController::class);
        Route::resource('/forget_finger', ForgetFingerController::class);
        Route::post('/forget_finger_bulk_delete', [ForgetFingerController::class, 'bulk_delete'])->name('forget_finger.bulk_delete');

        Route::resource('/vacations', VacationController::class);
        Route::post('/vacation_bulk_delete', [VacationController::class, 'bulk_delete'])->name('vacation.bulk_delete');

        Route::resource('/loans', LoanController::class);

        Route::resource('/shift_w', ShiftWeekController::class);
        Route::get('/shift_w_edit', [ShiftWeekController::class, 'edit'])->name('shift_w.edit');

        Route::resource('/dis_transport', DisTransportController::class);
        Route::resource('/badel_transport', BadelTransportController::class);

        Route::resource('/taminat', TaminatController::class);
        Route::post('/taminat_bulk_delete', [TaminatController::class, 'bulk_delete'])->name('taminat.bulk_delete');

        Route::resource('/purchases', PurchasesController::class);
        Route::post('/purchases_bulk_delete', [PurchasesController::class, 'bulk_delete'])->name('purchases.bulk_delete');

        Route::resource('/ch_shift_sections', HeadSectionsController::class);
        Route::resource('/eids', EidController::class);
        Route::resource('/plzot', PlzotController::class);
        Route::post('/ok_all_ot', [PlzotController::class, 'create'])->name('ok_all_ot');
        Route::get('/plzotu/{id}', [PlzotController::class, 'update'])->name('plzotup');
        Route::get('/plzotno/{id}', [PlzotController::class, 'show'])->name('showu');
        Route::resource('/hafzeids', HafzeidController::class);

        Route::post('/disemp_bulk_delete', [DisempController::class, 'bulk_delete'])->name('disemp.bulk_delete');
        Route::post('/hafz_bulk_delete', [HafzController::class, 'bulk_delete'])->name('hafz.bulk_delete');
        Route::post('/please_bulk_delete', [PleaseController::class, 'bulk_delete'])->name('please.bulk_delete');
        Route::post('/salf_bulk_delete', [SalfController::class, 'bulk_delete'])->name('salf.bulk_delete');
        Route::post('/shifts_bulk_delete', [ShiftController::class, 'bulk_delete'])->name('shifts.bulk_delete');
        Route::post('/dis_transport_bulk_delete', [DisTransportController::class, 'bulk_delete'])->name('dis_transport.bulk_delete');
        Route::post('/badel_transport_bulk_delete', [BadelTransportController::class, 'bulk_delete'])->name('badel_transport.bulk_delete');
        Route::post('/loans_bulk_delete', [LoanController::class, 'bulk_delete'])->name('loans.bulk_delete');
        Route::post('/eids_bulk_delete', [EidController::class, 'bulk_delete'])->name('eids.bulk_delete');
        Route::post('/hafzeids_bulk_delete', [HafzeidController::class, 'bulk_delete'])->name('hafzeids.bulk_delete');
        Route::post('/updateout', [ZktController::class, 'updateout'])->name('updateout');
        Route::post('/breakout', [ZktController::class, 'breakout'])->name('breakout');
        Route::post('/breakin', [ZktController::class, 'breakin'])->name('breakin');

        // AI Chat Assistant
        Route::get('/chat', function () {
            return view('ai_chat');
        })->name('chat.page');
        Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
        Route::get('/chat/settings', [ChatController::class, 'getSettings'])->name('chat.settings.get');
        Route::post('/chat/settings', [ChatController::class, 'saveSettings'])->name('chat.settings.save');

        Route::get('/maps/{id}/{date}', [MikroController::class, 'maps'])->name('maps');

        Route::post('/zkt/getuser', [ZktController::class, 'insert'])->name('zkt.insert');
        Route::post('/zkt/upload', [ZktController::class, 'upload'])->name('zkt.upload');
        Route::post('/cr', [EmployeesController::class, 'ec'])->name('cr');
        Route::post('/store_addzkt', [EmployeesController::class, 'store_addzkt'])->name('store_addzkt');
        Route::post('/store_nozkt', [EmployeesController::class, 'store_nozkt'])->name('store_nozkt');
        Route::get('/show_emp_index', [EmployeesController::class, 'show_emp_index'])->name('show_emp_index');
        Route::get('/in_out', [EmployeesController::class, 'in_out'])->name('in_out');
        Route::post('/reset_emp/{id}', [EmployeesController::class, 'resetEmp'])->name('emp.reset');

        Route::post('/emp-import', [CountController::class, 'store'])->name('emp-import');
        Route::post('/salf_import', [SalfController::class, 'salf_import'])->name('salf_import');
        Route::resource('/counts', CountController::class);
        Route::get('/countr', [CountController::class, 'countr'])->name('countr');
        Route::post('/employee_bulk_delete', [CountController::class, 'bulk_delete'])->name('employee.bulk_delete');

        Route::get('/break', [ShiftController::class, 'show'])->name('break');
        Route::patch('/update_break/{id}', [ShiftController::class, 'update_break'])->name('update_break');
        Route::patch('/del_break/{id}', [ShiftController::class, 'del_break'])->name('del_break');

        Route::resource('/salf', SalfController::class);
        Route::get('/multi_salf', [SalfController::class, 'create_multi'])->name('salf.multi');
        Route::post('/salf_bulk_delete', [SalfController::class, 'bulk_delete'])->name('salf.bulk_delete');

        Route::resource('/disemp', DisempController::class);
        Route::get('/multi_disemp', [DisempController::class, 'create_multi'])->name('disemp.multi');
        Route::post('/disemp_bulk_delete', [DisempController::class, 'bulk_delete'])->name('disemp.bulk_delete');

        Route::resource('/vacation', VacationController::class);
        Route::get('/multi_vacation', [VacationController::class, 'create_multi'])->name('vacation.multi');
        Route::post('/vacation_bulk_delete', [VacationController::class, 'bulk_delete'])->name('vacation.bulk_delete');

        Route::resource('/please', PleaseController::class);
        Route::get('/multi_please', [PleaseController::class, 'create_multi'])->name('please.multi');
        Route::post('/please_bulk_delete', [PleaseController::class, 'bulk_delete'])->name('please.bulk_delete');

        // Comprehensive Bulk Action
        Route::get('/bulk-action', [\App\Http\Controllers\BulkActionController::class, 'index'])->name('bulk_action.index');
        Route::post('/bulk-action', [\App\Http\Controllers\BulkActionController::class, 'store'])->name('bulk_action.store');

        Route::resource('/forget_finger', ForgetFingerController::class);
        Route::get('/multi_forget_finger', [ForgetFingerController::class, 'create_multi'])->name('forget_finger.multi');
        Route::post('/forget_finger_bulk_delete', [ForgetFingerController::class, 'bulk_delete'])->name('forget_finger.bulk_delete');

        Route::resource('/hafz', HafzController::class);
        Route::get('/multi_hafz', [HafzController::class, 'create_multi'])->name('hafz.multi');
        Route::post('/hafz_bulk_delete', [HafzController::class, 'bulk_delete'])->name('hafz.bulk_delete');

        Route::resource('/taminat', TaminatController::class);
        Route::get('/multi_taminat', [TaminatController::class, 'create_multi'])->name('taminat.multi');
        Route::post('/taminat_bulk_delete', [TaminatController::class, 'bulk_delete'])->name('taminat.bulk_delete');

        Route::resource('/purchases', PurchasesController::class);
        Route::get('/multi_purchases', [PurchasesController::class, 'create_multi'])->name('purchases.multi');
        Route::post('/purchases_bulk_delete', [PurchasesController::class, 'bulk_delete'])->name('purchases.bulk_delete');

        Route::get('/zktshow', [CountController::class, 'show'])->name('zktshow');
        Route::post('/zktstore', [CountController::class, 'store1'])->name('zktstore');

        Route::get('/employes/{id}', [DisempController::class, 'getemployees']);
        Route::get('/getsec/{id}', [DisempController::class, 'getsec']);

        Route::get('MarkAsRead_all', [SectionController::class, 'MarkAsRead_all'])->name('MarkAsRead_all');

        Route::get('/show/{id}/{type}', [show_notifyController::class, 'show'])->name('show.talb');

        // tlbplease
        Route::get('/plzfree', [TlbpleaseController::class, 'index'])->name('plzfree');
        Route::post('/plzfreestore', [TlbpleaseController::class, 'store'])->name('plzfreestore');
        Route::delete('/plzfreedel', [TlbpleaseController::class, 'destroy'])->name('plzfreedel');
        Route::patch('/plzfreeedit', [TlbpleaseController::class, 'update'])->name('plzfreeedit');

        // tlbpsalf
        Route::get('/plzsalf', [TlbpleaseController::class, 'indexsalf'])->name('plzsalf');
        Route::post('/plzsalfstore', [TlbpleaseController::class, 'storesalf'])->name('plzsalfstore');
        Route::delete('/plzsalfdel', [TlbpleaseController::class, 'destroysalf'])->name('plzsalfdel');
        Route::patch('/plzsalfedit', [TlbpleaseController::class, 'updatesalf'])->name('plzsalfedit');

        // tlbp shift change
        Route::get('/plzshift', [TlbpleaseController::class, 'indexshift'])->name('plzshift');
        Route::post('/plzshiftstore', [TlbpleaseController::class, 'storeshift'])->name('plzshiftstore');
        Route::delete('/plzshiftdel', [TlbpleaseController::class, 'destroyshift'])->name('plzshiftdel');
        Route::patch('/plzshiftedit', [TlbpleaseController::class, 'updateshift'])->name('plzshiftedit');

        // tlbp plz
        Route::get('/plzplz', [TlbpleaseController::class, 'indexplz'])->name('plzplz');
        Route::post('/plzplzstore', [TlbpleaseController::class, 'storeplz'])->name('plzplzstore');
        Route::delete('/plzplzdel', [TlbpleaseController::class, 'destroyplz'])->name('plzplzdel');
        Route::patch('/plzplzedit', [TlbpleaseController::class, 'updateplz'])->name('plzplzplzedit');

        // show free
        Route::get('/show_free', [show_talp::class, 'show_free'])->name('show_free');
        Route::post('/ok_free', [show_talp::class, 'ok_free'])->name('ok_free');
        Route::delete('/no_free', [show_talp::class, 'no_free'])->name('no_free');

        // show salf
        Route::get('/show_salf', [show_talp::class, 'show_salf'])->name('show_salf');
        Route::post('/ok_salf', [show_talp::class, 'ok_salf'])->name('ok_salf');
        Route::delete('/no_salfs', [show_talp::class, 'no_salf'])->name('no_salf');

        // show plz
        Route::get('/show_plz', [show_talp::class, 'show_plz'])->name('show_plz');
        Route::post('/ok_plz', [show_talp::class, 'ok_plz'])->name('ok_plz');
        Route::delete('/no_plz', [show_talp::class, 'no_plz'])->name('no_plz');

        // show ch-shiftshift
        Route::get('/show_shift', [show_talp::class, 'show_shift'])->name('show_shift');
        Route::post('/ok_shift', [show_talp::class, 'ok_shift'])->name('ok_shift');
        Route::delete('/no_shift', [show_talp::class, 'no_shift'])->name('no_shift');

        Route::get('/notifications/latest', [show_notifyController::class, 'getLatestNotifications'])->name('notifications.latest');
    });

    // ================================================
    // Mobile API Routes (مفتوحة للمصادقة عبر الموبايل)
    // ================================================
    Route::prefix('mobile-api')->group(function () {
        Route::post('/login', [LocationRequestController::class, 'mobileLogin']);
        Route::post('/send-location', [LocationRequestController::class, 'mobileLocation']);

        // المسارات التي تتطلب تسجيل الدخول
        Route::get('/attendance-status', [LocationRequestController::class, 'attendanceStatus']);

        // Mobile Employee Requests (سلفة - إجازة - إذن)
        Route::prefix('requests')->group(function () {
            Route::post('/loan', [\App\Http\Controllers\MobileRequestController::class, 'requestLoan']);
            Route::post('/vacation', [\App\Http\Controllers\MobileRequestController::class, 'requestVacation']);
            Route::post('/permission', [\App\Http\Controllers\MobileRequestController::class, 'requestPermission']);
            Route::get('/my-requests', [\App\Http\Controllers\MobileRequestController::class, 'myRequests']);
            Route::get('/notifications', [\App\Http\Controllers\MobileRequestController::class, 'myNotifications']);
            Route::post('/notifications/{id}/read', [\App\Http\Controllers\MobileRequestController::class, 'markNotificationRead']);
            Route::post('/notifications/read-all', [\App\Http\Controllers\MobileRequestController::class, 'markAllNotificationsRead']);
        });

        // Manager mobile endpoints
        Route::prefix('manager')->group(function () {
            Route::get('/pending-requests', [\App\Http\Controllers\MobileRequestController::class, 'managerPendingRequests']);
            Route::post('/respond-request', [\App\Http\Controllers\MobileRequestController::class, 'respondToRequest']);
        });
        Route::post('/check-in', [LocationRequestController::class, 'mobileCheckIn']);
        Route::post('/check-out', [LocationRequestController::class, 'mobileCheckOut']);
        Route::post('/break-out', [LocationRequestController::class, 'mobileBreakOut']);
        Route::post('/break-in', [LocationRequestController::class, 'mobileBreakIn']);
        Route::post('/mock-alert', [LocationRequestController::class, 'mockAlert']);
    });

    require __DIR__.'/auth.php';
});
