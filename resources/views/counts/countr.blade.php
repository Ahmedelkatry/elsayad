@extends('layouts_new.app')

@section('title')
    تقرير البصمة | نظام إدارة الموظفين
@endsection

@section('css')
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            height: 8px;
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 4px;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(234, 179, 8, 0.5);
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(234, 179, 8, 0.8);
        }

        /* Checkbox styling */
        .col-toggle-checkbox {
            accent-color: #eab308;
        }

        /* ===== Dark Mode Styles for Slips (Screen Only) ===== */
        .dark #slips-print-area {
            background: #111 !important;
            color: #e5e7eb !important;
        }
        .dark #slips-print-area .slip-block {
            background: #1a1a1a !important;
            border-color: #374151 !important;
            color: #e5e7eb !important;
        }
        /* اسم الموظف في dark mode */
        .dark #slips-print-area .slip-block > div[style*="background:#f3f4f6"] {
            background: #272727 !important;
            border-color: #374151 !important;
            color: #e5e7eb !important;
        }
        .dark #slips-print-area table {
            color: #e5e7eb !important;
        }
        /* رأس الجدول */
        .dark #slips-print-area tr[style*="background:#f9fafb"] {
            background: #1f2937 !important;
            color: #e5e7eb !important;
        }
        /* صف الإجماليات */
        .dark #slips-print-area tr[style*="background:#f0fdf4"] {
            background: #052e16 !important;
        }
        /* صف الصافي */
        .dark #slips-print-area div[style*="background:#fffbeb"] {
            background: #2d2200 !important;
            border-color: #78350f !important;
            color: #fbbf24 !important;
        }
        /* خلايا الجدول */
        .dark #slips-print-area td[style*="border:1px solid #ddd"],
        .dark #slips-print-area td[style*="border:1px solid #aaa"] {
            border-color: #374151 !important;
            color: #d1d5db !important;
        }
        /* الأعمدة الرمادية (البند) */
        .dark #slips-print-area span[style*="color:#888"] {
            color: #9ca3af !important;
        }


        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
            }
            /* Default Mode: Print the classic table */
            body:not(.print-a4-active):not(.print-slips-active) * {
                visibility: hidden;
            }
            body:not(.print-a4-active):not(.print-slips-active) #printable-area, body:not(.print-a4-active):not(.print-slips-active) #printable-area * {
                visibility: visible;
            }
            body:not(.print-a4-active):not(.print-slips-active) #printable-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            
            /* A4 Mode: Print the optimized A4 layout */
            body.print-a4-active * {
                visibility: hidden;
            }
            body.print-a4-active #a4-print-area, body.print-a4-active #a4-print-area * {
                visibility: visible;
            }
            body.print-a4-active #a4-print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                display: block !important;
            }
            
            /* Slips Mode: Print the individual employee slips */
            body.print-slips-active * {
                visibility: hidden;
            }
            body.print-slips-active #slips-print-area, body.print-slips-active #slips-print-area * {
                visibility: visible;
            }
            body.print-slips-active #slips-print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                display: block !important;
            }
            
            /* Slips: prevent splitting a single slip across pages */
            .slip-block {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
            }
            th, td {
                border: 1px solid #666 !important;
                padding: 3px !important;
                font-size: 8px !important;
            }
        }
    </style>
@endsection

