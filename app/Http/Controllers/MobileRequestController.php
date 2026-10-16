<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\salf;
use App\Models\please;
use App\Models\vacation;
use App\Models\employees;
use App\Models\tlbplease;
use App\Models\change_shift;
use Illuminate\Http\Request;
use App\Notifications\salf_notiy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class MobileRequestController extends Controller
{
    /**
     * الحصول على المستخدم الحالي من الجلسة
     */
    private function getMobileUser()
    {
        if (Auth::check()) {
            return Auth::user();
        }

        $userId = session('mobile_user_id');
        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                Auth::login($user);
                return $user;
            }
        }

        return null;
    }

    // ================================================
    // طلب سلفة (Loan)
    // ================================================
    public function requestLoan(Request $request)
    {
        $user = $this->getMobileUser();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'غير مصرح بالدخول'], 401);
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'reason' => 'nullable|string|max:500',
        ]);

        $emp = employees::where('num', $user->num)->first();
        if (!$emp) {
            return response()->json(['success' => false, 'message' => 'بيانات الموظف غير موجودة'], 404);
        }

        $noti = tlbplease::create([
            'datesalf' => $request->date,
            'num' => $user->num,
            'employees_id' => $emp->id,
            'textsalf' => $request->reason,
            'name' => $user->name,
            'salf' => $request->amount,
            'shift_id' => $emp->shift_id,
            'status' => 3, // معلق (pending)
        ]);

        // إرسال إشعار للمالك ورؤساء الأقسام
        $d = [
            "masg" => "طلب سلفه بقيمه {$noti->salf} جنيه",
            "id" => $noti->id,
            "date" => $noti->created_at,
            "dec" => $noti->textsalf,
            "type" => "tlpsalf",
        ];

        $this->notifyManagers($emp->id, $d);

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال طلب السلفة بنجاح',
            'request' => [
                'id' => $noti->id,
                'type' => 'salf',
                'amount' => $noti->salf,
                'date' => $noti->datesalf,
                'reason' => $noti->textsalf,
                'status' => $noti->status,
                'status_text' => 'قيد المراجعة',
                'created_at' => $noti->created_at->toDateTimeString(),
            ]
        ]);
    }

    // ================================================
    // طلب إجازة (Vacation)
    // ================================================
    public function requestVacation(Request $request)
    {
        $user = $this->getMobileUser();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'غير مصرح بالدخول'], 401);
        }

        $request->validate([
            'date' => 'required|date',
            'reason' => 'nullable|string|max:500',
        ]);

        $emp = employees::where('num', $user->num)->first();
        if (!$emp) {
            return response()->json(['success' => false, 'message' => 'بيانات الموظف غير موجودة'], 404);
        }

        $noti = tlbplease::create([
            'date' => $request->date,
            'num' => $user->num,
            'employees_id' => $emp->id,
            'textfree' => $request->reason,
            'name' => $user->name,
            'shift_id' => $emp->shift_id,
            'status' => 3, // معلق
        ]);

        // إرسال إشعار للمالك ورؤساء الأقسام
        $d = [
            "masg" => "طلب اجازه يوم {$noti->date}",
            "id" => $noti->id,
            "date" => $noti->created_at,
            "dec" => $noti->textfree,
            "type" => "tlpfree",
        ];

        $this->notifyManagers($emp->id, $d);

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال طلب الإجازة بنجاح',
            'request' => [
                'id' => $noti->id,
                'type' => 'vacation',
                'date' => $noti->date,
                'reason' => $noti->textfree,
                'status' => $noti->status,
                'status_text' => 'قيد المراجعة',
                'created_at' => $noti->created_at->toDateTimeString(),
            ]
        ]);
    }

    // ================================================
    // طلب إذن (Permission)
    // ================================================
    public function requestPermission(Request $request)
    {
        $user = $this->getMobileUser();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'غير مصرح بالدخول'], 401);
        }

        $request->validate([
            'date' => 'required|date',
            'duration_minutes' => 'required|integer|min:1|max:480',
            'reason' => 'nullable|string|max:500',
            'in_out' => 'nullable|in:in,out', // دخول أو خروج
        ]);

        $emp = employees::where('num', $user->num)->first();
        if (!$emp) {
            return response()->json(['success' => false, 'message' => 'بيانات الموظف غير موجودة'], 404);
        }

        $inOutValue = ($request->in_out === 'in') ? 0 : 1;

        $noti = tlbplease::create([
            'dateplz' => $request->date,
            'num' => $user->num,
            'employees_id' => $emp->id,
            'textplz' => $request->reason,
            'name' => $user->name,
            'in_out' => $inOutValue,
            'min' => $request->duration_minutes,
            'shift_id' => $emp->shift_id,
            'status' => 3, // معلق
        ]);

        // إرسال إشعار للمالك ورؤساء الأقسام
        $d = [
            "masg" => "طلب اذن يوم {$noti->dateplz} لمدة {$noti->min} دقيقة",
            "id" => $noti->id,
            "date" => $noti->created_at,
            "dec" => $noti->textplz,
            "type" => "tlpplz",
        ];

        $this->notifyManagers($emp->id, $d);

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال طلب الإذن بنجاح',
            'request' => [
                'id' => $noti->id,
                'type' => 'permission',
                'date' => $noti->dateplz,
                'duration_minutes' => $noti->min,
                'in_out' => ($noti->in_out == 0) ? 'in' : 'out',
                'reason' => $noti->textplz,
                'status' => $noti->status,
                'status_text' => 'قيد المراجعة',
                'created_at' => $noti->created_at->toDateTimeString(),
            ]
        ]);
    }

    // ================================================
    // جلب طلبات الموظف
    // ================================================
    public function myRequests()
    {
        $user = $this->getMobileUser();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'غير مصرح بالدخول'], 401);
        }

        $requests = tlbplease::where('num', $user->num)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $statusText = 'معلق';
                if ($item->status == 0) $statusText = 'تم القبول';
                if ($item->status == 1) $statusText = 'مرفوض';
                if ($item->status == 3) $statusText = 'قيد المراجعة';

                // تحديد نوع الطلب
                $type = 'unknown';
                $displayDate = null;
                $details = [];

                if ($item->date && $item->textfree) {
                    $type = 'vacation';
                    $displayDate = $item->date;
                    $details = ['reason' => $item->textfree];
                } elseif ($item->datesalf && $item->salf) {
                    $type = 'loan';
                    $displayDate = $item->datesalf;
                    $details = ['amount' => $item->salf, 'reason' => $item->textsalf];
                } elseif ($item->dateplz && $item->min) {
                    $type = 'permission';
                    $displayDate = $item->dateplz;
                    $details = [
                        'duration_minutes' => $item->min,
                        'in_out' => ($item->in_out == 0) ? 'in' : 'out',
                        'reason' => $item->textplz,
                    ];
                }

                return [
                    'id' => $item->id,
                    'type' => $type,
                    'date' => $displayDate,
                    'status' => $item->status,
                    'status_text' => $statusText,
                    'details' => $details,
                    'created_at' => $item->created_at->toDateTimeString(),
                ];
            });

        return response()->json([
            'success' => true,
            'requests' => $requests,
        ]);
    }

    // ================================================
    // جلب إشعارات الموظف
    // ================================================
    public function myNotifications()
    {
        $user = $this->getMobileUser();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'غير مصرح بالدخول'], 401);
        }

        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->map(function ($notification) {
                $data = $notification->data;
                return [
                    'id' => $notification->id,
                    'message' => $data['masg'] ?? '',
                    'type' => $data['type'] ?? '',
                    'request_id' => $data['id'] ?? null,
                    'date' => $data['date'] ?? '',
                    'description' => $data['dec'] ?? '',
                    'is_read' => $notification->read_at !== null,
                    'read_at' => $notification->read_at ? $notification->read_at->toDateTimeString() : null,
                    'created_at' => $notification->created_at->toDateTimeString(),
                    'time_ago' => $notification->created_at->diffForHumans(),
                ];
            });

        $unreadCount = $user->unreadNotifications()->count();

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }

    // ================================================
    // تعيين الإشعار كمقروء
    // ================================================
    public function markNotificationRead($id)
    {
        $user = $this->getMobileUser();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'غير مصرح بالدخول'], 401);
        }

        $notification = $user->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    // ================================================
    // تعيين كل الإشعارات كمقروءة
    // ================================================
    public function markAllNotificationsRead()
    {
        $user = $this->getMobileUser();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'غير مصرح بالدخول'], 401);
        }

        $user->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }

    // ================================================
    // إشعارات المديرين (الطلبات الواردة)
    // ================================================
    public function managerPendingRequests()
    {
        $user = $this->getMobileUser();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'غير مصرح بالدخول'], 401);
        }

        // التحقق من أن المستخدم مالك أو رئيس قسم
        $isOwner = $user->hasRole('owner');
        $isDeptManager = $user->hasRole('رئيس قسم');

        if (!$isOwner && !$isDeptManager) {
            return response()->json(['success' => false, 'message' => 'غير مصرح لك بعرض الطلبات'], 403);
        }

        $query = tlbplease::where('status', 3)->with('employees')->orderBy('created_at', 'desc');

        if ($isDeptManager && !$isOwner) {
            // رئيس قسم يرى فقط طلبات موظفي أقسامه
            $managedSectionIds = $user->managedSectionIds();
            $empIds = employees::whereIn('section_id', $managedSectionIds)->pluck('id');
            $query->whereIn('employees_id', $empIds);
        }

        $requests = $query->get()->map(function ($item) {
            $type = 'unknown';
            $details = [];

            if ($item->date && $item->textfree) {
                $type = 'vacation';
                $details = ['reason' => $item->textfree];
            } elseif ($item->datesalf && $item->salf) {
                $type = 'loan';
                $details = ['amount' => $item->salf, 'reason' => $item->textsalf];
            } elseif ($item->dateplz && $item->min) {
                $type = 'permission';
                $details = [
                    'duration_minutes' => $item->min,
                    'in_out' => ($item->in_out == 0) ? 'in' : 'out',
                    'reason' => $item->textplz,
                ];
            }

            return [
                'id' => $item->id,
                'type' => $type,
                'employee_name' => $item->employees->name ?? $item->name,
                'employee_num' => $item->num,
                'details' => $details,
                'created_at' => $item->created_at->toDateTimeString(),
                'time_ago' => $item->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'success' => true,
            'pending_requests' => $requests,
            'unread_count' => $requests->count(),
        ]);
    }

    // ================================================
    // قبول أو رفض الطلب (للمديرين)
    // ================================================
    public function respondToRequest(Request $request)
    {
        $user = $this->getMobileUser();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'غير مصرح بالدخول'], 401);
        }

        $request->validate([
            'request_id' => 'required|integer|exists:tlbpleases,id',
            'action' => 'required|in:approve,reject',
        ]);

        $tlb = tlbplease::findOrFail($request->request_id);

        // التحقق من الصلاحية
        $isOwner = $user->hasRole('owner');
        $isDeptManager = $user->hasRole('رئيس قسم');

        if (!$isOwner && !$isDeptManager) {
            return response()->json(['success' => false, 'message' => 'غير مصرح لك'], 403);
        }

        if ($isDeptManager && !$isOwner) {
            $managedSectionIds = $user->managedSectionIds();
            $emp = employees::find($tlb->employees_id);
            if (!$emp || !in_array($emp->section_id, $managedSectionIds)) {
                return response()->json(['success' => false, 'message' => 'غير مصرح لك'], 403);
            }
        }

        $oldStatus = $tlb->status;
        if ($oldStatus != 3) {
            return response()->json(['success' => false, 'message' => 'تم الرد على هذا الطلب مسبقاً']);
        }

        if ($request->action == 'approve') {
            $tlb->update(['status' => 0]);

            // إذا كان طلب إجازة، قم بإنشاء سجل الإجازة الفعلية
            if ($tlb->date && $tlb->textfree) {
                vacation::create([
                    "date" => $tlb->date,
                    "dec" => $tlb->textfree,
                    "employees_id" => $tlb->employees_id,
                ]);
            }

            // إذا كان طلب سلفة، قم بإنشاء سجل السلفة الفعلية
            if ($tlb->datesalf && $tlb->salf) {
                $emp = employees::find($tlb->employees_id);
                salf::create([
                    "date" => $tlb->datesalf,
                    "salf" => $tlb->salf,
                    "dec" => $tlb->textsalf,
                    "employees_id" => $tlb->employees_id,
                    "section_id" => $emp ? $emp->section_id : null,
                ]);
            }

            // إذا كان طلب إذن، قم بإنشاء سجل الإذن الفعلي
            if ($tlb->dateplz && $tlb->min) {
                please::create([
                    "date" => $tlb->dateplz,
                    "in_out" => ($tlb->in_out == 0 || $tlb->in_out === 'in') ? 0 : 1,
                    "employees_id" => $tlb->employees_id,
                    "minutes" => $tlb->min,
                    "dec" => $tlb->textplz,
                ]);
            }

            $message = "تم قبول طلبك";
            $type_suffix = "ok";

        } else {
            $tlb->update(['status' => 1]);
            $message = "تم رفض طلبك";
            $type_suffix = "no";
        }

        // إرسال إشعار للموظف
        $typeMap = [
            'tlpfree' => 'free',
            'tlpsalf' => 'salf',
            'tlpplz' => 'plz',
        ];
        $reqType = '';
        if ($tlb->date && $tlb->textfree) $reqType = 'free';
        elseif ($tlb->datesalf && $tlb->salf) $reqType = 'salf';
        elseif ($tlb->dateplz && $tlb->min) $reqType = 'plz';

        $d = [
            "masg" => $message,
            "id" => $tlb->id,
            "date" => now()->toDateString(),
            "dec" => $tlb->textfree ?? $tlb->textsalf ?? $tlb->textplz ?? '',
            "type" => $type_suffix . '_' . $reqType,
        ];

        $empUser = User::where("num", $tlb->num)->first();
        if ($empUser) {
            Notification::send($empUser, new salf_notiy($d));
        }

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    /**
     * إرسال إشعار للمديرين (مالك + رؤساء الأقسام)
     */
    private function notifyManagers($employees_id, $data)
    {
        $owners = User::whereHas("roles", function ($q) {
            $q->where("name", "owner");
        })->get();

        $deptManagers = User::whereHas("roles", function ($q) {
            $q->where("name", "رئيس قسم");
        })->get()->filter(function ($user) use ($employees_id) {
            $emp = employees::find($employees_id);
            return $emp && in_array($emp->section_id, $user->managedSectionIds());
        });

        $recipients = $owners->merge($deptManagers);
        Notification::send($recipients, new salf_notiy($data));
    }
}
