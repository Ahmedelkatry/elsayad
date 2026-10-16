@extends('layouts_new.app')

@section('title')
    التقرير الإجمالي | نظام إدارة الموظفين
@endsection

@section('page-header')
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">التقارير</span>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">التقرير الإجمالي</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">إجمالي الرواتب والخصومات</h1>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="space-y-8">
        <!-- Search Bar -->
        <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-3xl p-6 shadow-sm no-print">
            <form action="{{ route('ganeral.index') }}" class="flex flex-col md:flex-row items-end gap-4">
                <div class="flex-1 space-y-2 w-full md:w-auto">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest px-2">من تاريخ</label>
                    <input type="date" name="start" value="{{ request()->start }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-100 dark:border-white/10 text-gray-900 dark:text-white px-5 py-3 rounded-2xl outline-none focus:border-sky-500/50 transition-all">
                </div>
                <div class="flex-1 space-y-2 w-full md:w-auto">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest px-2">إلى تاريخ</label>
                    <input type="date" name="end" value="{{ request()->end }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-100 dark:border-white/10 text-gray-900 dark:text-white px-5 py-3 rounded-2xl outline-none focus:border-sky-500/50 transition-all">
                </div>
                <button type="submit" class="w-full md:w-auto bg-sky-500 text-white font-bold py-3.5 px-8 rounded-2xl shadow-lg shadow-sky-500/20 hover:bg-sky-400 transition-all flex items-center justify-center gap-2 active:scale-95">
                    <i data-lucide="search" class="w-5 h-5"></i>
                    بحث
                </button>
                <button type="button" onclick="window.print()" class="w-full md:w-auto bg-gray-100 dark:bg-[#111] text-gray-900 dark:text-white font-bold py-3.5 px-6 rounded-2xl hover:bg-gray-200 dark:hover:bg-white/5 transition-all border border-gray-200 dark:border-white/10 flex items-center justify-center gap-2">
                    <i data-lucide="printer" class="w-5 h-5"></i>
                    طباعة
                </button>
            </form>
        </div>

        @if(isset($salary))
        <!-- Main Stats Area -->
        <div id="print-area" class="space-y-6">
            
            <!-- Summary Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Net Salaries -->
                <div class="bg-gradient-to-br from-sky-500 to-sky-600 rounded-[2rem] p-6 text-black shadow-lg shadow-sky-500/20 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                    <span class="block text-xs font-black uppercase tracking-wider mb-2 opacity-80">إجمالي المرتبات بعد الخصومات</span>
                    <h2 class="text-4xl font-black">{{ number_format($salaryafter, 2) }} <span class="text-lg opacity-80">ج.م</span></h2>
                </div>

                <!-- Total Base Salaries -->
                <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-6 shadow-sm">
                    <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">إجمالي المرتبات الأساسية</span>
                    <h2 class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($salary, 2) }}</h2>
                </div>

                <!-- Total General Discounts -->
                <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-6 shadow-sm">
                    <span class="block text-[10px] font-bold text-red-400 uppercase tracking-widest mb-1">إجمالي الخصومات الشاملة</span>
                    <h2 class="text-2xl font-black text-red-500">{{ number_format($ganeral_dis, 2) }}</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- الخصومات التفصيلية -->
                <div class="bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-lucide="arrow-down-right" class="w-5 h-5 text-red-500"></i>
                        <h3 class="text-sm font-bold text-red-600 dark:text-red-400">إجمالي خصم الدخول</h3>
                    </div>
                    <p class="text-xl font-black text-red-700 dark:text-red-300">{{ number_format($counts_disin, 2) }}</p>
                </div>

                <div class="bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-lucide="arrow-down-right" class="w-5 h-5 text-red-500"></i>
                        <h3 class="text-sm font-bold text-red-600 dark:text-red-400">إجمالي خصم الخروج</h3>
                    </div>
                    <p class="text-xl font-black text-red-700 dark:text-red-300">{{ number_format($counts_disout, 2) }}</p>
                </div>

                <div class="bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-lucide="user-x" class="w-5 h-5 text-red-500"></i>
                        <h3 class="text-sm font-bold text-red-600 dark:text-red-400">أيام / خصم الغياب</h3>
                    </div>
                    <p class="text-xl font-black text-red-700 dark:text-red-300">{{ number_format($counts_absent, 2) }} <span class="text-sm opacity-70">({{ $days_absent }} يوم)</span></p>
                </div>

                <div class="bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-lucide="clock" class="w-5 h-5 text-red-500"></i>
                        <h3 class="text-sm font-bold text-red-600 dark:text-red-400">إجمالي التأخيرات</h3>
                    </div>
                    <p class="text-xl font-black text-red-700 dark:text-red-300">{{ $early_g }}</p>
                </div>

                <div class="bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-lucide="banknote" class="w-5 h-5 text-red-500"></i>
                        <h3 class="text-sm font-bold text-red-600 dark:text-red-400">إجمالي السلف</h3>
                    </div>
                    <p class="text-xl font-black text-red-700 dark:text-red-300">{{ number_format($salfs, 2) }}</p>
                </div>

                <div class="bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-lucide="credit-card" class="w-5 h-5 text-red-500"></i>
                        <h3 class="text-sm font-bold text-red-600 dark:text-red-400">إجمالي القروض</h3>
                    </div>
                    <p class="text-xl font-black text-red-700 dark:text-red-300">{{ number_format($loans, 2) }}</p>
                </div>

                <div class="bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-lucide="shopping-cart" class="w-5 h-5 text-red-500"></i>
                        <h3 class="text-sm font-bold text-red-600 dark:text-red-400">إجمالي المشتريات</h3>
                    </div>
                    <p class="text-xl font-black text-red-700 dark:text-red-300">{{ number_format($purchases, 2) }}</p>
                </div>

                <div class="bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-lucide="minus-circle" class="w-5 h-5 text-red-500"></i>
                        <h3 class="text-sm font-bold text-red-600 dark:text-red-400">خصم خارج البصمة</h3>
                    </div>
                    <p class="text-xl font-black text-red-700 dark:text-red-300">{{ number_format($diss, 2) }}</p>
                </div>

                <!-- المستحقات والإضافات -->
                <div class="bg-green-50 dark:bg-green-900/10 border border-green-100 dark:border-green-900/30 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-lucide="arrow-up-right" class="w-5 h-5 text-green-500"></i>
                        <h3 class="text-sm font-bold text-green-600 dark:text-green-400">إجمالي الحوافز</h3>
                    </div>
                    <p class="text-xl font-black text-green-700 dark:text-green-300">{{ number_format($hafzs, 2) }}</p>
                </div>

                <div class="bg-green-50 dark:bg-green-900/10 border border-green-100 dark:border-green-900/30 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-lucide="clock" class="w-5 h-5 text-green-500"></i>
                        <h3 class="text-sm font-bold text-green-600 dark:text-green-400">إجمالي الوقت الإضافي (OT)</h3>
                    </div>
                    <p class="text-xl font-black text-green-700 dark:text-green-300">{{ number_format($counts_ot, 2) }}</p>
                </div>

                <div class="bg-green-50 dark:bg-green-900/10 border border-green-100 dark:border-green-900/30 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-lucide="sun" class="w-5 h-5 text-green-500"></i>
                        <h3 class="text-sm font-bold text-green-600 dark:text-green-400">أيام / حساب حضور العطلات</h3>
                    </div>
                    <p class="text-xl font-black text-green-700 dark:text-green-300">{{ number_format($count_free, 2) }} <span class="text-sm opacity-70">({{ $days_free }} يوم)</span></p>
                </div>

            </div>
        </div>
        @endif
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #print-area, #print-area * {
                visibility: visible;
            }
            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
            .shadow-sm, .shadow-lg {
                box-shadow: none !important;
            }
            .grid {
                display: flex !important;
                flex-wrap: wrap !important;
                gap: 15px !important;
            }
            .grid > div {
                flex: 1 1 calc(25% - 15px) !important;
                min-width: 150px !important;
                border: 1px solid #e5e7eb !important;
                break-inside: avoid;
            }
            /* Adjust net salary card for print */
            .bg-gradient-to-br {
                background: #f8f9fa !important;
                color: #000 !important;
                border: 2px solid #eab308 !important;
            }
            /* Make text readable in print */
            .dark\:text-white { color: #000 !important; }
            .dark\:text-red-300 { color: #dc2626 !important; }
            .dark\:text-green-300 { color: #16a34a !important; }
            i[data-lucide] { display: none !important; }
        }
    </style>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
@endsection
