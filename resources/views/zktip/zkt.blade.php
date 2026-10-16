@extends('layouts_new.app')

@section('title')
    الاتصال بجهاز البصمة | نظام إدارة الموظفين
@endsection

@section('css')
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <style>
        /* ===== SN Sidebar Drawer ===== */
        #sn-drawer {
            transform: translateX(100%);
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #sn-drawer.open {
            transform: translateX(0);
        }
        #sn-overlay {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.35s ease;
        }
        #sn-overlay.open {
            opacity: 1;
            pointer-events: all;
        }
        .sn-card {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .sn-card:has(input:checked) {
            border-color: #3b82f6;
            background: rgba(59,130,246,0.06);
        }
        .sn-card input[type="checkbox"] {
            accent-color: #3b82f6;
        }
        /* pulse dot */
        @keyframes pulse-dot {
            0%,100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
        }
        .pulse-dot { animation: pulse-dot 1.5s infinite; }
    </style>
@endsection

@section('page-header')
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">إدارة البصمة</span>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">اتصال بالأجهزة</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">إدارة أجهزة البصمة والاتصال</h1>
            </div>
        </div>

        {{-- زر فتح الـ Sidebar --}}
        <button onclick="openSnDrawer()" class="flex items-center gap-2 px-5 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl font-bold text-sm shadow-lg shadow-blue-500/20 transition-all active:scale-95">
            <i data-lucide="cpu" class="w-5 h-5"></i>
            سحب بالـ SN
            <span id="sn-count-badge" class="hidden bg-white text-blue-600 text-[10px] font-black px-2 py-0.5 rounded-full">0</span>
        </button>
    </div>

    @if (session()->has('warning'))
        <div class="bg-sky-100 border-l-4 border-sky-500 text-sky-700 p-4 mb-6 rounded-r-xl shadow-sm" role="alert">
            <p class="font-bold">تنبيه</p>
            <p>القسم أو الشيفت فارغ، يرجى التحقق من البيانات.</p>
        </div>
    @endif

    @if (session()->has('Status'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-xl shadow-sm" role="alert">
            <p class="font-bold">نجاح</p>
            <p>تم سحب بيانات الموظفين من البصمة بنجاح.</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-xl shadow-sm" role="alert">
            <p class="font-bold">خطأ في الاتصال</p>
            <p>لا يمكن الاتصال بجهاز البصمة، يرجى التأكد من الـ IP والشبكة.</p>
        </div>
    @endif

    @if (session()->has('nodata'))
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded-r-xl shadow-sm" role="alert">
            <p class="font-bold">بيانات ناقصة</p>
            <p>يرجى إكمال إدخال بيانات الموظفين قبل المتابعة.</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-xl shadow-sm" role="alert">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection

@section('content')
    <div class="space-y-8">
        <!-- Logs Retrieval Section -->
        <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-8 shadow-sm overflow-hidden relative group">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-sky-500/0 via-sky-500 to-sky-500/0 opacity-30"></div>
            
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 bg-sky-500/10 rounded-2xl flex items-center justify-center text-sky-600">
                    <i data-lucide="download-cloud" class="w-6 h-6"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">سحب السجلات من البصمة</h2>
                    <p class="text-sm text-gray-500 mt-1">استيراد بصمات الحضور والانصراف من الأجهزة للداخل</p>
                </div>
            </div>

            <form action="{{ route('zkt.create') }}" class="space-y-8" id="main-pull-form">
                @csrf
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between px-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-gray-500 flex items-center gap-2">
                            <i data-lucide="cpu" class="w-4 h-4 text-sky-500"></i> اختر أجهزة البصمة (بالـ IP)
                        </label>
                        <button type="button" onclick="toggleAllCheckboxes(this)" class="text-[10px] font-bold text-sky-600 dark:text-sky-400 hover:text-sky-700 transition-colors uppercase tracking-widest bg-sky-500/10 px-3 py-1.5 rounded-full">تحديد / إلغاء تحديد الكل</button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-6 bg-gray-50 dark:bg-[#111] rounded-3xl border border-gray-100 dark:border-white/5">
                        @foreach ($zktss as $zk)
                            <label class="relative flex items-center gap-3 p-4 bg-white dark:bg-[#181818] border border-gray-200 dark:border-white/10 rounded-2xl cursor-pointer hover:border-sky-500/50 transition-all group/item shadow-sm">
                                <input type="checkbox" name="ipz[]" value="{{ $zk->ip }}" class="w-5 h-5 rounded-lg border-gray-300 text-sky-500 focus:ring-sky-500 dark:bg-[#111] dark:border-white/10 ip-checkbox">
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-gray-900 dark:text-white">{{ $zk->name }}</p>
                                    <p class="text-[10px] font-mono text-gray-500 mt-0.5">{{ $zk->ip }}</p>
                                    @if($zk->sn)
                                        <p class="text-[9px] font-mono text-blue-500 mt-0.5">SN: {{ $zk->sn }}</p>
                                    @endif
                                </div>
                                <div class="zkt-status-badge flex items-center justify-center w-6 h-6 rounded-full bg-gray-50 dark:bg-white/5" data-ip="{{ $zk->ip }}">
                                    <i data-lucide="loader-2" class="w-3.5 h-3.5 text-gray-400 animate-spin"></i>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest px-2">من تاريخ</label>
                        <input type="date" name="searchs" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-5 py-3 rounded-2xl outline-none focus:border-sky-500/50 transition-colors">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest px-2">إلى تاريخ</label>
                        <input type="date" name="searche" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-5 py-3 rounded-2xl outline-none focus:border-sky-500/50 transition-colors">
                    </div>
                    <div class="lg:col-span-2 flex items-end gap-3">
                        <button type="submit" class="flex-1 bg-sky-500 text-white font-bold py-3.5 px-6 rounded-2xl shadow-lg shadow-sky-500/20 hover:bg-sky-600 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <i data-lucide="search" class="w-5 h-5"></i>
                            بدء سحب السجلات
                        </button>
                        <button type="submit" name="local" value="1" class="flex-1 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold py-3.5 px-6 rounded-2xl shadow-lg hover:opacity-90 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <i data-lucide="database" class="w-5 h-5"></i>
                            بحث محلي فقط
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-4 bg-sky-500/5 rounded-2xl border border-sky-500/10">
                    <div class="w-10 h-10 bg-sky-500/20 rounded-xl flex items-center justify-center text-sky-600">
                        <i data-lucide="send" class="w-5 h-5"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-gray-900 dark:text-white">إرسال المرتبات إلى الموظفين</p>
                        <p class="text-[11px] text-gray-500">تفعيل هذا الخيار سيقوم بإرسال إشعارات للموظفين بتفاصيل مرتباتهم فور انتهاء السحب</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="emps" value="1" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none dark:bg-[#111] rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sky-500"></div>
                    </label>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Pull Employees -->
            <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-8 shadow-sm">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 bg-blue-500/10 rounded-2xl flex items-center justify-center text-blue-600">
                        <i data-lucide="users" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">سحب الموظفين من البصمة</h2>
                        <p class="text-sm text-gray-500 mt-1">استيراد قائمة الموظفين المسجلين على الأجهزة</p>
                    </div>
                </div>

                <form action="{{ route('zkt.insert') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-60 overflow-y-auto p-4 bg-gray-50 dark:bg-[#111] rounded-2xl border border-gray-100 dark:border-white/5 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-white/10">
                        @foreach ($zktss as $zk)
                            <label class="flex items-center gap-3 p-3 bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-xl cursor-pointer hover:border-blue-500/30 transition-all">
                                <input type="checkbox" name="ipz[]" value="{{ $zk->ip }}" class="w-4 h-4 rounded border-gray-300 text-blue-500 focus:ring-blue-500 ip-checkbox">
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $zk->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <button type="submit" class="w-full bg-blue-500 text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-blue-500/20 hover:bg-blue-600 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i data-lucide="download" class="w-5 h-5"></i>
                        تأكيد السحب
                    </button>
                </form>
            </div>

            <!-- Upload Employees -->
            <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-8 shadow-sm">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 bg-green-500/10 rounded-2xl flex items-center justify-center text-green-600">
                        <i data-lucide="user-plus" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">رفع الموظفين إلى البصمة</h2>
                        <p class="text-sm text-gray-500 mt-1">تحديث قائمة الموظفين على الأجهزة من النظام</p>
                    </div>
                </div>

                <form action="{{ route('zkt.upload') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-60 overflow-y-auto p-4 bg-gray-50 dark:bg-[#111] rounded-2xl border border-gray-100 dark:border-white/5 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-white/10">
                        @foreach ($zktss as $zk)
                            <label class="flex items-center gap-3 p-3 bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-xl cursor-pointer hover:border-green-500/30 transition-all">
                                <input type="checkbox" name="ipz[]" value="{{ $zk->ip }}" class="w-4 h-4 rounded border-gray-300 text-green-500 focus:ring-green-500 ip-checkbox">
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $zk->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <button type="submit" class="w-full bg-green-500 text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-green-500/20 hover:bg-green-600 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i data-lucide="upload" class="w-5 h-5"></i>
                        تأكيد الرفع
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== SN SIDEBAR DRAWER ===== --}}
    {{-- Overlay --}}
    <div id="sn-overlay" onclick="closeSnDrawer()"
         class="fixed inset-0 z-[200] bg-black/50 backdrop-blur-sm"></div>

    {{-- Drawer Panel --}}
    <div id="sn-drawer"
         class="fixed top-0 right-0 z-[201] h-full w-full max-w-md bg-white dark:bg-[#181818] shadow-2xl border-l border-gray-100 dark:border-white/10 flex flex-col">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-[#111] shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-600/10 rounded-xl flex items-center justify-center">
                    <i data-lucide="cpu" class="w-5 h-5 text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-gray-900 dark:text-white">سحب السجلات بالـ SN</h3>
                    <p class="text-[10px] text-gray-500">اختر الأجهزة والتاريخ ثم اسحب</p>
                </div>
            </div>
            <button onclick="closeSnDrawer()" class="w-8 h-8 flex items-center justify-center rounded-xl bg-gray-100 dark:bg-white/5 text-gray-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        {{-- Scrollable Body --}}
        <form action="{{ route('zkt.create') }}" method="GET" id="sn-form" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="flex-1 overflow-y-auto px-6 py-5 space-y-6">

                {{-- Date Range --}}
                <div class="space-y-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">نطاق التاريخ</p>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 mb-1.5">من تاريخ</label>
                            <input type="date" name="searchs" id="sn-date-from" required
                                   class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2.5 rounded-xl outline-none focus:border-blue-500/50 text-xs transition-colors">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 mb-1.5">إلى تاريخ</label>
                            <input type="date" name="searche" id="sn-date-to" required
                                   class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2.5 rounded-xl outline-none focus:border-blue-500/50 text-xs transition-colors">
                        </div>
                    </div>
                    {{-- Quick Date Buttons --}}
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="setQuickDate(0,0)" class="text-[10px] font-bold px-3 py-1.5 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 transition-colors">اليوم</button>
                        <button type="button" onclick="setQuickDate(6,0)" class="text-[10px] font-bold px-3 py-1.5 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 transition-colors">آخر 7 أيام</button>
                        <button type="button" onclick="setQuickDate(29,0)" class="text-[10px] font-bold px-3 py-1.5 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 transition-colors">آخر 30 يوم</button>
                        <button type="button" onclick="setCurrentMonth()" class="text-[10px] font-bold px-3 py-1.5 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 transition-colors">الشهر الحالي</button>
                    </div>
                </div>

                {{-- Devices By SN --}}
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">أجهزة البصمة (SN)</p>
                        <button type="button" onclick="toggleAllSn()" class="text-[9px] font-bold text-blue-600 hover:text-blue-700 transition-colors uppercase tracking-widest">تحديد الكل</button>
                    </div>

                    <div class="space-y-2" id="sn-devices-list">
                        @forelse ($zktss as $zk)
                            <label class="sn-card flex items-center gap-3 p-3.5 bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 rounded-2xl hover:border-blue-400 dark:hover:border-blue-500 transition-all">
                                {{-- إذا كان الجهاز يملك SN أرسله عبر snz[]، وإذا كان IP فقط أرسله عبر ipz[] --}}
                                @if($zk->sn)
                                    <input type="checkbox" name="snz[]" value="{{ $zk->sn }}"
                                           class="w-4 h-4 rounded sn-checkbox">
                                @else
                                    <input type="checkbox" name="ipz[]" value="{{ $zk->ip }}"
                                           class="w-4 h-4 rounded sn-checkbox">
                                @endif
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-gray-900 dark:text-white truncate">{{ $zk->name }}</span>
                                        @if($zk->sn)
                                            <span class="text-[9px] bg-blue-100 dark:bg-blue-500/10 text-blue-600 px-1.5 py-0.5 rounded-md font-bold">SN</span>
                                        @else
                                            <span class="text-[9px] bg-sky-100 dark:bg-sky-500/10 text-sky-600 px-1.5 py-0.5 rounded-md font-bold">IP</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3 mt-0.5">
                                        <span class="text-[10px] text-gray-500 font-mono">{{ $zk->ip ?? '—' }}</span>
                                        @if($zk->sn)
                                            <span class="flex items-center gap-1 text-[10px] text-blue-600 dark:text-blue-400 font-mono font-bold">
                                                <i data-lucide="hash" class="w-2.5 h-2.5"></i>
                                                {{ $zk->sn }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                {{-- Status indicator --}}
                                <div class="sn-status-dot w-2.5 h-2.5 rounded-full bg-gray-300 dark:bg-gray-600 shrink-0" data-ip="{{ $zk->ip }}"></div>
                            </label>
                        @empty
                            <div class="flex flex-col items-center justify-center py-10 text-center text-gray-400">
                                <i data-lucide="inbox" class="w-10 h-10 mb-3 opacity-30"></i>
                                <p class="text-sm font-bold">لا توجد أجهزة مسجلة</p>
                                <a href="{{ url('/zkt_name') }}" class="mt-2 text-xs text-blue-500 hover:underline">إضافة جهاز</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Summary --}}
                <div class="p-4 bg-blue-50 dark:bg-blue-500/5 rounded-2xl border border-blue-100 dark:border-blue-500/10">
                    <div class="flex items-center gap-2 mb-2">
                        <i data-lucide="info" class="w-4 h-4 text-blue-500"></i>
                        <span class="text-[11px] font-bold text-blue-700 dark:text-blue-400">ملخص السحب</span>
                    </div>
                    <div class="space-y-1">
                        <div class="flex justify-between text-[11px]">
                            <span class="text-gray-500">الأجهزة المحددة</span>
                            <span id="selected-sn-count" class="font-black text-blue-600">0</span>
                        </div>
                        <div class="flex justify-between text-[11px]">
                            <span class="text-gray-500">من</span>
                            <span id="summary-from" class="font-bold text-gray-700 dark:text-gray-300">—</span>
                        </div>
                        <div class="flex justify-between text-[11px]">
                            <span class="text-gray-500">إلى</span>
                            <span id="summary-to" class="font-bold text-gray-700 dark:text-gray-300">—</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer Buttons --}}
            <div class="shrink-0 px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-[#111] space-y-3">
                <button type="submit" id="sn-pull-btn"
                        class="w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-blue-600 hover:bg-blue-500 text-white font-black text-sm rounded-2xl shadow-lg shadow-blue-500/20 transition-all active:scale-95">
                    <i data-lucide="download-cloud" class="w-5 h-5"></i>
                    سحب السجلات الآن
                </button>
                <div class="grid grid-cols-2 gap-2">
                    <button type="submit" name="local" value="1"
                            class="flex items-center justify-center gap-1.5 px-4 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 font-bold text-xs rounded-xl transition-all">
                        <i data-lucide="database" class="w-4 h-4"></i>
                        بحث محلي فقط
                    </button>
                    <button type="button" onclick="closeSnDrawer()"
                            class="flex items-center justify-center gap-1.5 px-4 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 font-bold text-xs rounded-xl transition-all">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        إلغاء
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script>
        // ===== Main Form: Toggle All IP Checkboxes =====
        function toggleAllCheckboxes(btn) {
            const container = btn.closest('form').querySelector('.grid');
            const checkboxes = container.querySelectorAll('.ip-checkbox');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
            btn.innerText = allChecked ? 'تحديد الكل' : 'إلغاء تحديد الكل';
        }

        // ===== SN Drawer =====
        function openSnDrawer() {
            document.getElementById('sn-drawer').classList.add('open');
            document.getElementById('sn-overlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeSnDrawer() {
            document.getElementById('sn-drawer').classList.remove('open');
            document.getElementById('sn-overlay').classList.remove('open');
            document.body.style.overflow = '';
        }

        // Toggle All SN Checkboxes
        function toggleAllSn() {
            const checkboxes = document.querySelectorAll('.sn-checkbox:not(:disabled)');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
            updateSnSummary();
        }

        // Quick Date Shortcuts
        function setQuickDate(daysBack, daysForward) {
            const today = new Date();
            const from = new Date();
            from.setDate(today.getDate() - daysBack);
            const to = new Date();
            to.setDate(today.getDate() + daysForward);
            document.getElementById('sn-date-from').value = formatDate(from);
            document.getElementById('sn-date-to').value   = formatDate(to);
            updateSnSummary();
        }

        function setCurrentMonth() {
            const today = new Date();
            const first = new Date(today.getFullYear(), today.getMonth(), 1);
            const last  = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            document.getElementById('sn-date-from').value = formatDate(first);
            document.getElementById('sn-date-to').value   = formatDate(last);
            updateSnSummary();
        }

        function formatDate(d) {
            return d.toISOString().split('T')[0];
        }

        // Update Summary Panel
        function updateSnSummary() {
            const checked  = document.querySelectorAll('.sn-checkbox:checked').length;
            const from     = document.getElementById('sn-date-from').value;
            const to       = document.getElementById('sn-date-to').value;

            document.getElementById('selected-sn-count').textContent = checked;
            document.getElementById('summary-from').textContent = from || '—';
            document.getElementById('summary-to').textContent   = to  || '—';

            // Update badge on header button
            const badge = document.getElementById('sn-count-badge');
            if (checked > 0) {
                badge.textContent = checked;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }

        // Listen to SN checkbox changes
        document.querySelectorAll('.sn-checkbox').forEach(cb => {
            cb.addEventListener('change', updateSnSummary);
        });

        // Listen to date changes in drawer
        ['sn-date-from','sn-date-to'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('change', updateSnSummary);
        });

        // ===== Device Status Check =====
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof lucide !== 'undefined') lucide.createIcons();

            // Set today as default date for drawer
            const today = formatDate(new Date());
            if (document.getElementById('sn-date-from')) {
                document.getElementById('sn-date-from').value = today;
                document.getElementById('sn-date-to').value   = today;
                updateSnSummary();
            }

            // Collect unique IPs
            let uniqueIps = [];
            document.querySelectorAll('.zkt-status-badge').forEach(badge => {
                let ip = badge.getAttribute('data-ip');
                if (ip && !uniqueIps.includes(ip)) uniqueIps.push(ip);
            });

            // Build the base check status URL using route helper
            const checkStatusBaseUrl = "{{ route('check_zkt_status', ['ip' => '___IP___']) }}";

            // Check each IP status
            uniqueIps.forEach(ip => {
                const url = checkStatusBaseUrl.replace('___IP___', encodeURIComponent(ip));
                fetch(url)
                    .then(r => {
                        if (!r.ok) throw new Error('HTTP ' + r.status);
                        return r.json();
                    })
                    .then(data => {
                        // Main table badges
                        document.querySelectorAll(`.zkt-status-badge[data-ip="${ip}"]`).forEach(badge => {
                            badge.innerHTML = data.status === 'online'
                                ? '<i data-lucide="check-circle" class="w-4 h-4 text-green-500" title="متصل"></i>'
                                : '<i data-lucide="x-circle" class="w-4 h-4 text-red-500" title="غير متصل"></i>';
                        });

                        // Sidebar SN dots
                        document.querySelectorAll(`.sn-status-dot[data-ip="${ip}"]`).forEach(dot => {
                            dot.classList.remove('bg-gray-300','dark:bg-gray-600','pulse-dot');
                            if (data.status === 'online') {
                                dot.classList.add('bg-green-500');
                                dot.title = 'متصل';
                            } else {
                                dot.classList.add('bg-red-400');
                                dot.title = 'غير متصل';
                            }
                        });

                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    })
                    .catch(() => {
                        document.querySelectorAll(`.zkt-status-badge[data-ip="${ip}"]`).forEach(badge => {
                            badge.innerHTML = '<i data-lucide="alert-triangle" class="w-4 h-4 text-red-500" title="خطأ"></i>';
                        });
                        document.querySelectorAll(`.sn-status-dot[data-ip="${ip}"]`).forEach(dot => {
                            dot.classList.add('bg-red-400');
                        });
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    });
            });
        });
    </script>
@endsection
