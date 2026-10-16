@extends('layouts_new.app')
@section('title')
    تقرير البصمة | نظام إدارة الموظفين
@endsection

@section('css')
    <style>
        .custom-checkbox {
            appearance: none;
            background-color: #fff;
            margin: 0;
            font: inherit;
            color: currentColor;
            width: 1.15em;
            height: 1.15em;
            border: 0.15em solid currentColor;
            border-radius: 0.2em;
            display: grid;
            place-content: center;
        }
        .dark .custom-checkbox {
            background-color: #111;
        }
        .custom-checkbox::before {
            content: "";
            width: 0.65em;
            height: 0.65em;
            transform: scale(0);
            transition: 120ms transform ease-in-out;
            box-shadow: inset 1em 1em var(--tw-ring-color);
            background-color: CanvasText;
            transform-origin: bottom left;
            clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
        }
        .custom-checkbox:checked::before {
            transform: scale(1);
        }

        @media print {
            body { visibility: hidden; }
            #printt { visibility: visible; position: absolute; left: 0; top: 0; width: 100%; direction: rtl;}
            #printt * { color: #000 !important; background: transparent !important; }
            .no-print { display: none !important; }
        }
    </style>
@endsection

@section('page-header')
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <a href="{{ route('employees.index') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الموظفين</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">تقرير البصمة</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">تقرير البصمة: {{ $emp_name ?? '' }}</h1>
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
    <div id="app">
        <!-- قسم البحث والتصفية -->
        <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-6 shadow-sm mb-6">
            <h4 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2 mb-4 border-b border-gray-100 dark:border-white/5 pb-3">
                <i data-lucide="search" class="w-5 h-5 text-blue-500"></i> بحث وتصفية التقرير
            </h4>
            
            <form class="date" action="{{ route('in_out') }}">
                <div class="flex flex-col md:flex-row items-center gap-4 mb-6">
                    <div class="flex-1 w-full">
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">من تاريخ</label>
                        <input type="date" name="searchs" value="{{ request('searchs') }}" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>
                    <div class="flex-1 w-full">
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">إلى تاريخ</label>
                        <input type="date" name="searche" value="{{ request('searche') }}" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>
                    <div class="w-full md:w-auto md:mt-6">
                        <button type="submit" class="w-full md:w-auto px-6 py-3 bg-blue-500 text-white hover:bg-blue-600 rounded-xl text-sm font-bold shadow-lg shadow-blue-500/10 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <i data-lucide="search" class="w-4 h-4"></i> بحث
                        </button>
                    </div>
                </div>
            </form>

            <h5 class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-3 border-t border-gray-100 dark:border-white/5 pt-4">الأعمدة المعروضة:</h5>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3">
                <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg transition-colors">
                    <input type="checkbox" v-model="date" class="custom-checkbox text-sky-500 focus:ring-sky-500">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">التاريخ</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg transition-colors">
                    <input type="checkbox" v-model="checkinD" class="custom-checkbox text-sky-500 focus:ring-sky-500">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">الدخول الملزم</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg transition-colors">
                    <input type="checkbox" v-model="checkoutD" class="custom-checkbox text-sky-500 focus:ring-sky-500">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">الخروج الملزم</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg transition-colors">
                    <input type="checkbox" v-model="shift" class="custom-checkbox text-sky-500 focus:ring-sky-500">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">الشيفت</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg transition-colors">
                    <input type="checkbox" v-model="checkin" class="custom-checkbox text-sky-500 focus:ring-sky-500">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">دخول</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg transition-colors">
                    <input type="checkbox" v-model="checkout" class="custom-checkbox text-sky-500 focus:ring-sky-500">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">خروج</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg transition-colors">
                    <input type="checkbox" v-model="breakout" class="custom-checkbox text-sky-500 focus:ring-sky-500">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">خروج استراحة</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg transition-colors">
                    <input type="checkbox" v-model="breakin" class="custom-checkbox text-sky-500 focus:ring-sky-500">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">دخول استراحة</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg transition-colors">
                    <input type="checkbox" v-model="disin" class="custom-checkbox text-sky-500 focus:ring-sky-500">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">خصم دخول</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg transition-colors">
                    <input type="checkbox" v-model="disout" class="custom-checkbox text-sky-500 focus:ring-sky-500">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">خصم خروج</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg transition-colors">
                    <input type="checkbox" v-model="early" class="custom-checkbox text-sky-500 focus:ring-sky-500">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">تأخير</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg transition-colors">
                    <input type="checkbox" v-model="free" class="custom-checkbox text-sky-500 focus:ring-sky-500">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">إجازة رسمية</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg transition-colors">
                    <input type="checkbox" v-model="hafz" class="custom-checkbox text-sky-500 focus:ring-sky-500">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">حافز</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg transition-colors">
                    <input type="checkbox" v-model="hwork" class="custom-checkbox text-sky-500 focus:ring-sky-500">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">عدد ساعات العمل</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg transition-colors">
                    <input type="checkbox" v-model="hoursot" class="custom-checkbox text-sky-500 focus:ring-sky-500">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">ساعات OT</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg transition-colors">
                    <input type="checkbox" v-model="countot" class="custom-checkbox text-sky-500 focus:ring-sky-500">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">حساب OT</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg transition-colors">
                    <input type="checkbox" v-model="dis" class="custom-checkbox text-sky-500 focus:ring-sky-500">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">الخصم</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg transition-colors">
                    <input type="checkbox" v-model="salf" class="custom-checkbox text-sky-500 focus:ring-sky-500">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">سلفة</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg transition-colors">
                    <input type="checkbox" v-model="absent" class="custom-checkbox text-sky-500 focus:ring-sky-500">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">غياب</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg transition-colors">
                    <input type="checkbox" v-model="countabsent" class="custom-checkbox text-sky-500 focus:ring-sky-500">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">حساب الغياب</span>
                </label>
            </div>
        </div>

        <!-- الطباعة والجدول -->
        <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-6 shadow-sm">
            <div class="flex justify-between items-center mb-6 no-print">
                <h4 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="file-text" class="w-6 h-6 text-sky-500"></i> التقرير المفصل
                </h4>
                <button onclick="printDiv('printt')" class="px-5 py-2.5 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-xs font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="printer" class="w-4 h-4"></i> طباعة التقرير
                </button>
            </div>

            <div id="printt">
                <div class="mb-6 hidden print:block border-b-2 border-gray-900 pb-4">
                    <h2 class="text-2xl font-black text-center mb-2">تقرير البصمة للموظف</h2>
                    <h3 class="text-xl font-bold text-center">الاسم: {{ $emp_name }}</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-right border-collapse" id="mytable">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-[#111] border-y border-gray-100 dark:border-white/5">
                                <th v-show="date" class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400 whitespace-nowrap">التاريخ</th>
                                <th v-show="checkinD" class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400 whitespace-nowrap">الدخول الملزم</th>
                                <th v-show="checkoutD" class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400 whitespace-nowrap">الخروج الملزم</th>
                                <th v-show="shift" class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400 whitespace-nowrap">الشيفت</th>
                                <th v-show="checkin" class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400 whitespace-nowrap">دخول</th>
                                <th v-show="checkout" class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400 whitespace-nowrap">خروج</th>
                                <th v-show="breakout" class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400 whitespace-nowrap">خروج استراحة</th>
                                <th v-show="breakin" class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400 whitespace-nowrap">دخول استراحة</th>
                                <th v-show="disin" class="py-3 px-2 text-xs font-bold text-red-500 whitespace-nowrap">خصم دخول</th>
                                <th v-show="disout" class="py-3 px-2 text-xs font-bold text-red-500 whitespace-nowrap">خصم خروج</th>
                                <th v-show="early" class="py-3 px-2 text-xs font-bold text-red-500 whitespace-nowrap">تأخير</th>
                                <th v-show="free" class="py-3 px-2 text-xs font-bold text-green-500 whitespace-nowrap">إجازة رسمية</th>
                                <th v-show="hafz" class="py-3 px-2 text-xs font-bold text-green-500 whitespace-nowrap">حافز</th>
                                <th v-show="hwork" class="py-3 px-2 text-xs font-bold text-blue-500 whitespace-nowrap">عدد ساعات العمل</th>
                                <th v-show="hoursot" class="py-3 px-2 text-xs font-bold text-purple-500 whitespace-nowrap">ساعات OT</th>
                                <th v-show="countot" class="py-3 px-2 text-xs font-bold text-purple-500 whitespace-nowrap">حساب OT</th>
                                <th v-show="dis" class="py-3 px-2 text-xs font-bold text-red-500 whitespace-nowrap">الخصم</th>
                                <th v-show="salf" class="py-3 px-2 text-xs font-bold text-orange-500 whitespace-nowrap">سلفة</th>
                                <th v-show="absent" class="py-3 px-2 text-xs font-bold text-red-500 whitespace-nowrap">الغياب</th>
                                <th v-show="countabsent" class="py-3 px-2 text-xs font-bold text-red-500 whitespace-nowrap">حساب الغياب</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $sort = $cos->sortBy('date');
                            @endphp
                            @forelse ($sort as $count)
                                <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                    <td v-show="date" class="py-2 px-2 text-[11px] font-bold text-gray-900 dark:text-white whitespace-nowrap">
                                        {{ $count->date }} <br> <span class="text-[10px] text-gray-500">{{ Carbon\Carbon::parse($count->date)->locale('ar')->dayName }}</span>
                                    </td>
                                    <td v-show="checkinD" class="py-2 px-2 text-xs text-gray-700 dark:text-gray-300">{{ $count->in_duty }}</td>
                                    <td v-show="checkoutD" class="py-2 px-2 text-xs text-gray-700 dark:text-gray-300">{{ $count->out_duty }}</td>
                                    <td v-show="shift" class="py-2 px-2 text-xs text-gray-700 dark:text-gray-300">{{ $count->shift }}</td>
                                    <td v-show="checkin" class="py-2 px-2 text-xs font-bold text-green-600 dark:text-green-500">{{ $count->in }}</td>
                                    <td v-show="checkout" class="py-2 px-2 text-xs font-bold text-blue-600 dark:text-blue-500">{{ $count->out }}</td>
                                    <td v-show="breakout" class="py-2 px-2 text-xs text-gray-500">{{ $count->break_out }}</td>
                                    <td v-show="breakin" class="py-2 px-2 text-xs text-gray-500">{{ $count->break_in }}</td>
                                    <td v-show="disin" class="py-2 px-2 text-xs font-bold text-red-500">{{ round($count->dis, 2) }}</td>
                                    <td v-show="disout" class="py-2 px-2 text-xs font-bold text-red-500">{{ round($count->disout, 2) }}</td>
                                    <td v-show="early" class="py-2 px-2 text-xs font-bold text-red-500">{{ $count->early }}</td>
                                    <td v-show="free" class="py-2 px-2 text-xs font-bold text-green-500">{{ $count->friday == 1 ? 'حاضر' : '' }}</td>

                                    @php $hafzc=0; @endphp
                                    @if (App\models\hafz::where('employees_id', $id_emp)->where('date', $count->date)->first('hafz') === null)
                                        <td v-show="hafz" class="py-2 px-2 text-xs text-gray-500">0</td>
                                    @else
                                        <td v-show="hafz" class="py-2 px-2 text-xs font-bold text-green-500">
                                            {{ $hafzc = App\models\hafz::where('employees_id', $id_emp)->where('date', $count->date)->first()->hafz }}
                                        </td>
                                    @endif

                                    <td v-show="hwork" class="py-2 px-2 text-xs font-bold text-blue-500">{{ Carbon\Carbon::parse($count->dayh)->format('H:i:s') }}</td>
                                    <td v-show="hoursot" class="py-2 px-2 text-xs font-bold text-purple-500">{{ Carbon\Carbon::parse($count->othours)->format('H:i:s') }}</td>
                                    
                                    <td v-show="countot" class="py-2 px-2 text-xs font-bold text-purple-500">
                                        @php
                                            $shift_ot = App\models\shift::where('id', $count->shift_id)->first()->ot ?? 0;
                                            $emp_counth = App\models\employees::where('num', $count->numid)->first()->counth ?? 0;
                                            $otmin = round(((($shift_ot * $count->othours) / 60) * $emp_counth) / 60, 2);
                                        @endphp
                                        {{ $otmin }}
                                    </td>

                                    @php $disc=0; @endphp
                                    @if (App\models\disemp::where('employees_id', $id_emp)->where('date', $count->date)->first('dis') === null)
                                        <td v-show="dis" class="py-2 px-2 text-xs text-gray-500">0</td>
                                    @else
                                        <td v-show="dis" class="py-2 px-2 text-xs font-bold text-red-500">
                                            {{ $disc = App\models\disemp::where('employees_id', $id_emp)->where('date', $count->date)->sum('dis') }}
                                        </td>
                                    @endif

                                    @php $salfc=0; @endphp
                                    @if (App\models\salf::where('employees_id', $id_emp)->where('date', $count->date)->first('salf') === null)
                                        <td v-show="salf" class="py-2 px-2 text-xs text-gray-500">0</td>
                                    @else
                                        <td v-show="salf" class="py-2 px-2 text-xs font-bold text-orange-500">
                                            {{ $salfc = App\models\salf::where('employees_id', $id_emp)->where('date', $count->date)->sum('salf') }}
                                        </td>
                                    @endif

                                    <td v-show="absent" class="py-2 px-2 text-xs font-bold text-red-500">{{ $count->absent == 1 ? 'غياب' : '' }}</td>
                                    
                                    <td v-show="countabsent" class="py-2 px-2 text-xs font-bold text-red-500">
                                        @php
                                            $emp_countday = App\models\employees::where('num', $count->numid)->first()->countday ?? 0;
                                            $shift_absent = App\models\shift::where('id', $count->shift_id)->first()->absent ?? 0;
                                            $abseent = round($count->absent * $emp_countday * $shift_absent, 2);
                                        @endphp
                                        {{ number_format($count->absent_dis, 2) }}
                                        {{-- {{ $abseent }} --}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="20" class="py-4 text-center text-sm font-bold text-gray-500">لا يوجد بيانات لهذه الفترة</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- الإجماليات -->
                <div class="mt-8 pt-6 border-t-2 border-gray-200 dark:border-white/10">
                    <h3 class="text-xl font-black text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                        <i data-lucide="calculator" class="w-6 h-6 text-sky-500"></i> الخلاصة الإجمالية 
                        <span class="text-sm font-bold text-gray-500 mr-2 border-r border-gray-300 dark:border-white/10 pr-2">للموظف: {{ $emp_name }}</span>
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- الخصومات -->
                        <div class="bg-red-50 dark:bg-red-500/5 rounded-xl border border-red-100 dark:border-red-500/10 p-4">
                            <h4 class="text-xs font-bold text-red-800 dark:text-red-400 mb-3 border-b border-red-200 dark:border-red-500/20 pb-2">الخصومات والتأخير</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between text-xs"><span class="text-gray-600 dark:text-gray-400">خصم دخول:</span><strong class="text-red-600 dark:text-red-400">{{ round($disin ?? 0, 2) }}</strong></div>
                                <div class="flex justify-between text-xs"><span class="text-gray-600 dark:text-gray-400">خصم خروج:</span><strong class="text-red-600 dark:text-red-400">{{ round($disout ?? 0, 2) }}</strong></div>
                                <div class="flex justify-between text-xs"><span class="text-gray-600 dark:text-gray-400">إجمالي التأخير:</span><strong class="text-red-600 dark:text-red-400">{{ $early_new ?? 0 }}</strong></div>
                                <div class="flex justify-between text-xs"><span class="text-gray-600 dark:text-gray-400">خصم خارج البصمة:</span><strong class="text-red-600 dark:text-red-400">{{ $disdis ?? 0 }}</strong></div>
                                <div class="flex justify-between text-xs"><span class="text-gray-600 dark:text-gray-400">عدد أيام الغياب:</span><strong class="text-red-600 dark:text-red-400">{{ $absent ?? 0 }}</strong></div>
                                <div class="flex justify-between text-xs"><span class="text-gray-600 dark:text-gray-400">خصم الغياب:</span><strong class="text-red-600 dark:text-red-400">{{ number_format($disabsent ?? 0, 2) }}</strong></div>
                                <div class="flex justify-between text-xs pt-2 border-t border-red-200 dark:border-red-500/20"><span class="font-bold text-red-800 dark:text-red-400">إجمالي الخصومات:</span><strong class="text-red-600 dark:text-red-400">{{ number_format($afterdis ?? 0, 2) }}</strong></div>
                            </div>
                        </div>

                        <!-- السلف والمشتريات -->
                        <div class="bg-orange-50 dark:bg-orange-500/5 rounded-xl border border-orange-100 dark:border-orange-500/10 p-4">
                            <h4 class="text-xs font-bold text-orange-800 dark:text-orange-400 mb-3 border-b border-orange-200 dark:border-orange-500/20 pb-2">السلف والمستقطعات</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between text-xs"><span class="text-gray-600 dark:text-gray-400">خصم سلف:</span><strong class="text-orange-600 dark:text-orange-400">{{ number_format($dissalf ?? 0, 2) }}</strong></div>
                                <div class="flex justify-between text-xs"><span class="text-gray-600 dark:text-gray-400">القرض:</span><strong class="text-orange-600 dark:text-orange-400">{{ number_format($loan ?? 0, 2) }}</strong></div>
                                <div class="flex justify-between text-xs"><span class="text-gray-600 dark:text-gray-400">خصم المواصلات:</span><strong class="text-orange-600 dark:text-orange-400">{{ number_format($dis_trans ?? 0) }}</strong></div>
                                <div class="flex justify-between text-xs"><span class="text-gray-600 dark:text-gray-400">التأمينات:</span><strong class="text-orange-600 dark:text-orange-400">{{ number_format($taminat ?? 0) }}</strong></div>
                                <div class="flex justify-between text-xs"><span class="text-gray-600 dark:text-gray-400">المشتريات:</span><strong class="text-orange-600 dark:text-orange-400">{{ number_format($purchases ?? 0) }}</strong></div>
                            </div>
                        </div>

                        <!-- الإضافات والمكافآت -->
                        <div class="bg-blue-50 dark:bg-blue-500/5 rounded-xl border border-blue-100 dark:border-blue-500/10 p-4">
                            <h4 class="text-xs font-bold text-blue-800 dark:text-blue-400 mb-3 border-b border-blue-200 dark:border-blue-500/20 pb-2">الإضافات والمكافآت</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between text-xs"><span class="text-gray-600 dark:text-gray-400">بدل المواصلات:</span><strong class="text-blue-600 dark:text-blue-400">{{ number_format($badel_trans ?? 0) }}</strong></div>
                                <div class="flex justify-between text-xs"><span class="text-gray-600 dark:text-gray-400">بدل انتظام:</span><strong class="text-blue-600 dark:text-blue-400">{{ round($badel_sys ?? 0, 2) }}</strong></div>
                                <div class="flex justify-between text-xs"><span class="text-gray-600 dark:text-gray-400">الحوافز:</span><strong class="text-blue-600 dark:text-blue-400">{{ number_format($hafzs ?? 0, 2) }}</strong></div>
                                <div class="flex justify-between text-xs"><span class="text-gray-600 dark:text-gray-400">عدد ساعات OT:</span><strong class="text-purple-600 dark:text-purple-400">{{ Carbon\Carbon::parse($othours ?? '00:00:00')->format('H:i:s') }}</strong></div>
                                <div class="flex justify-between text-xs"><span class="text-gray-600 dark:text-gray-400">حساب OT:</span><strong class="text-purple-600 dark:text-purple-400">{{ number_format($overtime ?? 0, 2) }}</strong></div>
                                <div class="flex justify-between text-xs"><span class="text-gray-600 dark:text-gray-400">عيدية العيد:</span><strong class="text-blue-600 dark:text-blue-400">{{ round($hafz_eid ?? 0, 2) }}</strong></div>
                                <div class="flex justify-between text-xs"><span class="text-gray-600 dark:text-gray-400">حضور إجازة رسمية:</span><strong class="text-green-600 dark:text-green-400">{{ round($friday ?? 0) }}</strong></div>
                            </div>
                        </div>

                        <!-- الخلاصة النهائية -->
                        <div class="bg-green-50 dark:bg-green-500/5 rounded-xl border border-green-100 dark:border-green-500/10 p-4">
                            <h4 class="text-xs font-bold text-green-800 dark:text-green-400 mb-3 border-b border-green-200 dark:border-green-500/20 pb-2">الخلاصة المالية والزمنية</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between text-xs"><span class="text-gray-600 dark:text-gray-400">المرتب الأساسي:</span><strong class="text-gray-900 dark:text-white">{{ $empkabd ?? 0 }}</strong></div>
                                <div class="flex justify-between text-xs"><span class="text-gray-600 dark:text-gray-400">قيمة اليوم:</span><strong class="text-gray-900 dark:text-white">{{ round($countday ?? 0, 2) }}</strong></div>
                                <div class="flex justify-between text-xs"><span class="text-gray-600 dark:text-gray-400">أيام الشهر المحددة:</span><strong class="text-gray-900 dark:text-white">{{ $sysemp ?? 0 }}</strong></div>
                                <div class="flex justify-between text-xs"><span class="text-gray-600 dark:text-gray-400">ساعات العمل:</span><strong class="text-green-600 dark:text-green-400">{{ Carbon\Carbon::parse(($dayss ?? 0) < 0 ? ($dayss ?? 0) + 24 : ($dayss ?? 0))->format('H:i:s') }}</strong></div>
                                <div class="flex justify-between text-xs"><span class="text-gray-600 dark:text-gray-400">المرتب بعد OT:</span><strong class="text-green-600 dark:text-green-400">{{ number_format($afterot ?? 0, 2) }}</strong></div>
                                <div class="flex justify-between text-sm mt-3 pt-3 border-t border-green-200 dark:border-green-500/20"><span class="font-black text-green-800 dark:text-green-400">المرتب المستحق ({{ $bet ?? 0 }} يوم):</span><strong class="text-green-600 dark:text-green-400 text-lg">{{ number_format($salary_days ?? 0, 2) }}</strong></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
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
        }).mount('#app')

        function printDiv(divId) {
            window.print();
        }
    </script>
@endsection