@section('page-header')
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">التقارير</span>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">تقرير البصمة</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">تقرير البصمة والرواتب</h1>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl p-4">
                <ul class="list-disc list-inside text-red-600 dark:text-red-400 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Search Bar -->
        <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-3xl p-6 shadow-sm no-print">
            <form action="{{ route('countr') }}" class="flex flex-col md:flex-row items-end gap-4">
                @can('استيراد ملف قسم')
                <div class="flex-1 space-y-2 w-full md:w-auto">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest px-2">القسم</label>
                    <select name="name" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-100 dark:border-white/10 text-gray-900 dark:text-white px-5 py-3 rounded-2xl outline-none focus:border-sky-500/50 transition-all">
                        <option disabled {{ !request()->name ? 'selected' : '' }}>اختر القسم</option>
                        <option value="all" {{ request()->name === 'all' ? 'selected' : '' }}>كل الأقسام</option>
                        @foreach ($sections as $sec)
                            <option value="{{ $sec->id }}" {{ request()->name == $sec->id ? 'selected' : '' }}>{{ $sec->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endcan

                @can('بحث القسم')
                <div class="flex-1 space-y-2 w-full md:w-auto">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest px-2">من تاريخ</label>
                    <input type="date" name="searchs" value="{{ request()->searchs }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-100 dark:border-white/10 text-gray-900 dark:text-white px-5 py-3 rounded-2xl outline-none focus:border-sky-500/50 transition-all">
                </div>
                <div class="flex-1 space-y-2 w-full md:w-auto">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest px-2">إلى تاريخ</label>
                    <input type="date" name="searche" value="{{ request()->searche }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-100 dark:border-white/10 text-gray-900 dark:text-white px-5 py-3 rounded-2xl outline-none focus:border-sky-500/50 transition-all">
                </div>
                <button type="submit" class="w-full md:w-auto bg-sky-500 text-white font-bold py-3.5 px-8 rounded-2xl shadow-lg shadow-sky-500/20 hover:bg-sky-400 transition-all flex items-center justify-center gap-2 active:scale-95">
                    <i data-lucide="search" class="w-5 h-5"></i>
                    بحث
                </button>
                @endcan
            </form>
        </div>

        @if(!empty($report_data))
        <!-- Controls & Columns Toggler -->
        <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-3xl p-6 shadow-sm no-print">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                <div class="flex flex-wrap items-center gap-4">
                    <button onclick="triggerPrint()" class="bg-gray-100 dark:bg-[#111] text-gray-900 dark:text-white font-bold py-2.5 px-6 rounded-xl hover:bg-gray-200 dark:hover:bg-white/5 transition-all border border-gray-200 dark:border-white/10 flex items-center gap-2">
                        <i data-lucide="printer" class="w-4 h-4"></i>
                        طباعة الجدول
                    </button>
                    <div class="text-sm font-bold text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-[#111] py-2.5 px-4 rounded-xl border border-gray-100 dark:border-white/5">
                        إجمالي الرواتب: <span id="total_sum" class="text-sky-600 dark:text-sky-500 text-lg font-black mr-2">0</span>
                    </div>
                    <!-- Layout Toggle -->
                    <div class="flex items-center gap-1.5 bg-gray-50 dark:bg-[#111] p-1 rounded-xl border border-gray-200 dark:border-white/10">
                        <button type="button" id="btn-mode-classic" onclick="switchTableMode('classic')" class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all bg-sky-500 text-white shadow-md">
                            الجدول الكامل (الافتراضي)
                        </button>
                        <button type="button" id="btn-mode-a4" onclick="switchTableMode('a4')" class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-900 dark:hover:text-white">
                            تخطيط الطباعة (A4)
                        </button>
                        <button type="button" id="btn-mode-slips" onclick="switchTableMode('slips')" class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-900 dark:hover:text-white">
                            كشوفات فردية (A4)
                        </button>
                    </div>
                </div>

                <!-- Column Toggler Dropdown -->
                <div class="relative group">
                    <button class="bg-sky-500/10 text-sky-600 dark:text-sky-400 font-bold py-2.5 px-6 rounded-xl hover:bg-sky-500/20 transition-all border border-sky-500/20 flex items-center gap-2">
                        <i data-lucide="columns" class="w-4 h-4"></i>
                        إظهار/إخفاء الأعمدة
                        <i data-lucide="chevron-down" class="w-4 h-4 ml-1"></i>
                    </button>
                    <!-- Dropdown Menu -->
                    <div class="absolute left-0 mt-2 w-64 bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-2xl shadow-xl z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 p-3">
                        <div class="max-h-60 overflow-y-auto custom-scrollbar pr-2 space-y-2">
                            <!-- JS will populate these checkboxes -->
                            <div id="col-toggles"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Table Area -->
        <div id="printable-area" class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-3xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto custom-scrollbar">
                <table id="mainTable" class="w-full text-right border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-[#111] border-b border-gray-200 dark:border-white/10">
                            <th class="p-4 text-[11px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">اسم الموظف</th>
                            <th class="p-4 text-[11px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">خصم دخول</th>
                            <th class="p-4 text-[11px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">خصم خروج</th>
                            <th class="p-4 text-[11px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">أيام غياب</th>
                            <th class="p-4 text-[11px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">خصم غياب</th>
                            <th class="p-4 text-[11px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">خصم سلف</th>
                            <th class="p-4 text-[11px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">خارج بصمة</th>
                            <th class="p-4 text-[11px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">قرض</th>
                            <th class="p-4 text-[11px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">خصم مواصلات</th>
                            <th class="p-4 text-[11px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">مشتريات</th>
                            <th class="p-4 text-[11px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">تأمينات</th>
                            <th class="p-4 text-[11px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">أيام فعلية</th>
                            <th class="p-4 text-[11px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">ساعات العمل</th>
                            <th class="p-4 text-[11px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">الراتب الأساسي</th>
                            <th class="p-4 text-[11px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">إضافي إجازة</th>
                            <th class="p-4 text-[11px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">قيمة OT</th>
                            <th class="p-4 text-[11px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">حوافز</th>
                            <th class="p-4 text-[11px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">عيدية العيد</th>
                            <th class="p-4 text-[11px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">بدل مواصلات</th>
                            <th class="p-4 text-[11px] font-black text-sky-600 dark:text-sky-500 uppercase tracking-wider whitespace-nowrap">الصافي</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @foreach ($report_data as $data)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="p-4 text-sm font-black text-gray-900 dark:text-white whitespace-nowrap">{{ $data['emp_name'] }}</td>
                                <td class="p-4 text-sm font-bold text-red-500 whitespace-nowrap">{{ $data['in'] }}</td>
                                <td class="p-4 text-sm font-bold text-red-500 whitespace-nowrap">{{ $data['out'] }}</td>
                                <td class="p-4 text-sm font-bold text-red-500 whitespace-nowrap">{{ $data['absent_days'] }}</td>
                                <td class="p-4 text-sm font-bold text-red-500 whitespace-nowrap">{{ $data['absentt'] }}</td>
                                <td class="p-4 text-sm font-bold text-red-500 whitespace-nowrap">{{ $data['salfdis'] }}</td>
                                <td class="p-4 text-sm font-bold text-red-500 whitespace-nowrap">{{ $data['outdis'] }}</td>
                                <td class="p-4 text-sm font-bold text-red-500 whitespace-nowrap">{{ $data['loan'] }}</td>
                                <td class="p-4 text-sm font-bold text-red-500 whitespace-nowrap">{{ $data['dis_transport'] }}</td>
                                <td class="p-4 text-sm font-bold text-red-500 whitespace-nowrap">{{ $data['purchases'] }}</td>
                                <td class="p-4 text-sm font-bold text-red-500 whitespace-nowrap">{{ $data['taminat'] }}</td>
                                <td class="p-4 text-sm font-bold text-blue-500 whitespace-nowrap">{{ $data['sysemp'] }}</td>
                                <td class="p-4 text-sm font-bold text-blue-500 whitespace-nowrap">{{ $data['times'] }}</td>
                                <td class="p-4 text-sm font-bold text-blue-500 whitespace-nowrap">{{ $data['kabdd'] }}</td>
                                <td class="p-4 text-sm font-bold text-green-500 whitespace-nowrap">{{ $data['otfree'] }}</td>
                                <td class="p-4 text-sm font-bold text-green-500 whitespace-nowrap">{{ $data['ot'] }}</td>
                                <td class="p-4 text-sm font-bold text-green-500 whitespace-nowrap">{{ $data['hafz'] }}</td>
                                <td class="p-4 text-sm font-bold text-green-500 whitespace-nowrap">{{ $data['hafz_eid'] }}</td>
                                <td class="p-4 text-sm font-bold text-green-500 whitespace-nowrap">{{ $data['badel_transport'] }}</td>
                                <td class="p-4 text-lg font-black text-sky-600 dark:text-sky-500 whitespace-nowrap net-salary-cell" data-value="{{ $data['net_salary'] }}">{{ number_format($data['net_salary'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- NEW A4 Layout Area -->
        <div id="a4-print-area" class="hidden bg-white text-black p-6 rounded-3xl border border-gray-200">
            <!-- Header for A4 Print -->
            <div class="flex justify-between items-center mb-6 border-b-2 border-gray-900 pb-4">
                <div class="text-right">
                    <h2 class="text-lg font-black text-black">شركة بكسل للأنظمة الأمنية</h2>
                    <p class="text-xs text-gray-500">نظام إدارة الموارد البشرية</p>
                </div>
                <div class="text-center">
                    <h1 class="text-xl font-black border-2 border-black px-6 py-2 rounded-lg text-black">تقرير مسير الرواتب والحضور</h1>
                </div>
                <div class="text-left text-xs text-black">
                    <p><strong>من تاريخ:</strong> {{ request()->searchs }}</p>
                    <p><strong>إلى تاريخ:</strong> {{ request()->searche }}</p>
                    @if(request()->name && request()->name !== 'all')
                        @php
                            $selectedSec = $sections->firstWhere('id', request()->name);
                        @endphp
                        <p><strong>القسم:</strong> {{ $selectedSec ? $selectedSec->name : '' }}</p>
                    @else
                        <p><strong>القسم:</strong> كل الأقسام</p>
                    @endif
                </div>
            </div>

            <!-- Condensed print-optimized table -->
            <table class="w-full text-right border-collapse border border-gray-400 text-[9px] text-black">
                <thead>
                    <tr class="bg-gray-100 text-black border-b border-gray-400 font-bold">
                        <th class="border border-gray-400 p-1.5 font-bold whitespace-nowrap text-center">الاسم</th>
                        <th class="border border-gray-400 p-1.5 font-bold whitespace-nowrap text-center">الأساسي</th>
                        <th class="border border-gray-400 p-1.5 font-bold whitespace-nowrap text-center">أيام فعلية</th>
                        <th class="border border-gray-400 p-1.5 font-bold whitespace-nowrap text-center">ساعات</th>
                        <th class="border border-gray-400 p-1.5 font-bold whitespace-nowrap text-center">د. دخول</th>
                        <th class="border border-gray-400 p-1.5 font-bold whitespace-nowrap text-center">د. خروج</th>
                        <th class="border border-gray-400 p-1.5 font-bold whitespace-nowrap text-center">غياب (يوم/خصم)</th>
                        <th class="border border-gray-400 p-1.5 font-bold whitespace-nowrap text-center">خ. بصمة</th>
                        <th class="border border-gray-400 p-1.5 font-bold whitespace-nowrap text-center">سلف وقروض</th>
                        <th class="border border-gray-400 p-1.5 font-bold whitespace-nowrap text-center">إضافات (+)</th>
                        <th class="border border-gray-400 p-1.5 font-bold whitespace-nowrap text-center">خصومات (-)</th>
                        <th class="border border-gray-400 p-1.5 font-bold whitespace-nowrap text-center">الصافي</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($report_data as $data)
                        @php
                            $totalAdditions = $data['otfree'] + $data['ot'] + $data['hafz'] + $data['hafz_eid'] + $data['badel_transport'];
                            $totalDeductions = $data['in'] + $data['out'] + $data['absentt'] + $data['salfdis'] + $data['outdis'] + $data['loan'] + $data['dis_transport'] + $data['purchases'] + $data['taminat'];
                        @endphp
                        <tr class="border-b border-gray-300">
                            <td class="border border-gray-300 p-1.5 font-black whitespace-nowrap text-black">{{ $data['emp_name'] }}</td>
                            <td class="border border-gray-300 p-1.5 font-bold whitespace-nowrap text-center text-black">{{ number_format($data['kabdd'], 2) }}</td>
                            <td class="border border-gray-300 p-1.5 font-bold whitespace-nowrap text-center text-black">{{ $data['sysemp'] }}</td>
                            <td class="border border-gray-300 p-1.5 font-bold whitespace-nowrap text-center text-black">{{ $data['times'] }}</td>
                            <td class="border border-gray-300 p-1.5 font-bold text-red-600 whitespace-nowrap text-center">{{ $data['in'] > 0 ? number_format($data['in'], 2) : '-' }}</td>
                            <td class="border border-gray-300 p-1.5 font-bold text-red-600 whitespace-nowrap text-center">{{ $data['out'] > 0 ? number_format($data['out'], 2) : '-' }}</td>
                            <td class="border border-gray-300 p-1.5 font-bold whitespace-nowrap text-center text-black">
                                @if($data['absent_days'] > 0)
                                    {{ $data['absent_days'] }} ي / {{ number_format($data['absentt'], 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="border border-gray-300 p-1.5 font-bold text-red-600 whitespace-nowrap text-center">{{ $data['outdis'] > 0 ? number_format($data['outdis'], 2) : '-' }}</td>
                            <td class="border border-gray-300 p-1.5 font-bold whitespace-nowrap text-center text-black">
                                @if($data['salfdis'] > 0 || $data['loan'] > 0)
                                    {{ number_format($data['salfdis'] + $data['loan'], 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="border border-gray-300 p-1.5 font-bold text-green-600 whitespace-nowrap text-center">{{ $totalAdditions > 0 ? number_format($totalAdditions, 2) : '-' }}</td>
                            <td class="border border-gray-300 p-1.5 font-bold text-red-600 whitespace-nowrap text-center">{{ $totalDeductions > 0 ? number_format($totalDeductions, 2) : '-' }}</td>
                            <td class="border border-gray-300 p-1.5 font-black text-black bg-gray-50 whitespace-nowrap text-center">{{ number_format($data['net_salary'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Signatures block at the bottom -->
            <div class="grid grid-cols-3 gap-6 mt-12 text-center text-[10px] text-black">
                <div>
                    <p class="font-bold mb-8">إعداد / الموارد البشرية</p>
                    <p class="border-t border-dotted border-gray-600 pt-2 w-32 mx-auto"></p>
                </div>
                <div>
                    <p class="font-bold mb-8">الحسابات والمراجعة</p>
                    <p class="border-t border-dotted border-gray-600 pt-2 w-32 mx-auto"></p>
                </div>
                <div>
                    <p class="font-bold mb-8">اعتماد المدير العام</p>
                    <p class="border-t border-dotted border-gray-600 pt-2 w-32 mx-auto"></p>
                </div>
            </div>
        </div>

        <!-- Slips Layout Area: 4 per page using PHP chunking -->
        <div id="slips-print-area" class="hidden bg-white text-black" style="direction:rtl;">
            @php $slipIndex = 0; @endphp
            @foreach ($report_data as $data)
                @php
                    $totalAdditions = $data['otfree'] + $data['ot'] + $data['hafz'] + $data['hafz_eid'] + $data['badel_transport'];
                    $totalDeductions = $data['in'] + $data['out'] + $data['absentt'] + $data['salfdis'] + $data['outdis'] + $data['loan'] + $data['dis_transport'] + $data['purchases'] + $data['taminat'];
                    $slipIndex++;
                @endphp

                {{-- كشف الموظف المضغوط --}}
                <div class="slip-block" style="border:1px solid #888; padding:5px; margin-bottom:4px; background:white; font-size:8pt; page-break-inside:avoid; break-inside:avoid;">
                    {{-- اسم الموظف --}}
                    <div style="background:#f3f4f6; border:1px solid #ccc; padding:2px 5px; margin-bottom:3px; display:flex; justify-content:space-between;">
                        <strong>الموظف: <span style="color:#ca8a04;">{{ $data['emp_name'] }}</span></strong>
                        <span style="color:#888;">أيام: {{ $data['sysemp'] }} | ساعات: {{ $data['times'] }} | الأساسي: {{ number_format($data['kabdd'], 2) }}</span>
                    </div>
                    {{-- جدول الإضافات والخصومات في صفين --}}
                    <table style="width:100%; border-collapse:collapse; font-size:7.5pt;">
                        <tr style="background:#f9fafb; font-weight:bold; border-bottom:1px solid #aaa;">
                            <td style="border:1px solid #aaa; padding:1px 3px; width:28%;">البند (إضافات)</td>
                            <td style="border:1px solid #aaa; padding:1px 3px; width:22%; text-align:center; color:green;">القيمة</td>
                            <td style="border:1px solid #aaa; padding:1px 3px; width:28%;">البند (خصومات)</td>
                            <td style="border:1px solid #aaa; padding:1px 3px; width:22%; text-align:center; color:red;">القيمة</td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:1px 3px;">الراتب الأساسي</td>
                            <td style="border:1px solid #ddd; padding:1px 3px; text-align:center;">{{ number_format($data['kabdd'], 2) }}</td>
                            <td style="border:1px solid #ddd; padding:1px 3px;">خصم تأخير دخول</td>
                            <td style="border:1px solid #ddd; padding:1px 3px; text-align:center; color:red;">{{ $data['in'] > 0 ? number_format($data['in'], 2) : '-' }}</td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:1px 3px;">إضافي إجازة</td>
                            <td style="border:1px solid #ddd; padding:1px 3px; text-align:center; color:green;">{{ $data['otfree'] > 0 ? number_format($data['otfree'], 2) : '-' }}</td>
                            <td style="border:1px solid #ddd; padding:1px 3px;">خصم خروج مبكر</td>
                            <td style="border:1px solid #ddd; padding:1px 3px; text-align:center; color:red;">{{ $data['out'] > 0 ? number_format($data['out'], 2) : '-' }}</td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:1px 3px;">عمل إضافي (OT)</td>
                            <td style="border:1px solid #ddd; padding:1px 3px; text-align:center; color:green;">{{ $data['ot'] > 0 ? number_format($data['ot'], 2) : '-' }}</td>
                            <td style="border:1px solid #ddd; padding:1px 3px;">غياب ({{ $data['absent_days'] }} يوم)</td>
                            <td style="border:1px solid #ddd; padding:1px 3px; text-align:center; color:red;">{{ $data['absentt'] > 0 ? number_format($data['absentt'], 2) : '-' }}</td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:1px 3px;">حوافز ومكافآت</td>
                            <td style="border:1px solid #ddd; padding:1px 3px; text-align:center; color:green;">{{ $data['hafz'] > 0 ? number_format($data['hafz'], 2) : '-' }}</td>
                            <td style="border:1px solid #ddd; padding:1px 3px;">خصم بصمة</td>
                            <td style="border:1px solid #ddd; padding:1px 3px; text-align:center; color:red;">{{ $data['outdis'] > 0 ? number_format($data['outdis'], 2) : '-' }}</td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:1px 3px;">عيدية</td>
                            <td style="border:1px solid #ddd; padding:1px 3px; text-align:center; color:green;">{{ $data['hafz_eid'] > 0 ? number_format($data['hafz_eid'], 2) : '-' }}</td>
                            <td style="border:1px solid #ddd; padding:1px 3px;">سلف + قروض</td>
                            <td style="border:1px solid #ddd; padding:1px 3px; text-align:center; color:red;">{{ ($data['salfdis']+$data['loan']) > 0 ? number_format($data['salfdis']+$data['loan'], 2) : '-' }}</td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #ddd; padding:1px 3px;">بدل مواصلات</td>
                            <td style="border:1px solid #ddd; padding:1px 3px; text-align:center; color:green;">{{ $data['badel_transport'] > 0 ? number_format($data['badel_transport'], 2) : '-' }}</td>
                            <td style="border:1px solid #ddd; padding:1px 3px;">تأمينات + مشتريات</td>
                            <td style="border:1px solid #ddd; padding:1px 3px; text-align:center; color:red;">{{ ($data['taminat']+$data['purchases']+$data['dis_transport']) > 0 ? number_format($data['taminat']+$data['purchases']+$data['dis_transport'], 2) : '-' }}</td>
                        </tr>
                        <tr style="background:#f0fdf4; font-weight:bold;">
                            <td style="border:1px solid #aaa; padding:1px 3px; color:green;">إجمالي الإضافات</td>
                            <td style="border:1px solid #aaa; padding:1px 3px; text-align:center; color:green;">{{ number_format($totalAdditions, 2) }}</td>
                            <td style="border:1px solid #aaa; padding:1px 3px; color:red;">إجمالي الخصومات</td>
                            <td style="border:1px solid #aaa; padding:1px 3px; text-align:center; color:red;">{{ number_format($totalDeductions, 2) }}</td>
                        </tr>
                    </table>
                    {{-- الصافي --}}
                    <div style="background:#fffbeb; border:1px solid #aaa; padding:2px 5px; margin-top:3px; font-weight:900; font-size:8pt;">
                        صافي الراتب: <span style="color:#ca8a04; font-size:10pt;">{{ number_format($data['net_salary'], 2) }} جنيه</span>
                    </div>
                </div>

                {{-- كسر الصفحة بعد كل 4 موظفين --}}
                @if($slipIndex % 4 === 0 && !$loop->last)
                    <div style="page-break-after:always; break-after:page;"></div>
                @endif

            @endforeach
        </div>
        @endif
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            const table = document.getElementById('mainTable');
            if (table) {
                // 1. حساب الإجمالي
                let total = 0;
                document.querySelectorAll('.net-salary-cell').forEach(el => {
                    let val = parseFloat(el.getAttribute('data-value'));
                    if (!isNaN(val)) total += val;
                });
                const totalEl = document.getElementById('total_sum');
                if (totalEl) {
                    totalEl.textContent = total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                }

                // 2. تفعيل إخفاء وإظهار الأعمدة
                const headers = table.querySelectorAll('thead th');
                const toggleContainer = document.getElementById('col-toggles');
                
                headers.forEach((th, index) => {
                    // لا نسمح بإخفاء اسم الموظف (0) أو الصافي (الأخير)
                    if (index === 0 || index === headers.length - 1) return;

                    const title = th.textContent.trim();
                    const storageKey = 'colVis_countr_' + index;
                    const savedState = localStorage.getItem(storageKey);
                    let isVisible = savedState === null ? true : (savedState === 'visible');
                    
                    const label = document.createElement('label');
                    label.className = "flex items-center gap-3 p-2 hover:bg-gray-50 dark:hover:bg-[#111] rounded-xl cursor-pointer transition-colors";
                    
                    const checkbox = document.createElement('input');
                    checkbox.type = "checkbox";
                    checkbox.checked = isVisible;
                    checkbox.className = "col-toggle-checkbox w-4 h-4 rounded border-gray-300 dark:border-gray-600 cursor-pointer";
                    
                    const toggleColumn = (visible) => {
                        th.style.display = visible ? '' : 'none';
                        const rows = table.querySelectorAll('tbody tr');
                        rows.forEach(row => {
                            const cell = row.cells[index];
                            if (cell) {
                                cell.style.display = visible ? '' : 'none';
                            }
                        });
                    };

                    // تطبيق الحالة المحفوظة فوراً
                    if (!isVisible) {
                        toggleColumn(false);
                    }
                    
                    checkbox.addEventListener('change', function() {
                        isVisible = this.checked;
                        toggleColumn(isVisible);
                        localStorage.setItem(storageKey, isVisible ? 'visible' : 'hidden');
                    });

                    const span = document.createElement('span');
                    span.className = "text-[11px] font-bold text-gray-700 dark:text-gray-300";
                    span.textContent = title;

                    label.appendChild(checkbox);
                    label.appendChild(span);
                    toggleContainer.appendChild(label);
                });
            }
        });

        let activeMode = 'classic';

        function switchTableMode(mode) {
            activeMode = mode;
            const classicBtn = document.getElementById('btn-mode-classic');
            const a4Btn = document.getElementById('btn-mode-a4');
            const slipsBtn = document.getElementById('btn-mode-slips');
            const classicArea = document.getElementById('printable-area');
            const a4Area = document.getElementById('a4-print-area');
            const slipsArea = document.getElementById('slips-print-area');

            if (mode === 'a4') {
                if (a4Area) a4Area.classList.remove('hidden');
                if (classicArea) classicArea.classList.add('hidden');
                if (slipsArea) slipsArea.classList.add('hidden');
                document.body.classList.add('print-a4-active');
                document.body.classList.remove('print-slips-active');

                // Adjust buttons styles
                if (a4Btn) a4Btn.className = "px-4 py-1.5 rounded-lg text-xs font-bold transition-all bg-sky-500 text-white shadow-md";
                if (classicBtn) classicBtn.className = "px-4 py-1.5 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-900 dark:hover:text-white";
                if (slipsBtn) slipsBtn.className = "px-4 py-1.5 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-900 dark:hover:text-white";
            } else if (mode === 'slips') {
                if (slipsArea) slipsArea.classList.remove('hidden');
                if (classicArea) classicArea.classList.add('hidden');
                if (a4Area) a4Area.classList.add('hidden');
                document.body.classList.add('print-slips-active');
                document.body.classList.remove('print-a4-active');

                // Adjust buttons styles
                if (slipsBtn) slipsBtn.className = "px-4 py-1.5 rounded-lg text-xs font-bold transition-all bg-sky-500 text-white shadow-md";
                if (classicBtn) classicBtn.className = "px-4 py-1.5 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-900 dark:hover:text-white";
                if (a4Btn) a4Btn.className = "px-4 py-1.5 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-900 dark:hover:text-white";
            } else {
                if (classicArea) classicArea.classList.remove('hidden');
                if (a4Area) a4Area.classList.add('hidden');
                if (slipsArea) slipsArea.classList.add('hidden');
                document.body.classList.remove('print-a4-active');
                document.body.classList.remove('print-slips-active');

                // Adjust buttons styles
                if (classicBtn) classicBtn.className = "px-4 py-1.5 rounded-lg text-xs font-bold transition-all bg-sky-500 text-white shadow-md";
                if (a4Btn) a4Btn.className = "px-4 py-1.5 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-900 dark:hover:text-white";
                if (slipsBtn) slipsBtn.className = "px-4 py-1.5 rounded-lg text-xs font-bold transition-all text-gray-500 hover:text-gray-900 dark:hover:text-white";
            }
        }

        function triggerPrint() {
            window.print();
        }
    </script>
@endsection
