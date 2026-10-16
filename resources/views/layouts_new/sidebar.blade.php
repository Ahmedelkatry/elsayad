<style>
    #sidebar nav {
        padding-top: 0.75rem !important;
        padding-bottom: 0.75rem !important;
    }
    #sidebar nav a, #sidebar nav summary {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }
    #sidebar nav details div a {
        padding-top: 0.35rem !important;
        padding-bottom: 0.35rem !important;
    }
    #sidebar nav .pt-3 {
        padding-top: 0.5rem !important;
    }
</style>
<aside id="sidebar"
    class="fixed right-0 top-0 h-full w-64 glass-sidebar z-50 flex flex-col transform translate-x-full lg:translate-x-0 transition-transform shadow-2xl lg:shadow-none overflow-hidden">

    <div class="flex flex-col items-center py-6 border-b border-gray-200 dark:border-white/5 relative shrink-0">
        <div class="text-center relative z-7">
            <h2 class="text-lg font-black tracking-tighter text-gray-900 dark:text-white leading-none">
                Valcore <span class="text-sky-500">Systems</span>
            </h2>
        </div>
        <button id="close-mobile-menu" type="button"
            onclick="document.getElementById('sidebar').classList.remove('mobile-show'); document.getElementById('mobile-overlay').classList.add('hidden');"
            class="lg:hidden absolute left-4 top-4 text-gray-500 hover:text-red-500 dark:hover:text-red-400 transition-all z-[100] p-3 bg-gray-100 dark:bg-white/5 rounded-full border border-gray-200 dark:border-white/10 shadow-lg">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
    </div>

    <nav class="flex-1 px-4 py-8 space-y-1 overflow-y-auto text-sm custom-scrollbar">

        <a href="{{ route('dashboard') }}"
            class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('dashboard') ? 'active-link font-bold' : 'text-gray-500 hover:text-sky-500 hover:bg-sky-500/5' }}">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
            <span>الرئيسية</span>
        </a>

        @if (Auth::user()->roles_name !== ['emp'])

            <!-- 1. الهيكل التنظيمي -->
            <div class="pt-4 pb-1 px-5 text-[10px] font-bold text-gray-400 dark:text-gray-600 uppercase tracking-widest flex items-center gap-2">
                <span class="w-4 h-[1px] bg-gray-700"></span> الهيكل التنظيمي
            </div>

            @can('الاقسام')
                <a href="{{ url('/sections') }}"
                    class="flex items-center justify-between gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->is('sections*') ? 'active-link font-bold' : 'text-gray-500 hover:text-sky-500 hover:bg-sky-500/5' }}">
                    <div class="flex items-center gap-4">
                        <i data-lucide="layers" class="w-5 h-5"></i>
                        <span>الأقسام</span>
                    </div>
                </a>
            @endcan

            @can('الموظفين')
                <a href="{{ url('/employees') }}"
                    class="flex items-center justify-between gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->is('employees*') ? 'active-link font-bold' : 'text-gray-500 hover:text-sky-500 hover:bg-sky-500/5' }}">
                    <div class="flex items-center gap-4">
                        <i data-lucide="users" class="w-5 h-5"></i>
                        <span>الموظفين</span>
                    </div>
                </a>
            @endcan


            <!-- 2. الدوام والورديات -->
            <div class="pt-4 pb-1 px-5 text-[10px] font-bold text-gray-400 dark:text-gray-600 uppercase tracking-widest flex items-center gap-2">
                <span class="w-4 h-[1px] bg-gray-700"></span> الدوام والورديات
            </div>

            @can('شيفت')
                <details class="group overflow-hidden transition-all duration-300"
                    {{ request()->is('shifts*') || request()->is('sup_shifts*') || request()->is('shift_w*') || request()->is('break*') ? 'open' : '' }}>
                    <summary
                        class="flex items-center gap-4 px-5 py-3 rounded-2xl cursor-pointer list-none outline-none select-none transition-all {{ request()->is('shifts*') || request()->is('sup_shifts*') || request()->is('shift_w*') || request()->is('break*') ? 'text-sky-600 dark:text-sky-500 bg-sky-500/10' : 'text-gray-500 hover:text-sky-500 hover:bg-sky-500/5' }}">
                        <i data-lucide="clock" class="w-5 h-5"></i>
                        <span class="font-bold text-sm">الشيفتات والورديات</span>
                        <i data-lucide="chevron-down"
                            class="w-4 h-4 mr-auto transition-transform duration-300 group-open:rotate-180"></i>
                    </summary>
                    <div class="bg-black/5 dark:bg-white/5 rounded-xl mt-1 py-2 space-y-1">
                        <a href="{{ url('/shifts') }}"
                            class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('shifts') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">
                            <div class="flex items-center gap-4">
                                <span class="w-1.5 h-1.5 rounded-full {{ request()->is('shifts') ? 'bg-sky-500' : 'bg-current' }}"></span>
                                شيفت
                            </div>
                        </a>
                        <a href="{{ url('/sup_shifts') }}"
                            class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('sup_shifts') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">
                            <div class="flex items-center gap-4">
                                <span class="w-1.5 h-1.5 rounded-full {{ request()->is('sup_shifts') ? 'bg-sky-500' : 'bg-current' }}"></span>
                                تعديلات الشفتات
                            </div>
                            <span class="opacity-50">{{ App\Models\SupShift::count() }}</span>
                        </a>
                        <a href="{{ route('shift_w.edit') }}"
                            class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('shift_w*') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">
                            <div class="flex items-center gap-4">
                                <span class="w-1.5 h-1.5 rounded-full {{ request()->is('shift_w*') ? 'bg-sky-500' : 'bg-current' }}"></span>
                                تبديل الشيفتات بالايام
                            </div>
                            <span class="opacity-50">{{ App\Models\shift_week::count() }}</span>
                        </a>
                        <a href="{{ url('/break') }}"
                            class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('break') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">
                            <div class="flex items-center gap-4">
                                <span class="w-1.5 h-1.5 rounded-full {{ request()->is('break') ? 'bg-sky-500' : 'bg-current' }}"></span>
                                استراحه
                            </div>
                        </a>
                    </div>
                </details>
            @endcan

            @can('تبديل شيفت')
                <details class="group overflow-hidden transition-all duration-300"
                    {{ request()->is('ch_shift_sections*') || request()->is('chshift*') ? 'open' : '' }}>
                    <summary
                        class="flex items-center gap-4 px-5 py-3 rounded-2xl cursor-pointer list-none outline-none select-none transition-all {{ request()->is('ch_shift_sections*') || request()->is('chshift*') ? 'text-sky-600 dark:text-sky-500 bg-sky-500/10' : 'text-gray-500 hover:text-sky-500 hover:bg-sky-500/5' }}">
                        <i data-lucide="calendar-range" class="w-5 h-5"></i>
                        <span class="font-bold text-sm">تبديل شيفتات الموظفين</span>
                        <i data-lucide="chevron-down"
                            class="w-4 h-4 mr-auto transition-transform duration-300 group-open:rotate-180"></i>
                    </summary>
                    <div class="bg-black/5 dark:bg-white/5 rounded-xl mt-1 py-2 space-y-1">
                        @can('تبديل شيفت قسم')
                            <a href="{{ url('/ch_shift_sections') }}"
                                class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('ch_shift_sections') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">
                                <div class="flex items-center gap-4">
                                    <span class="w-1.5 h-1.5 rounded-full {{ request()->is('ch_shift_sections') ? 'bg-sky-500' : 'bg-current' }}"></span>
                                    تبديل شيفت للقسم
                                </div>
                                <span class="opacity-50">{{ App\Models\head_sections::count() }}</span>
                            </a>
                        @endcan
                        @can('تبديل شيفت موظف')
                            <a href="{{ url('/chshift') }}"
                                class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('chshift') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">
                                <div class="flex items-center gap-4">
                                    <span class="w-1.5 h-1.5 rounded-full {{ request()->is('chshift') ? 'bg-sky-500' : 'bg-current' }}"></span>
                                    تبديل شيفت موظف
                                </div>
                                <span class="opacity-50">{{ App\Models\change_shift::count() }}</span>
                            </a>
                        @endcan
                    </div>
                </details>
            @endcan


            <!-- 3. شؤون الموظفين والعمليات -->
            <div class="pt-4 pb-1 px-5 text-[10px] font-bold text-gray-400 dark:text-gray-600 uppercase tracking-widest flex items-center gap-2">
                <span class="w-4 h-[1px] bg-gray-700"></span> شؤون الموظفين والعمليات
            </div>

            @can('شؤن الموظفين')
                <!-- إجراءات جماعية للموظفين (High Level Standalone) -->
                <a href="{{ route('bulk_action.index') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('bulk_action.index') ? 'active-link font-bold' : 'text-gray-500 hover:text-sky-500 hover:bg-sky-500/5' }}">
                    <i data-lucide="users-2" class="w-5 h-5"></i>
                    <span>إجراءات جماعية للموظفين</span>
                </a>

                <!-- العمليات المالية -->
                @php
                    $isFinancial =
                        request()->is('salf*') ||
                        request()->is('loans*') ||
                        request()->is('disemp*') ||
                        request()->is('hafz*') ||
                        request()->is('dis_transport*') ||
                        request()->is('badel_transport*') ||
                        request()->is('taminat*') ||
                        request()->is('purchases*') ||
                        request()->is('eids*') ||
                        request()->is('hafzeids*');
                @endphp
                <details class="group overflow-hidden transition-all duration-300" {{ $isFinancial ? 'open' : '' }}>
                    <summary
                        class="flex items-center gap-4 px-5 py-3 rounded-2xl cursor-pointer list-none outline-none select-none transition-all {{ $isFinancial ? 'text-sky-600 dark:text-sky-500 bg-sky-500/10' : 'text-gray-500 hover:text-sky-500 hover:bg-sky-500/5' }}">
                        <i data-lucide="wallet" class="w-5 h-5"></i>
                        <span class="font-bold text-sm">الماليات والرواتب</span>
                        <i data-lucide="chevron-down"
                            class="w-4 h-4 mr-auto transition-transform duration-300 group-open:rotate-180"></i>
                    </summary>
                    <div class="bg-black/5 dark:bg-white/5 rounded-xl mt-1 py-2 space-y-1">
                        @can('سلف')
                            <a href="{{ url('/salf') }}"
                                class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('salf*') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">
                                <div class="flex items-center gap-4">
                                    <span class="w-1.5 h-1.5 rounded-full {{ request()->is('salf*') ? 'bg-sky-500' : 'bg-current' }}"></span>
                                    طلب سلفة
                                </div>
                            </a>
                        @endcan
                        @can('قرض')
                            <a href="{{ url('/loans') }}"
                                class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('loans*') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">
                                <div class="flex items-center gap-4">
                                    <span class="w-1.5 h-1.5 rounded-full {{ request()->is('loans*') ? 'bg-sky-500' : 'bg-current' }}"></span>
                                    قروض الموظفين
                                </div>
                                <span class="opacity-50">{{ App\Models\loan::count() }}</span>
                            </a>
                        @endcan
                        @can('الخصم')
                            <a href="{{ url('/disemp') }}"
                                class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('disemp*') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">
                                <div class="flex items-center gap-4">
                                    <span class="w-1.5 h-1.5 rounded-full {{ request()->is('disemp*') ? 'bg-sky-500' : 'bg-current' }}"></span>
                                    خصم خارج البصمة
                                </div>
                            </a>
                        @endcan
                        @can('حافز')
                            <a href="{{ url('/hafz') }}"
                                class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('hafz*') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">
                                <div class="flex items-center gap-4">
                                    <span class="w-1.5 h-1.5 rounded-full {{ request()->is('hafz*') ? 'bg-sky-500' : 'bg-current' }}"></span>
                                    حافز / مكافأة
                                </div>
                            </a>
                        @endcan
                        @can('بدل مواصلات')
                            <a href="{{ url('/badel_transport') }}"
                                class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('badel_transport*') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">
                                <div class="flex items-center gap-4">
                                    <span class="w-1.5 h-1.5 rounded-full {{ request()->is('badel_transport*') ? 'bg-sky-500' : 'bg-current' }}"></span>
                                    بدل مواصلات
                                </div>
                                <span class="opacity-50">{{ App\Models\badel_transport::count() }}</span>
                            </a>
                        @endcan
                        @can('خصم مواصلات')
                            <a href="{{ url('/dis_transport') }}"
                                class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('dis_transport*') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">
                                <div class="flex items-center gap-4">
                                    <span class="w-1.5 h-1.5 rounded-full {{ request()->is('dis_transport*') ? 'bg-sky-500' : 'bg-current' }}"></span>
                                    خصم مواصلات
                                </div>
                                <span class="opacity-50">{{ App\Models\dis_transport::count() }}</span>
                            </a>
                        @endcan
                        @can('التامينات')
                            <a href="{{ url('/taminat') }}"
                                class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('taminat*') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">
                                <div class="flex items-center gap-4">
                                    <span class="w-1.5 h-1.5 rounded-full {{ request()->is('taminat*') ? 'bg-sky-500' : 'bg-current' }}"></span>
                                    التأمينات
                                </div>
                                <span class="opacity-50">{{ App\Models\taminat::count() }}</span>
                            </a>
                        @endcan
                        @can('المشتريات')
                            <a href="{{ url('/purchases') }}"
                                class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('purchases*') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">
                                <div class="flex items-center gap-4">
                                    <span class="w-1.5 h-1.5 rounded-full {{ request()->is('purchases*') ? 'bg-sky-500' : 'bg-current' }}"></span>
                                    المشتريات
                                </div>
                                <span class="opacity-50">{{ App\Models\purchases::count() }}</span>
                            </a>
                        @endcan
                        @can('عديه العيد')
                            <a href="{{ url('/hafzeids') }}"
                                class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('hafzeids*') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">
                                <div class="flex items-center gap-4">
                                    <span class="w-1.5 h-1.5 rounded-full {{ request()->is('hafzeids*') ? 'bg-sky-500' : 'bg-current' }}"></span>
                                    عيدية العيد
                                </div>
                                <span class="opacity-50">{{ App\Models\hafzeid::count() }}</span>
                            </a>
                        @endcan
                        @can('ايام العيد')
                            <a href="{{ url('/eids') }}"
                                class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('eids*') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">
                                <div class="flex items-center gap-4">
                                    <span class="w-1.5 h-1.5 rounded-full {{ request()->is('eids*') ? 'bg-sky-500' : 'bg-current' }}"></span>
                                    تحديد العيد
                                </div>
                                <span class="opacity-50">{{ App\Models\eid::count() }}</span>
                            </a>
                        @endcan
                    </div>
                </details>

                <!-- الإجازات والأذونات -->
                @php
                    $isLeaves =
                        request()->is('ple*') ||
                        request()->is('vacations*') ||
                        request()->is('forget_finger*') ||
                        request()->is('plzot*');
                @endphp
                <details class="group overflow-hidden transition-all duration-300" {{ $isLeaves ? 'open' : '' }}>
                    <summary
                        class="flex items-center gap-4 px-5 py-3 rounded-2xl cursor-pointer list-none outline-none select-none transition-all {{ $isLeaves ? 'text-sky-600 dark:text-sky-500 bg-sky-500/10' : 'text-gray-500 hover:text-sky-500 hover:bg-sky-500/5' }}">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                        <span class="font-bold text-sm">الإجازات والطلبات</span>
                        <i data-lucide="chevron-down"
                            class="w-4 h-4 mr-auto transition-transform duration-300 group-open:rotate-180"></i>
                    </summary>
                    <div class="bg-black/5 dark:bg-white/5 rounded-xl mt-1 py-2 space-y-1">
                        @can('طلب اجازه')
                            <a href="{{ url('/vacations') }}"
                                class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('vacations*') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">
                                <div class="flex items-center gap-4">
                                    <span class="w-1.5 h-1.5 rounded-full {{ request()->is('vacations*') ? 'bg-sky-500' : 'bg-current' }}"></span>
                                    طلب إجازة
                                </div>
                                <span class="opacity-50">{{ App\Models\vacation::count() }}</span>
                            </a>
                        @endcan
                        @can('اذن')
                            <a href="{{ url('/ple') }}"
                                class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('ple*') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">
                                <div class="flex items-center gap-4">
                                    <span class="w-1.5 h-1.5 rounded-full {{ request()->is('ple*') ? 'bg-sky-500' : 'bg-current' }}"></span>
                                    طلب إذن
                                </div>
                                <span class="opacity-50">{{ App\Models\please::count() }}</span>
                            </a>
                        @endcan
                        @can('اداره بصمات الموظفين')
                            <a href="{{ url('/forget_finger') }}"
                                class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('forget_finger*') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">
                                <div class="flex items-center gap-4">
                                    <span class="w-1.5 h-1.5 rounded-full {{ request()->is('forget_finger*') ? 'bg-sky-500' : 'bg-current' }}"></span>
                                    إدارة بصمات الموظفين
                                </div>
                                <span class="opacity-50">{{ App\Models\forget_finger::count() }}</span>
                            </a>
                        @endcan
                        @can('اداره الوقت الاضافي')
                            <a href="{{ url('/plzot') }}"
                                class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('plzot*') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">
                                <div class="flex items-center gap-4">
                                    <span class="w-1.5 h-1.5 rounded-full {{ request()->is('plzot*') ? 'bg-sky-500' : 'bg-current' }}"></span>
                                    إدارة الوقت الإضافي
                                </div>
                                <span class="opacity-50">{{ App\Models\plzot::count() }}</span>
                            </a>
                        @endcan
                    </div>
                </details>
            @endcan


            <!-- 4. الموافقات وطلبات الإيميل -->
            @if (Auth::user()->roles_name == ['owner'] || Auth::user()->hasRole('رئيس قسم'))
                @can('طلبات الموظفين ايميل')
                    @php
                        $isTlb =
                            request()->is('show_free*') ||
                            request()->is('show_salf*') ||
                            request()->is('show_plz*') ||
                            request()->is('show_shift*');
                        $allPending = App\Models\tlbplease::where('status', 3)->count();
                    @endphp
                    <!--<div class="pt-4 pb-1 px-5 text-[10px] font-bold text-gray-400 dark:text-gray-600 uppercase tracking-widest flex items-center gap-2">-->
                    <!--    <span class="w-4 h-[1px] bg-gray-700"></span> الموافقات والطلبات-->
                    <!--</div>-->
                    <!--<details class="group overflow-hidden transition-all duration-300" {{ $isTlb ? 'open' : '' }}>-->
                    <!--    <summary-->
                    <!--        class="flex items-center gap-4 px-5 py-3 rounded-2xl cursor-pointer list-none outline-none select-none transition-all {{ $isTlb ? 'text-sky-600 dark:text-sky-500 bg-sky-500/10' : 'text-gray-500 hover:text-sky-500 hover:bg-sky-500/5' }}">-->
                    <!--        <i data-lucide="mail-check" class="w-5 h-5"></i>-->
                    <!--        <span class="font-bold text-sm">طلبات الإيميل المعلقة</span>-->
                    <!--        @if ($allPending > 0)-->
                    <!--            <span class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold ml-2">{{ $allPending }}</span>-->
                    <!--        @endif-->
                    <!--        <i data-lucide="chevron-down"-->
                    <!--            class="w-4 h-4 mr-auto transition-transform duration-300 group-open:rotate-180"></i>-->
                    <!--    </summary>-->
                    <!--    <div class="bg-black/5 dark:bg-white/5 rounded-xl mt-1 py-2 space-y-1">-->
                    <!--        <a href="{{ url('/show_free') }}"-->
                    <!--            class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('show_free*') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">-->
                    <!--            <div class="flex items-center gap-4">-->
                    <!--                <span class="w-1.5 h-1.5 rounded-full {{ request()->is('show_free*') ? 'bg-sky-500' : 'bg-current' }}"></span>-->
                    <!--                إجازات-->
                    <!--            </div>-->
                    <!--            @php $countFree = App\Models\tlbplease::where('status', 3)->whereNotNull('date')->count(); @endphp-->
                    <!--            @if ($countFree > 0)-->
                    <!--                <span class="text-[10px] bg-sky-500/20 text-sky-600 px-2 rounded-full font-bold">{{ $countFree }}</span>-->
                    <!--            @endif-->
                    <!--        </a>-->
                    <!--        <a href="{{ url('/show_salf') }}"-->
                    <!--            class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('show_salf*') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">-->
                    <!--            <div class="flex items-center gap-4">-->
                    <!--                <span class="w-1.5 h-1.5 rounded-full {{ request()->is('show_salf*') ? 'bg-sky-500' : 'bg-current' }}"></span>-->
                    <!--                سلف-->
                    <!--            </div>-->
                    <!--            @php $countSalf = App\Models\tlbplease::where('status', 3)->whereNotNull('datesalf')->count(); @endphp-->
                    <!--            @if ($countSalf > 0)-->
                    <!--                <span class="text-[10px] bg-sky-500/20 text-sky-600 px-2 rounded-full font-bold">{{ $countSalf }}</span>-->
                    <!--            @endif-->
                    <!--        </a>-->
                    <!--        <a href="{{ url('/show_plz') }}"-->
                    <!--            class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('show_plz*') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">-->
                    <!--            <div class="flex items-center gap-4">-->
                    <!--                <span class="w-1.5 h-1.5 rounded-full {{ request()->is('show_plz*') ? 'bg-sky-500' : 'bg-current' }}"></span>-->
                    <!--                استئذان-->
                    <!--            </div>-->
                    <!--            @php $countPlz = App\Models\tlbplease::where('status', 3)->whereNotNull('dateplz')->count(); @endphp-->
                    <!--            @if ($countPlz > 0)-->
                    <!--                <span class="text-[10px] bg-sky-500/20 text-sky-600 px-2 rounded-full font-bold">{{ $countPlz }}</span>-->
                    <!--            @endif-->
                    <!--        </a>-->
                    <!--        <a href="{{ url('/show_shift') }}"-->
                    <!--            class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('show_shift*') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">-->
                    <!--            <div class="flex items-center gap-4">-->
                    <!--                <span class="w-1.5 h-1.5 rounded-full {{ request()->is('show_shift*') ? 'bg-sky-500' : 'bg-current' }}"></span>-->
                    <!--                تبديل شيفت-->
                    <!--            </div>-->
                    <!--            @php $countShift = App\Models\tlbplease::where('status', 3)->whereNotNull('dateshift')->count(); @endphp-->
                    <!--            @if ($countShift > 0)-->
                    <!--                <span class="text-[10px] bg-sky-500/20 text-sky-600 px-2 rounded-full font-bold">{{ $countShift }}</span>-->
                    <!--            @endif-->
                    <!--        </a>-->
                    <!--    </div>-->
                    <!--</details>-->
                @endcan
            @endif


            <!-- 5. الأجهزة والربط -->
            <div class="pt-4 pb-1 px-5 text-[10px] font-bold text-gray-400 dark:text-gray-600 uppercase tracking-widest flex items-center gap-2">
                <span class="w-4 h-[1px] bg-gray-700"></span> الأجهزة والاتصال
            </div>

            <!--<a href="{{ url('/zkt_name') }}"-->
            <!--    class="flex items-center justify-between gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->is('zkt_name*') ? 'active-link font-bold' : 'text-gray-500 hover:text-sky-500 hover:bg-sky-500/5' }}">-->
            <!--    <div class="flex items-center gap-4">-->
            <!--        <i data-lucide="hard-drive" class="w-5 h-5"></i>-->
            <!--        <span>أجهزة البصمة</span>-->
            <!--    </div>-->
            <!--    <span class="bg-sky-500/20 text-sky-600 dark:text-sky-500 text-[10px] px-2 py-0.5 rounded-full font-bold">{{ App\Models\ipzkt::count() }}</span>-->
            <!--</a>-->

            @can('IP-الاتصال')
                <a href="{{ url('/zkt') }}"
                    class="flex items-center justify-between gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->is('zkt*') ? 'active-link font-bold' : 'text-gray-500 hover:text-sky-500 hover:bg-sky-500/5' }}">
                    <div class="flex items-center gap-4">
                        <i data-lucide="network" class="w-5 h-5"></i>
                        <span>سجلات الحضور (IP)</span>
                    </div>
                    <span class="bg-sky-500/20 text-sky-600 dark:text-sky-500 text-[10px] px-2 py-0.5 rounded-full font-bold">{{ App\Models\zkt::count() }}</span>
                </a>
            @endcan

            <!--<a href="{{ route('check_attendance_location') }}"-->
            <!--    class="flex items-center justify-between gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('check_attendance_location') || request()->routeIs('employee.location_history') ? 'active-link font-bold' : 'text-gray-500 hover:text-sky-500 hover:bg-sky-500/5' }}">-->
            <!--    <div class="flex items-center gap-4">-->
            <!--        <i data-lucide="map-pin" class="w-5 h-5"></i>-->
            <!--        <span>تتبع المواقع (GPS)</span>-->
            <!--    </div>-->
            <!--</a>-->

            <!--<a href="{{ route('employee.locations_report') }}"-->
            <!--    class="flex items-center justify-between gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('employee.locations_report') ? 'active-link font-bold' : 'text-gray-500 hover:text-sky-500 hover:bg-sky-500/5' }}">-->
            <!--    <div class="flex items-center gap-4">-->
            <!--        <i data-lucide="file-spreadsheet" class="w-5 h-5"></i>-->
            <!--        <span>تقرير سجل المواقع</span>-->
            <!--    </div>-->
            <!--</a>-->

            {{-- @can('سحب السجلات الهواتف')
                <a href="{{ url('/nozkt') }}"
                    class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->is('nozkt*') ? 'active-link font-bold' : 'text-gray-500 hover:text-sky-500 hover:bg-sky-500/5' }}">
                    <i data-lucide="smartphone" class="w-5 h-5"></i>
                    <span>سجلات حضور الهواتف</span>
                </a>
            @endcan --}}


            <!-- 6. التقارير والإحصائيات -->
            @can('التقرير')
                <div class="pt-4 pb-1 px-5 text-[10px] font-bold text-gray-400 dark:text-gray-600 uppercase tracking-widest flex items-center gap-2">
                    <span class="w-4 h-[1px] bg-gray-700"></span> التقارير والإحصائيات
                </div>

                <details class="group overflow-hidden transition-all duration-300"
                    {{ request()->is('counts*') || request()->is('countr*') || request()->is('ganeral*') ? 'open' : '' }}>
                    <summary
                        class="flex items-center gap-4 px-5 py-3 rounded-2xl cursor-pointer list-none outline-none select-none transition-all {{ request()->is('counts*') || request()->is('countr*') || request()->is('ganeral*') ? 'text-sky-600 dark:text-sky-500 bg-sky-500/10' : 'text-gray-500 hover:text-sky-500 hover:bg-sky-500/5' }}">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                        <span class="font-bold text-sm">مركز التقارير</span>
                        <i data-lucide="chevron-down"
                            class="w-4 h-4 mr-auto transition-transform duration-300 group-open:rotate-180"></i>
                    </summary>
                    <div class="bg-black/5 dark:bg-white/5 rounded-xl mt-1 py-2 space-y-1">
                        @can('تقرير موظف')
                            <a href="{{ url('/counts') }}"
                                class="flex items-center gap-4 px-10 py-2 text-xs transition-colors {{ request()->is('counts') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ request()->is('counts') ? 'bg-sky-500' : 'bg-current' }}"></span>
                                تقرير الموظف الفردي
                            </a>
                        @endcan
                        @can('تقرير بالقسم')
                            <a href="{{ url('/countr') }}"
                                class="flex items-center gap-4 px-10 py-2 text-xs transition-colors {{ request()->is('countr') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ request()->is('countr') ? 'bg-sky-500' : 'bg-current' }}"></span>
                                تقرير القسم
                            </a>
                        @endcan
                        @can('تقرير اجمالي')
                            <a href="{{ url('/ganeral') }}"
                                class="flex items-center gap-4 px-10 py-2 text-xs transition-colors {{ request()->is('ganeral') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ request()->is('ganeral') ? 'bg-sky-500' : 'bg-current' }}"></span>
                                تقرير إجمالي عام
                            </a>
                        @endcan
                    </div>
                </details>
            @endcan


            <!-- 7. إعدادات النظام -->
            <!--@can('اعدادات المستخدين')-->
            <!--    <div class="pt-4 pb-1 px-5 text-[10px] font-bold text-gray-400 dark:text-gray-600 uppercase tracking-widest flex items-center gap-2">-->
            <!--        <span class="w-4 h-[1px] bg-gray-700"></span> إعدادات النظام-->
            <!--    </div>-->

            <!--    <details class="group overflow-hidden transition-all duration-300"-->
            <!--        {{ request()->is('users*') || request()->is('roles*') ? 'open' : '' }}>-->
            <!--        <summary-->
            <!--            class="flex items-center gap-4 px-5 py-3 rounded-2xl cursor-pointer list-none outline-none select-none transition-all {{ request()->is('users*') || request()->is('roles*') ? 'text-sky-600 dark:text-sky-500 bg-sky-500/10' : 'text-gray-500 hover:text-sky-500 hover:bg-sky-500/5' }}">-->
            <!--            <i data-lucide="settings" class="w-5 h-5"></i>-->
            <!--            <span class="font-bold text-sm">تهيئة وإعداد النظام</span>-->
            <!--            <i data-lucide="chevron-down"-->
            <!--                class="w-4 h-4 mr-auto transition-transform duration-300 group-open:rotate-180"></i>-->
            <!--        </summary>-->
            <!--        <div class="bg-black/5 dark:bg-white/5 rounded-xl mt-1 py-2 space-y-1">-->
            <!--            @can('المستخدمين')-->
            <!--                <a href="{{ url('/users') }}"-->
            <!--                    class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('users*') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">-->
            <!--                    <div class="flex items-center gap-4">-->
            <!--                        <span class="w-1.5 h-1.5 rounded-full {{ request()->is('users*') ? 'bg-sky-500' : 'bg-current' }}"></span>-->
            <!--                        المستخدمين-->
            <!--                    </div>-->
            <!--                    <span class="opacity-50">{{ App\Models\User::count() }}</span>-->
            <!--                </a>-->
            <!--            @endcan-->
            <!--            @can('الصلاحيات')-->
            <!--                <a href="{{ url('/roles') }}"-->
            <!--                    class="flex items-center justify-between px-10 py-2 text-xs transition-colors {{ request()->is('roles*') ? 'text-sky-600 dark:text-sky-500 font-bold' : 'text-gray-500 hover:text-sky-500' }}">-->
            <!--                    <div class="flex items-center gap-4">-->
            <!--                        <span class="w-1.5 h-1.5 rounded-full {{ request()->is('roles*') ? 'bg-sky-500' : 'bg-current' }}"></span>-->
            <!--                        الصلاحيات والأدوار-->
            <!--                    </div>-->
            <!--                </a>-->
            <!--            @endcan-->
            <!--            <a href="#" id="ai-settings-link" onclick="toggleAiSettingsSidebar(); return false;"-->
            <!--                class="flex items-center justify-between px-10 py-2 text-xs transition-colors text-gray-500 hover:text-sky-500">-->
            <!--                <div class="flex items-center gap-4">-->
            <!--                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>-->
            <!--                    الذكاء الاصطناعي-->
            <!--                </div>-->
            <!--                <i id="ai-settings-chevron" data-lucide="chevron-down" class="w-3 h-3 transition-transform duration-300"></i>-->
            <!--            </a>-->
                        <!-- AI Settings Panel inside sidebar -->
            <!--            <div id="ai-settings-panel-sidebar" class="hidden px-6 py-3 space-y-3 bg-black/5 dark:bg-white/5 rounded-xl mx-2 mb-2 text-xs">-->
            <!--                <div>-->
            <!--                    <label class="block mb-1 font-bold">المحرك</label>-->
            <!--                    <select id="ai-provider" onchange="toggleProviderFieldsSidebar()" class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-1.5 outline-none focus:ring-2 focus:ring-blue-500 text-xs">-->
            <!--                        <option value="ollama">محلي (Ollama)</option>-->
            <!--                        <option value="gemini">جوجل (Gemini)</option>-->
            <!--                        <option value="openrouter">OpenRouter</option>-->
            <!--                    </select>-->
            <!--                </div>-->
            <!--                <div>-->
            <!--                    <label class="block mb-1 font-bold">نموذج Ollama</label>-->
            <!--                    <input type="text" id="ai-ollama-model" placeholder="qwen2.5-coder:7b" class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-1.5 outline-none focus:ring-2 focus:ring-blue-500 text-xs font-mono text-left" dir="ltr">-->
            <!--                </div>-->
            <!--                <div id="field-gemini-sidebar" class="hidden">-->
            <!--                    <label class="block mb-1 font-bold">مفتاح Gemini</label>-->
            <!--                    <input type="password" id="ai-gemini-key" placeholder="AIzaSy..." class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-1.5 outline-none focus:ring-2 focus:ring-blue-500 text-xs font-mono text-left" dir="ltr">-->
            <!--                </div>-->
            <!--                <div id="field-openrouter-sidebar" class="hidden">-->
            <!--                    <label class="block mb-1 font-bold">مفتاح OpenRouter</label>-->
            <!--                    <input type="password" id="ai-openrouter-key" placeholder="sk-or-v1-..." class="w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-1.5 outline-none focus:ring-2 focus:ring-blue-500 text-xs font-mono text-left" dir="ltr">-->
            <!--                </div>-->
            <!--                <button onclick="saveAiSettingsSidebar()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-1.5 px-3 rounded-lg transition-colors text-xs">-->
            <!--                    حفظ الإعدادات-->
            <!--                </button>-->
            <!--            </div>-->
            <!--            <script>-->
            <!--                function toggleAiSettingsSidebar() {-->
            <!--                    const panel = document.getElementById('ai-settings-panel-sidebar');-->
            <!--                    const chevron = document.getElementById('ai-settings-chevron');-->
            <!--                    panel.classList.toggle('hidden');-->
            <!--                    if (chevron) chevron.classList.toggle('rotate-180');-->
            <!--                    if (!panel.classList.contains('hidden')) {-->
            <!--                        loadAiSettingsSidebar();-->
            <!--                    }-->
            <!--                }-->

            <!--                function loadAiSettingsSidebar() {-->
            <!--                    fetch('/chat/settings')-->
            <!--                        .then(res => res.json())-->
            <!--                        .then(data => {-->
            <!--                            document.getElementById('ai-provider').value = data.provider || 'ollama';-->
            <!--                            document.getElementById('ai-gemini-key').value = data.gemini_api_key || '';-->
            <!--                            document.getElementById('ai-openrouter-key').value = data.openrouter_api_key || '';-->
            <!--                            document.getElementById('ai-ollama-model').value = data.ollama_model || 'qwen2.5-coder:7b';-->
            <!--                            toggleProviderFieldsSidebar();-->
            <!--                        });-->
            <!--                }-->

            <!--                function toggleProviderFieldsSidebar() {-->
            <!--                    const provider = document.getElementById('ai-provider').value;-->
            <!--                    document.getElementById('field-gemini-sidebar').style.display = (provider === 'gemini') ? 'block' : 'none';-->
            <!--                    document.getElementById('field-openrouter-sidebar').style.display = (provider === 'openrouter') ? 'block' : 'none';-->
            <!--                }-->

            <!--                function saveAiSettingsSidebar() {-->
            <!--                    const provider = document.getElementById('ai-provider').value;-->
            <!--                    const geminiKey = document.getElementById('ai-gemini-key').value;-->
            <!--                    const openrouterKey = document.getElementById('ai-openrouter-key').value;-->
            <!--                    const ollamaModel = document.getElementById('ai-ollama-model').value;-->

            <!--                    fetch('/chat/settings', {-->
            <!--                        method: 'POST',-->
            <!--                        headers: {-->
            <!--                            'Content-Type': 'application/json',-->
            <!--                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')-->
            <!--                        },-->
            <!--                        body: JSON.stringify({-->
            <!--                            provider: provider,-->
            <!--                            gemini_api_key: geminiKey,-->
            <!--                            openrouter_api_key: openrouterKey,-->
            <!--                            ollama_model: ollamaModel-->
            <!--                        })-->
            <!--                    })-->
            <!--                    .then(res => res.json())-->
            <!--                    .then(data => {-->
            <!--                        if (data.success) {-->
            <!--                            alert('تم حفظ الإعدادات بنجاح!');-->
            <!--                        } else {-->
            <!--                            alert('حدث خطأ أثناء الحفظ.');-->
            <!--                        }-->
            <!--                    })-->
            <!--                    .catch(err => {-->
            <!--                        alert('فشل الاتصال بالخادم.');-->
            <!--                    });-->
            <!--                }-->
            <!--            </script>-->
            <!--        </div>-->
            <!--    </details>-->
            <!--@endcan-->

        @endif

        <!-- روابط الموظف العادي -->
        @if (Auth::user()->roles_name == ['emp'])
            <div class="pt-4 pb-1 px-5 text-[10px] font-bold text-gray-400 dark:text-gray-600 uppercase tracking-widest flex items-center gap-2">
                <span class="w-4 h-[1px] bg-gray-700"></span> الخدمة الذاتية
            </div>
            <a href="{{ url('/plzfree') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->is('plzfree*') ? 'active-link font-bold' : 'text-gray-500 hover:text-sky-500 hover:bg-sky-500/5' }}">
                <i data-lucide="calendar" class="w-5 h-5"></i>
                <span>طلب إجازة</span>
            </a>
            <a href="{{ url('/plzsalf') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->is('plzsalf*') ? 'active-link font-bold' : 'text-gray-500 hover:text-sky-500 hover:bg-sky-500/5' }}">
                <i data-lucide="banknote" class="w-5 h-5"></i>
                <span>طلب سلفة</span>
            </a>
            <a href="{{ url('/plzshift') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->is('plzshift*') ? 'active-link font-bold' : 'text-gray-500 hover:text-sky-500 hover:bg-sky-500/5' }}">
                <i data-lucide="calendar-range" class="w-5 h-5"></i>
                <span>طلب تبديل شيفت</span>
            </a>
            <a href="{{ url('/plzplz') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->is('plzplz*') ? 'active-link font-bold' : 'text-gray-500 hover:text-sky-500 hover:bg-sky-500/5' }}">
                <i data-lucide="clock" class="w-5 h-5"></i>
                <span>طلب إذن</span>
            </a>
            <a href="{{ url('/show_emp_index') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->is('show_emp_index*') ? 'active-link font-bold' : 'text-gray-500 hover:text-sky-500 hover:bg-sky-500/5' }}">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                <span>كشف الحضور الفردي</span>
            </a>
            <a href="{{ url('/in_out') }}"
                class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all {{ request()->is('in_out*') ? 'active-link font-bold' : 'text-gray-500 hover:text-sky-500 hover:bg-sky-500/5' }}">
                <i data-lucide="wallet" class="w-5 h-5"></i>
                <span>كشف الراتب</span>
            </a>
        @endif

    </nav>

    <div class="p-4 border-t border-gray-200 dark:border-white/5 bg-white dark:bg-transparent shrink-0">
        <div class="bg-gray-100 dark:bg-white/[0.03] p-3 rounded-[1.5rem] flex items-center justify-between gap-3 border border-transparent hover:border-sky-500/20 transition-all cursor-pointer group"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-xl bg-sky-500 flex items-center justify-center text-black font-black shadow-lg shadow-sky-500/20 group-hover:scale-110 transition-transform">
                    <i data-lucide="power" class="w-4 h-4"></i>
                </div>
                <div class="truncate max-w-[100px]">
                    <p class="text-xs font-bold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-gray-500">تسجيل خروج</p>
                </div>
            </div>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</aside>
