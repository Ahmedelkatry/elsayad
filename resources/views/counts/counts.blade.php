@extends('layouts_new.app')
@section('title')
    تقرير البصمة | نظام إدارة الموظفين
@endsection

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        #modalMap {
            height: 400px;
            width: 100%;
            border-radius: 1rem;
            z-index: 50;
        }
        .dataTables_wrapper .dataTables_filter input {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.5rem;
            margin-right: 0.5rem;
        }
        .dark .dataTables_wrapper .dataTables_filter input {
            background: #111;
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
        }
        .dataTables_wrapper .dataTables_length select {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.25rem 1rem;
        }
        .dark .dataTables_wrapper .dataTables_length select {
            background: #111;
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
        }
        
        /* Print styles */
        @media print {
            body * {
                visibility: hidden;
            }
            #printt, #printt * {
                visibility: visible;
            }
            #printt {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                background: white;
            }
            .no-print {
                display: none !important;
            }
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                display: none !important;
            }
        }
        
        /* Vue Transitions */
        .fade-enter-active, .fade-leave-active {
            transition: opacity 0.3s ease;
        }
        .fade-enter-from, .fade-leave-to {
            opacity: 0;
        }
    </style>
    <!-- Vue 3 -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
@endsection

@section('page-header')
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">شؤون الموظفين</span>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">تقرير البصمة الشامل</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">تقرير البصمة</h1>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection

@section('content')
    <div id="app" class="space-y-6">
        
        <!-- Search Filter Card -->
        <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-6 shadow-sm">
            <form action="{{ route('counts.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1 w-full">
                    <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">اسم الموظف</label>
                    <select name="name" id="employee-select" class="form-control form-control-sm select2 w-full" required>
                        <option value="" disabled selected>ابحث واختر الموظف </option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request()->name == $emp->id ? 'selected' : '' }}>
                                {{ $emp->num . ' - ' . $emp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                @can('بحث موظف')
                <div class="flex-1 w-full">
                    <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">من تاريخ</label>
                    <input type="date" name="searchs" value="{{ request()->searchs ?? '' }}" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2 rounded-xl outline-none focus:border-sky-500/50 text-sm transition-colors">
                </div>
                
                <div class="flex-1 w-full">
                    <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">إلى تاريخ</label>
                    <input type="date" name="searche" value="{{ request()->searche ?? '' }}" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2 rounded-xl outline-none focus:border-sky-500/50 text-sm transition-colors">
                </div>
                
                <div class="w-full md:w-auto">
                    <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-sm font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        بحث
                    </button>
                </div>
                @endcan
            </form>
        </div>

        <!-- Custom Columns Card -->
        <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-6 shadow-sm no-print">
            <h6 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="settings-2" class="w-4 h-4 text-sky-500"></i> تخصيص الأعمدة المعروضة
            </h6>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @php
                    $filters = [
                        ['model' => 'date', 'label' => 'التاريخ'],
                        ['model' => 'checkinD', 'label' => 'الدخول الملزم'],
                        ['model' => 'checkoutD', 'label' => 'الخروج الملزم'],
                        ['model' => 'shift', 'label' => 'الشيفت'],
                        ['model' => 'checkin', 'label' => 'دخول'],
                        ['model' => 'checkout', 'label' => 'خروج'],
                        ['model' => 'breakout', 'label' => 'خروج استراحه'],
                        ['model' => 'breakin', 'label' => 'دخول استراحه'],
                        ['model' => 'disin', 'label' => 'خصم دخول'],
                        ['model' => 'disout', 'label' => 'خصم خروج'],
                        ['model' => 'early', 'label' => 'تاخير'],
                        ['model' => 'free', 'label' => 'الاجازه الرسميه'],
                        ['model' => 'hafz', 'label' => 'حافز'],
                        ['model' => 'hwork', 'label' => 'ساعات العمل'],
                        ['model' => 'hoursot', 'label' => 'ساعات OT'],
                        ['model' => 'countot', 'label' => 'حساب OT'],
                        ['model' => 'dis', 'label' => 'خصم'],
                        ['model' => 'salf', 'label' => 'سلفه'],
                        ['model' => 'absent', 'label' => 'غياب'],
                        ['model' => 'countabsent', 'label' => 'حساب الغياب'],
                    ];
                @endphp
                @foreach ($filters as $filter)
                    <label class="custom-checkbox-container select-none">
                        <input type="checkbox" class="rounded border-gray-300 text-sky-500 shadow-sm focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50" v-model="{{ $filter['model'] }}">
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $filter['label'] }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Main Report Area -->
        <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-6 shadow-sm">
            
            <!-- Tabs Navigation -->
            <div class="border-b border-gray-100 dark:border-white/5 mb-6 no-print">
                <ul class="flex flex-wrap -mb-px text-sm font-bold text-center gap-4">
                    <li class="me-2">
                        <button class="inline-flex items-center justify-center p-4 border-b-2 border-sky-500 text-sky-500 rounded-t-lg active group outline-none" id="tab1-btn" onclick="switchTab('tab1')">
                            تقرير البصمة اليومي
                        </button>
                    </li>
                    <li class="me-2">
                        <button class="inline-flex items-center justify-center p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 text-gray-500 dark:text-gray-400 outline-none" id="tab2-btn" onclick="switchTab('tab2')">
                            تقرير شؤون الموظف
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Tab 1: Daily Report -->
            <div id="tab1" class="block">
                <div id="printt">
                    <div class="flex justify-between items-center mb-6 no-print">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">تفاصيل البصمة</h3>
                        <button onclick="printDiv('printt')" class="px-5 py-2.5 bg-gray-100 dark:bg-white/5 text-gray-900 dark:text-white hover:bg-gray-200 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                            <i data-lucide="printer" class="w-4 h-4"></i> طباعة التقرير
                        </button>
                    </div>

                    <div class="overflow-x-auto print:overflow-visible">
                        <table class="w-full text-center border-collapse text-sm whitespace-nowrap" id='mytable'>
                            <thead>
                                <tr class="bg-gray-50 dark:bg-[#111] border-b border-gray-200 dark:border-white/10">
                                    <th v-show="date" class="py-3 px-2 font-bold text-gray-500 dark:text-gray-400">التاريخ</th>
                                    <th v-show="checkinD" class="py-3 px-2 font-bold text-gray-500 dark:text-gray-400">الدخول الملزم</th>
                                    <th v-show="checkoutD" class="py-3 px-2 font-bold text-gray-500 dark:text-gray-400">الخروج الملزم</th>
                                    <th v-show="shift" class="py-3 px-2 font-bold text-gray-500 dark:text-gray-400">الشيفت</th>
                                    <th v-show="checkin" class="py-3 px-2 font-bold text-gray-500 dark:text-gray-400">دخول</th>
                                    <th v-show="checkout" class="py-3 px-2 font-bold text-gray-500 dark:text-gray-400">خروج</th>
                                    <th v-show="breakout" class="py-3 px-2 font-bold text-gray-500 dark:text-gray-400">خروج استراحه</th>
                                    <th v-show="breakin" class="py-3 px-2 font-bold text-gray-500 dark:text-gray-400">دخول استراحه</th>
                                    <th v-show="disin" class="py-3 px-2 font-bold text-gray-500 dark:text-gray-400">خصم دخول</th>
                                    <th v-show="disout" class="py-3 px-2 font-bold text-gray-500 dark:text-gray-400">خصم خروج</th>
                                    <th v-show="early" class="py-3 px-2 font-bold text-gray-500 dark:text-gray-400">تاخير</th>
                                    <th v-show="free" class="py-3 px-2 font-bold text-gray-500 dark:text-gray-400">اجازه رسميه</th>
                                    <th v-show="hafz" class="py-3 px-2 font-bold text-gray-500 dark:text-gray-400">حافز</th>
                                    <th v-show="hwork" class="py-3 px-2 font-bold text-gray-500 dark:text-gray-400">ساعات العمل</th>
                                    <th v-show="hoursot" class="py-3 px-2 font-bold text-gray-500 dark:text-gray-400">ساعات ot</th>
                                    <th v-show="countot" class="py-3 px-2 font-bold text-gray-500 dark:text-gray-400">ot حساب</th>
                                    <th v-show="dis" class="py-3 px-2 font-bold text-gray-500 dark:text-gray-400">الخصم</th>
                                    <th v-show="salf" class="py-3 px-2 font-bold text-gray-500 dark:text-gray-400">سلفه</th>
                                    <th v-show="absent" class="py-3 px-2 font-bold text-gray-500 dark:text-gray-400">الغياب</th>
                                    <th v-show="countabsent" class="py-3 px-2 font-bold text-gray-500 dark:text-gray-400">حساب الغياب</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $sort = isset($cos) ? $cos->sortBy('date') : collect();
                                @endphp
                                @foreach ($sort as $count)
                                    <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 {{ $count->check_loction == 1 ? 'bg-sky-50/50 dark:bg-sky-900/20' : '' }}">
                                        
                                        <td v-show="date" class="py-3 px-2 text-xs font-bold {{ $count->check_loction == 1 ? 'text-sky-600' : 'text-gray-900 dark:text-white' }}">
                                            {{ $count->date }} <br>
                                            <span class="text-[10px] text-gray-500">{{ Carbon\Carbon::parse($count->date)->locale('ar')->dayName }}</span>
                                        </td>
                                        
                                        <td v-show="checkinD" class="py-3 px-2 text-xs text-gray-600 dark:text-gray-400">{{ $count->in_duty }}</td>
                                        <td v-show="checkoutD" class="py-3 px-2 text-xs text-gray-600 dark:text-gray-400">{{ $count->out_duty }}</td>
                                        <td v-show="shift" class="py-3 px-2 text-xs font-bold text-blue-500">{{ $count->shiftt->name ?? 'لا يوجد' }}</td>
                                        
                                        <td v-show="checkin" class="py-3 px-2 text-xs">
                                            <span class="font-bold text-green-500 block">{{ $count->in }}</span>
                                            @if ($count->latitude_in && $count->longitude_in)
                                                <button type="button" onclick="showOnMap({{ $count->latitude_in }}, {{ $count->longitude_in }}, 'دخول - {{ $count->in }}')" class="mt-1 px-2 py-1 bg-green-100 dark:bg-green-500/10 text-green-600 rounded text-[10px] hover:bg-green-200 transition-colors no-print">
                                                    <i class="fa fa-map-marker-alt"></i> الخريطة
                                                </button>
                                            @endif
                                        </td>
                                        
                                        <td v-show="checkout" class="py-3 px-2 text-xs">
                                            <span class="font-bold text-red-500 block">{{ $count->out }}</span>
                                            @if ($count->latitude_out && $count->longitude_out)
                                                <button type="button" onclick="showOnMap({{ $count->latitude_out }}, {{ $count->longitude_out }}, 'خروج - {{ $count->out }}')" class="mt-1 px-2 py-1 bg-red-100 dark:bg-red-500/10 text-red-600 rounded text-[10px] hover:bg-red-200 transition-colors no-print">
                                                    <i class="fa fa-map-marker-alt"></i> الخريطة
                                                </button>
                                            @endif
                                        </td>

                                        <td v-show="breakout" class="py-3 px-2 text-xs text-orange-500">
                                            {{ $count->break_out }}
                                            @if ($count->latitude_break_out && $count->longitude_break_out)
                                                <button type="button" onclick="showOnMap({{ $count->latitude_break_out }}, {{ $count->longitude_break_out }}, 'خروج استراحة - {{ $count->break_out }}')" class="mt-1 px-2 py-1 bg-orange-100 text-orange-600 rounded text-[10px] no-print"><i class="fa fa-map-marker-alt"></i></button>
                                            @endif
                                        </td>
                                        <td v-show="breakin" class="py-3 px-2 text-xs text-orange-500">
                                            {{ $count->break_in }}
                                            @if ($count->latitude_break_in && $count->longitude_break_in)
                                                <button type="button" onclick="showOnMap({{ $count->latitude_break_in }}, {{ $count->longitude_break_in }}, 'دخول استراحة - {{ $count->break_in }}')" class="mt-1 px-2 py-1 bg-orange-100 text-orange-600 rounded text-[10px] no-print"><i class="fa fa-map-marker-alt"></i></button>
                                            @endif
                                        </td>

                                        <td v-show="disin" class="py-3 px-2 text-xs font-bold text-red-500">{{ round($count->dis, 2) }}</td>
                                        <td v-show="disout" class="py-3 px-2 text-xs font-bold text-red-500">{{ round($count->disout, 2) }}</td>
                                        <td v-show="early" class="py-3 px-2 text-xs font-bold text-red-500">{{ $count->early }}</td>
                                        <td v-show="free" class="py-3 px-2 text-xs font-bold text-green-500">{{ $count->friday == 1 ? 'حاضر' : '' }}</td>
                                        
                                        @php $hafzc=0; @endphp
                                        @if (isset($id_emp) && App\models\hafz::where('employees_id', $id_emp)->where('date', $count->date)->first('hafz') === null)
                                            <td v-show="hafz" class="py-3 px-2 text-xs text-gray-500">0</td>
                                        @else
                                            <td v-show="hafz" class="py-3 px-2 text-xs font-bold text-green-500">
                                                {{ $hafzc = isset($id_emp) ? App\models\hafz::where('employees_id', $id_emp)->where('date', $count->date)->first()->hafz : 0 }}
                                            </td>
                                        @endif

                                        <td v-show="hwork" class="py-3 px-2 text-xs font-bold text-green-500">{{ $count->dayh ? gmdate('H:i:s', $count->dayh) : '00:00:00' }}</td>
                                        <td v-show="hoursot" class="py-3 px-2 text-xs font-bold text-green-500">{{ $count->othours ? gmdate('H:i:s', $count->othours) : '00:00:00' }}</td>
                                        <td v-show="countot" class="py-3 px-2 text-xs font-bold text-green-500">{{ $otmin = round($count->count_ot, 2) }}</td>

                                        @php $disc=0; @endphp
                                        @if (isset($id_emp) && App\models\disemp::where('employees_id', $id_emp)->where('date', $count->date)->first('dis') === null)
                                            <td v-show="dis" class="py-3 px-2 text-xs text-gray-500">0</td>
                                        @else
                                            <td v-show="dis" class="py-3 px-2 text-xs font-bold text-red-500">
                                                {{ $disc = isset($id_emp) ? App\models\disemp::where('employees_id', $id_emp)->where('date', $count->date)->sum('dis') : 0 }}
                                            </td>
                                        @endif

                                        @php $salfc=0; @endphp
                                        @if (isset($id_emp) && App\models\salf::where('employees_id', $id_emp)->where('date', $count->date)->first('salf') === null)
                                            <td v-show="salf" class="py-3 px-2 text-xs text-gray-500">0</td>
                                        @else
                                            <td v-show="salf" class="py-3 px-2 text-xs font-bold text-red-500">
                                                {{ $salfc = isset($id_emp) ? App\models\salf::where('employees_id', $id_emp)->where('date', $count->date)->sum('salf') : 0 }}
                                            </td>
                                        @endif

                                        <td v-show="absent" class="py-3 px-2 text-xs font-bold text-red-500">{{ $count->absent == 1 ? 'غياب' : '' }}</td>
                                        <td v-show="countabsent" class="py-3 px-2 text-xs font-bold text-red-500">{{ number_format($count->absent_dis, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if (!isset($cos) || count($cos) == 0)
                        <div class="text-center py-8 text-gray-500 font-bold">لا توجد بيانات بصمة لهذا الموظف في هذه الفترة</div>
                    @endif

                    @if(isset($emp_name))
                    <!-- Summary Table -->
                    <div class="mt-12">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-1.5 h-6 bg-sky-500 rounded-full"></div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">ملخص الموظف: <span class="text-sky-500">{{ $emp_name }}</span></h3>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-6">
                            <!-- Deductions -->
                            <div class="bg-red-50 dark:bg-red-500/5 p-4 rounded-xl border border-red-100 dark:border-red-500/10">
                                <p class="text-[10px] font-bold text-red-500 mb-1">خصم دخول</p>
                                <h4 class="text-lg font-black text-red-600 dark:text-red-400">{{ round($disin ?? 0, 2) }}</h4>
                            </div>
                            <div class="bg-red-50 dark:bg-red-500/5 p-4 rounded-xl border border-red-100 dark:border-red-500/10">
                                <p class="text-[10px] font-bold text-red-500 mb-1">خصم خروج</p>
                                <h4 class="text-lg font-black text-red-600 dark:text-red-400">{{ round($disout ?? 0, 2) }}</h4>
                            </div>
                            <div class="bg-red-50 dark:bg-red-500/5 p-4 rounded-xl border border-red-100 dark:border-red-500/10">
                                <p class="text-[10px] font-bold text-red-500 mb-1">عدد أيام الغياب</p>
                                <h4 class="text-lg font-black text-red-600 dark:text-red-400">{{ $absent ?? 0 }}</h4>
                            </div>
                            <div class="bg-red-50 dark:bg-red-500/5 p-4 rounded-xl border border-red-100 dark:border-red-500/10">
                                <p class="text-[10px] font-bold text-red-500 mb-1">خصم الغياب</p>
                                <h4 class="text-lg font-black text-red-600 dark:text-red-400">{{ number_format($disabsent ?? 0, 2) }}</h4>
                            </div>
                            <div class="bg-red-50 dark:bg-red-500/5 p-4 rounded-xl border border-red-100 dark:border-red-500/10">
                                <p class="text-[10px] font-bold text-red-500 mb-1">إجمالي التأخير</p>
                                <h4 class="text-lg font-black text-red-600 dark:text-red-400">{{ $early_new ?? 0 }}</h4>
                            </div>
                            <div class="bg-red-50 dark:bg-red-500/5 p-4 rounded-xl border border-red-100 dark:border-red-500/10">
                                <p class="text-[10px] font-bold text-red-500 mb-1">خصم خارج البصمة</p>
                                <h4 class="text-lg font-black text-red-600 dark:text-red-400">{{ $disdis ?? 0 }}</h4>
                            </div>
                            <div class="bg-red-50 dark:bg-red-500/5 p-4 rounded-xl border border-red-100 dark:border-red-500/10">
                                <p class="text-[10px] font-bold text-red-500 mb-1">خصم سلف</p>
                                <h4 class="text-lg font-black text-red-600 dark:text-red-400">{{ number_format($dissalf ?? 0, 2) }}</h4>
                            </div>
                            <div class="bg-red-50 dark:bg-red-500/5 p-4 rounded-xl border border-red-100 dark:border-red-500/10">
                                <p class="text-[10px] font-bold text-red-500 mb-1">القرض</p>
                                <h4 class="text-lg font-black text-red-600 dark:text-red-400">{{ number_format($loan ?? 0, 2) }}</h4>
                            </div>
                            <div class="bg-red-50 dark:bg-red-500/5 p-4 rounded-xl border border-red-100 dark:border-red-500/10">
                                <p class="text-[10px] font-bold text-red-500 mb-1">خصم مواصلات</p>
                                <h4 class="text-lg font-black text-red-600 dark:text-red-400">{{ number_format($dis_trans ?? 0) }}</h4>
                            </div>
                            <div class="bg-red-50 dark:bg-red-500/5 p-4 rounded-xl border border-red-100 dark:border-red-500/10">
                                <p class="text-[10px] font-bold text-red-500 mb-1">التأمينات</p>
                                <h4 class="text-lg font-black text-red-600 dark:text-red-400">{{ number_format($taminat ?? 0) }}</h4>
                            </div>
                            <div class="bg-red-50 dark:bg-red-500/5 p-4 rounded-xl border border-red-100 dark:border-red-500/10">
                                <p class="text-[10px] font-bold text-red-500 mb-1">المشتريات</p>
                                <h4 class="text-lg font-black text-red-600 dark:text-red-400">{{ number_format($purchases ?? 0) }}</h4>
                            </div>
                            
                            <!-- Additions / Info -->
                            <div class="bg-green-50 dark:bg-green-500/5 p-4 rounded-xl border border-green-100 dark:border-green-500/10">
                                <p class="text-[10px] font-bold text-green-600 dark:text-green-400 mb-1">بدل مواصلات</p>
                                <h4 class="text-lg font-black text-green-700 dark:text-green-300">{{ number_format($badel_trans ?? 0) }}</h4>
                            </div>
                            <div class="bg-green-50 dark:bg-green-500/5 p-4 rounded-xl border border-green-100 dark:border-green-500/10">
                                <p class="text-[10px] font-bold text-green-600 dark:text-green-400 mb-1">ساعات العمل</p>
                                <h4 class="text-lg font-black text-green-700 dark:text-green-300">
                                    @php
                                        $dayss = $dayss ?? 0;
                                        $hours = floor($dayss / 3600);
                                        $minutes = floor(($dayss % 3600) / 60);
                                        $secs = $dayss % 60;
                                        echo sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
                                    @endphp
                                </h4>
                            </div>
                            <div class="bg-green-50 dark:bg-green-500/5 p-4 rounded-xl border border-green-100 dark:border-green-500/10">
                                <p class="text-[10px] font-bold text-green-600 dark:text-green-400 mb-1">يوم الموظف</p>
                                <h4 class="text-lg font-black text-green-700 dark:text-green-300">{{ round($countday ?? 0, 2) }}</h4>
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-500/5 p-4 rounded-xl border border-blue-100 dark:border-blue-500/10">
                                <p class="text-[10px] font-bold text-blue-600 dark:text-blue-400 mb-1">أيام الشهر</p>
                                <h4 class="text-lg font-black text-blue-700 dark:text-blue-300">{{ $sysemp ?? 0 }}</h4>
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-500/5 p-4 rounded-xl border border-blue-100 dark:border-blue-500/10">
                                <p class="text-[10px] font-bold text-blue-600 dark:text-blue-400 mb-1">أيام الإجازة الرسمية</p>
                                <h4 class="text-lg font-black text-blue-700 dark:text-blue-300">{{ $count_vacation ?? 0 }}</h4>
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-500/5 p-4 rounded-xl border border-blue-100 dark:border-blue-500/10">
                                <p class="text-[10px] font-bold text-blue-600 dark:text-blue-400 mb-1">حضور الإجازة (أيام)</p>
                                <h4 class="text-lg font-black text-blue-700 dark:text-blue-300">{{ $fri ?? 0 }}</h4>
                            </div>
                            
                            <!-- Overtime & Bonus -->
                            <div class="bg-green-50 dark:bg-green-500/5 p-4 rounded-xl border border-green-100 dark:border-green-500/10">
                                <p class="text-[10px] font-bold text-green-600 dark:text-green-400 mb-1">ساعات OT</p>
                                <h4 class="text-lg font-black text-green-700 dark:text-green-300">{{ isset($othours) ? Carbon\Carbon::parse($othours)->format('H:i:s') : '00:00:00' }}</h4>
                            </div>
                            <div class="bg-green-50 dark:bg-green-500/5 p-4 rounded-xl border border-green-100 dark:border-green-500/10">
                                <p class="text-[10px] font-bold text-green-600 dark:text-green-400 mb-1">حساب OT</p>
                                <h4 class="text-lg font-black text-green-700 dark:text-green-300">{{ number_format($overtime ?? 0, 2) }}</h4>
                            </div>
                            <div class="bg-green-50 dark:bg-green-500/5 p-4 rounded-xl border border-green-100 dark:border-green-500/10">
                                <p class="text-[10px] font-bold text-green-600 dark:text-green-400 mb-1">الحوافز</p>
                                <h4 class="text-lg font-black text-green-700 dark:text-green-300">{{ number_format($hafzs ?? 0, 2) }}</h4>
                            </div>
                            
                            <!-- Totals -->
                            <div class="col-span-2 sm:col-span-3 bg-red-100 dark:bg-red-500/20 p-4 rounded-xl border border-red-200 dark:border-red-500/30 flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-bold text-red-600 dark:text-red-400 mb-1">إجمالي الخصومات</p>
                                    <h4 class="text-2xl font-black text-red-700 dark:text-red-300">{{ number_format($afterdis ?? 0, 2) }} <span class="text-sm font-normal">ج.م</span></h4>
                                </div>
                                <i data-lucide="trending-down" class="w-8 h-8 text-red-500/50"></i>
                            </div>
                            
                            <div class="col-span-2 sm:col-span-3 bg-sky-50 dark:bg-sky-500/10 p-4 rounded-xl border border-sky-200 dark:border-sky-500/20 flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-bold text-sky-600 dark:text-sky-400 mb-1">الراتب الأساسي</p>
                                    <h4 class="text-2xl font-black text-sky-700 dark:text-sky-300">{{ $empkabd ?? 0 }} <span class="text-sm font-normal">ج.م</span></h4>
                                </div>
                                <i data-lucide="wallet" class="w-8 h-8 text-sky-500/50"></i>
                            </div>
                            
                            <div class="col-span-full bg-green-100 dark:bg-green-500/20 p-6 rounded-xl border border-green-200 dark:border-green-500/30 flex flex-col md:flex-row items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white shadow-lg shadow-green-500/30">
                                        <i data-lucide="banknote" class="w-6 h-6"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-green-700 dark:text-green-400 mb-1">الصافي بعد الحوافز والـ OT (على {{ $bet ?? 0 }} يوم)</p>
                                        <h4 class="text-3xl font-black text-green-800 dark:text-green-300">{{ number_format($salary_days ?? 0, 2) }} <span class="text-lg font-bold">ج.م</span></h4>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-green-600/70 dark:text-green-400/70 font-bold mb-1">الراتب بعد الـ OT</p>
                                    <p class="text-xl font-black text-green-700 dark:text-green-400">{{ number_format($afterot ?? 0, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Tab 2: Employee Breakdown -->
            <div id="tab2" class="hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    <!-- Deductions Outside Fingerprint -->
                    <div class="bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 rounded-2xl overflow-hidden flex flex-col">
                        <div class="p-4 border-b border-gray-200 dark:border-white/10 bg-white/50 dark:bg-transparent flex items-center gap-2">
                            <i data-lucide="minus-circle" class="w-4 h-4 text-red-500"></i>
                            <h4 class="font-bold text-gray-900 dark:text-white">خصومات خارج البصمة</h4>
                        </div>
                        <div class="p-4 flex-1 overflow-auto max-h-80">
                            @if (isset($disemp) && count($disemp) > 0)
                                <table class="w-full text-right text-sm">
                                    <thead class="text-gray-500">
                                        <tr>
                                            <th class="pb-2">الخصم</th>
                                            <th class="pb-2">السبب</th>
                                            <th class="pb-2">التاريخ</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                                        @foreach ($disemp as $dise)
                                            <tr>
                                                <td class="py-2 font-bold text-red-500">{{ $dise->dis }} ج.م</td>
                                                <td class="py-2 text-gray-700 dark:text-gray-300">{{ $dise->dec }}</td>
                                                <td class="py-2 text-gray-500 text-xs">{{ $dise->date }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="flex items-center justify-center h-full text-gray-400 text-sm font-bold">لا يوجد بيانات</div>
                            @endif
                        </div>
                    </div>

                    <!-- Advances (Salf) -->
                    <div class="bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 rounded-2xl overflow-hidden flex flex-col">
                        <div class="p-4 border-b border-gray-200 dark:border-white/10 bg-white/50 dark:bg-transparent flex items-center gap-2">
                            <i data-lucide="credit-card" class="w-4 h-4 text-orange-500"></i>
                            <h4 class="font-bold text-gray-900 dark:text-white">السلف</h4>
                        </div>
                        <div class="p-4 flex-1 overflow-auto max-h-80">
                            @if (isset($salfs) && count($salfs) > 0)
                                <table class="w-full text-right text-sm">
                                    <thead class="text-gray-500">
                                        <tr>
                                            <th class="pb-2">المبلغ</th>
                                            <th class="pb-2">السبب</th>
                                            <th class="pb-2">التاريخ</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                                        @foreach ($salfs as $f)
                                            <tr>
                                                <td class="py-2 font-bold text-orange-500">{{ $f->salf }} ج.م</td>
                                                <td class="py-2 text-gray-700 dark:text-gray-300">{{ $f->dec }}</td>
                                                <td class="py-2 text-gray-500 text-xs">{{ $f->date }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="flex items-center justify-center h-full text-gray-400 text-sm font-bold">لا يوجد بيانات</div>
                            @endif
                        </div>
                    </div>

                    <!-- Purchases -->
                    <div class="bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 rounded-2xl overflow-hidden flex flex-col">
                        <div class="p-4 border-b border-gray-200 dark:border-white/10 bg-white/50 dark:bg-transparent flex items-center gap-2">
                            <i data-lucide="shopping-cart" class="w-4 h-4 text-blue-500"></i>
                            <h4 class="font-bold text-gray-900 dark:text-white">المشتريات</h4>
                        </div>
                        <div class="p-4 flex-1 overflow-auto max-h-80">
                            @if (isset($purchas) && count($purchas) > 0)
                                <table class="w-full text-right text-sm">
                                    <thead class="text-gray-500">
                                        <tr>
                                            <th class="pb-2">المبلغ</th>
                                            <th class="pb-2">السبب</th>
                                            <th class="pb-2">التاريخ</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                                        @foreach ($purchas as $it)
                                            <tr>
                                                <td class="py-2 font-bold text-blue-500">{{ $it->mony }} ج.م</td>
                                                <td class="py-2 text-gray-700 dark:text-gray-300">{{ $it->dec }}</td>
                                                <td class="py-2 text-gray-500 text-xs">{{ $it->date }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="flex items-center justify-center h-full text-gray-400 text-sm font-bold">لا يوجد بيانات</div>
                            @endif
                        </div>
                    </div>

                    <!-- Bonuses (Hafz) -->
                    <div class="bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 rounded-2xl overflow-hidden flex flex-col">
                        <div class="p-4 border-b border-gray-200 dark:border-white/10 bg-white/50 dark:bg-transparent flex items-center gap-2">
                            <i data-lucide="gift" class="w-4 h-4 text-green-500"></i>
                            <h4 class="font-bold text-gray-900 dark:text-white">الحوافز والمكافآت</h4>
                        </div>
                        <div class="p-4 flex-1 overflow-auto max-h-80">
                            @if (isset($hafz_data) && count($hafz_data) > 0)
                                <table class="w-full text-right text-sm">
                                    <thead class="text-gray-500">
                                        <tr>
                                            <th class="pb-2">المبلغ</th>
                                            <th class="pb-2">السبب</th>
                                            <th class="pb-2">التاريخ</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                                        @foreach ($hafz_data as $h)
                                            <tr>
                                                <td class="py-2 font-bold text-green-500">{{ $h->hafz }} ج.م</td>
                                                <td class="py-2 text-gray-700 dark:text-gray-300">{{ $h->dec }}</td>
                                                <td class="py-2 text-gray-500 text-xs">{{ $h->date }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="flex items-center justify-center h-full text-gray-400 text-sm font-bold">لا يوجد بيانات</div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Map Modal -->
    <div id="mapModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="hideMapModal()"></div>
        <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-4xl rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2" id="mapModalLabel">
                    <i data-lucide="map-pin" class="w-5 h-5 text-blue-500"></i> موقع البصمة
                </h3>
                <button type="button" onclick="hideMapModal()" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="p-6">
                <div id="modalMap" class="bg-gray-100 dark:bg-[#111]"></div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end">
                <button type="button" onclick="hideMapModal()" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-sm font-bold transition-all">إغلاق الخريطة</button>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <script>
        // Vue Initialization for dynamic table columns
        const { createApp } = Vue;

        createApp({
            data() {
                return {
                    date: true,
                    checkinD: true,
                    checkoutD: true,
                    shift: true,
                    checkin: true,
                    checkout: true,
                    breakout: true,
                    breakin: true,
                    disin: true,
                    disout: true,
                    early: true,
                    free: true,
                    hafz: true,
                    hwork: true,
                    hoursot: true,
                    countot: true,
                    dis: true,
                    salf: true,
                    absent: true,
                    countabsent: true,
                };
            },
        }).mount('#app');

        // Tab Switching Logic
        function switchTab(tabId) {
            // Hide all tabs
            document.getElementById('tab1').classList.remove('block');
            document.getElementById('tab1').classList.add('hidden');
            document.getElementById('tab2').classList.remove('block');
            document.getElementById('tab2').classList.add('hidden');
            
            // Show selected tab
            document.getElementById(tabId).classList.remove('hidden');
            document.getElementById(tabId).classList.add('block');
            
            // Reset button styles
            const btn1 = document.getElementById('tab1-btn');
            const btn2 = document.getElementById('tab2-btn');
            
            [btn1, btn2].forEach(btn => {
                btn.className = 'inline-flex items-center justify-center p-4 border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 text-gray-500 dark:text-gray-400 outline-none transition-colors';
            });
            
            // Activate selected button style
            const activeBtn = document.getElementById(tabId + '-btn');
            activeBtn.className = 'inline-flex items-center justify-center p-4 border-b-2 border-sky-500 text-sky-500 rounded-t-lg active group outline-none transition-colors font-bold';
        }

        // Print Logic
        function printDiv(divId) {
            window.print();
        }

        // Map Logic
        var leafletMap = null;
        var mapMarker = null;

        function showOnMap(lat, lon, title) {
            const modal = document.getElementById('mapModal');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.querySelector('.modal-content').classList.remove('scale-95');
            
            document.getElementById('mapModalLabel').innerHTML = '<i data-lucide="map-pin" class="w-5 h-5 text-blue-500"></i> موقع البصمة: ' + title;

            // Small delay to ensure modal is visible before rendering map
            setTimeout(function() {
                if (leafletMap === null) {
                    leafletMap = L.map('modalMap').setView([lat, lon], 15);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(leafletMap);
                } else {
                    leafletMap.setView([lat, lon], 15);
                }

                if (mapMarker) {
                    leafletMap.removeLayer(mapMarker);
                }
                mapMarker = L.marker([lat, lon]).addTo(leafletMap).bindPopup(title).openPopup();

                // Fix for map tiles not loading completely inside modal
                leafletMap.invalidateSize();
            }, 300);
        }

        function hideMapModal() {
            const modal = document.getElementById('mapModal');
            modal.classList.add('opacity-0', 'pointer-events-none');
            modal.querySelector('.modal-content').classList.add('scale-95');
        }

        $(document).ready(function() {
            // Initialize Select2
            $('#employee-select').select2({
                placeholder: 'ابحث بالكتابة عن اسم الموظف...',
                allowClear: true,
                dir: "rtl",
                width: '100%'
            });
        });
    </script>
@endsection
