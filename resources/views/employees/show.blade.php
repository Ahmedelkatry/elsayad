@extends('layouts_new.app')
@section('title')
    كشف موظف | نظام إدارة الموظفين
@endsection

@section('page-header')
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}"
                    class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <a href="{{ route('employees.index') }}"
                    class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الموظفين</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">كشف موظف</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">كشف موظف: <span
                        class="text-blue-500">{{ App\Models\employees::where('id', $id)->first()->name }}</span></h1>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div x-data="{ activeTab: 'tab1' }" class="space-y-6">

        <!-- Search and Filter Bar -->
        <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-3xl p-6 shadow-sm">
            <form action="{{ route('employees.show', $id) }}" class="flex flex-col md:flex-row items-end gap-4">
                <div class="flex-1 w-full">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-2">من
                        تاريخ</label>
                    <input type="date" name="dates" value="{{ request('dates') }}"
                        class="w-full bg-gray-50 dark:bg-[#111] border border-gray-100 dark:border-white/10 text-gray-900 dark:text-white px-5 py-3 rounded-2xl outline-none focus:border-sky-500/50 transition-all text-sm font-bold">
                </div>
                <div class="flex-1 w-full">
                    <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-2">إلى
                        تاريخ</label>
                    <input type="date" name="datee" value="{{ request('datee') }}"
                        class="w-full bg-gray-50 dark:bg-[#111] border border-gray-100 dark:border-white/10 text-gray-900 dark:text-white px-5 py-3 rounded-2xl outline-none focus:border-sky-500/50 transition-all text-sm font-bold">
                </div>
                <div class="w-full md:w-auto">
                    <button type="submit"
                        class="w-full md:w-auto px-8 py-3 bg-blue-500 text-white hover:bg-blue-600 rounded-2xl text-sm font-bold shadow-lg shadow-blue-500/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i data-lucide="search" class="w-5 h-5"></i>
                        <span>بحث السجلات</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tab Navigation -->
        <div
            class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-3xl shadow-sm overflow-hidden">
            <div class="flex flex-wrap border-b border-gray-100 dark:border-white/5 overflow-x-auto custom-scrollbar">
                <button @click="activeTab = 'tab1'"
                    :class="activeTab === 'tab1' ? 'text-blue-500 border-b-2 border-blue-500 bg-blue-50/50 dark:bg-blue-500/5' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5'"
                    class="px-6 py-4 text-sm font-bold transition-all whitespace-nowrap flex items-center gap-2 flex-1 md:flex-none justify-center">
                    <i data-lucide="scissors" class="w-4 h-4"></i> الخصومات
                </button>
                <button @click="activeTab = 'tab2'"
                    :class="activeTab === 'tab2' ? 'text-orange-500 border-b-2 border-orange-500 bg-orange-50/50 dark:bg-orange-500/5' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5'"
                    class="px-6 py-4 text-sm font-bold transition-all whitespace-nowrap flex items-center gap-2 flex-1 md:flex-none justify-center">
                    <i data-lucide="banknote" class="w-4 h-4"></i> السلف
                </button>
                <button @click="activeTab = 'tab3'"
                    :class="activeTab === 'tab3' ? 'text-green-500 border-b-2 border-green-500 bg-green-50/50 dark:bg-green-500/5' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5'"
                    class="px-6 py-4 text-sm font-bold transition-all whitespace-nowrap flex items-center gap-2 flex-1 md:flex-none justify-center">
                    <i data-lucide="calendar-heart" class="w-4 h-4"></i> الإجازات
                </button>
                <button @click="activeTab = 'tab4'"
                    :class="activeTab === 'tab4' ? 'text-purple-500 border-b-2 border-purple-500 bg-purple-50/50 dark:bg-purple-500/5' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5'"
                    class="px-6 py-4 text-sm font-bold transition-all whitespace-nowrap flex items-center gap-2 flex-1 md:flex-none justify-center">
                    <i data-lucide="clock" class="w-4 h-4"></i> الأذونات
                </button>
                <button @click="activeTab = 'tab5'"
                    :class="activeTab === 'tab5' ? 'text-sky-600 dark:text-sky-400 border-b-2 border-sky-500 bg-sky-50/50 dark:bg-sky-500/10' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5'"
                    class="px-6 py-4 text-sm font-bold transition-all whitespace-nowrap flex items-center gap-2 flex-1 md:flex-none justify-center">
                    <i data-lucide="star" class="w-4 h-4"></i> الحوافز والمكافآت
                </button>
                <button @click="activeTab = 'tab6'"
                    :class="activeTab === 'tab6' ? 'text-indigo-500 border-b-2 border-indigo-500 bg-indigo-50/50 dark:bg-indigo-500/5' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5'"
                    class="px-6 py-4 text-sm font-bold transition-all whitespace-nowrap flex items-center gap-2 flex-1 md:flex-none justify-center">
                    <i data-lucide="landmark" class="w-4 h-4"></i> القروض
                </button>
                <button @click="activeTab = 'tab7'"
                    :class="activeTab === 'tab7' ? 'text-teal-500 border-b-2 border-teal-500 bg-teal-50/50 dark:bg-teal-500/5' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/5'"
                    class="px-6 py-4 text-sm font-bold transition-all whitespace-nowrap flex items-center gap-2 flex-1 md:flex-none justify-center">
                    <i data-lucide="shopping-cart" class="w-4 h-4"></i> المشتريات
                </button>
            </div>

            <!-- Tab Contents -->
            <div class="p-6">
                <!-- الخصومات -->
                <div x-show="activeTab === 'tab1'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                    class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-[#111] border-b border-gray-100 dark:border-white/5">
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">#</th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">قيمة الخصم
                                </th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">السبب</th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                            @forelse ($diss as $i => $dis)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                                    <td class="p-4 text-sm text-gray-600 dark:text-gray-400 font-bold">{{ $i + 1 }}</td>
                                    <td class="p-4 text-sm font-black text-red-500">{{ $dis->dis }}</td>
                                    <td class="p-4 text-sm text-gray-900 dark:text-gray-300">{{ $dis->dec }}</td>
                                    <td class="p-4 text-sm font-bold text-gray-600 dark:text-gray-400">{{ $dis->date }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-gray-400 font-bold">لا يوجد سجلات للفترة المحددة
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- السلف -->
                <div x-show="activeTab === 'tab2'" style="display: none;"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0" class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-[#111] border-b border-gray-100 dark:border-white/5">
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">#</th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">قيمة السلفة
                                </th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">البيان</th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                            @forelse ($salfs as $i => $salf)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                                    <td class="p-4 text-sm text-gray-600 dark:text-gray-400 font-bold">{{ $i + 1 }}</td>
                                    <td class="p-4 text-sm font-black text-orange-500">{{ $salf->salf }}</td>
                                    <td class="p-4 text-sm text-gray-900 dark:text-gray-300">{{ $salf->dec }}</td>
                                    <td class="p-4 text-sm font-bold text-gray-600 dark:text-gray-400">{{ $salf->date }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-gray-400 font-bold">لا يوجد سجلات للفترة المحددة
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- الإجازات -->
                <div x-show="activeTab === 'tab3'" style="display: none;"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0" class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-[#111] border-b border-gray-100 dark:border-white/5">
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">#</th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">تاريخ الإجازة
                                </th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">البيان / السبب
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                            @forelse ($vacs as $i => $vac)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                                    <td class="p-4 text-sm text-gray-600 dark:text-gray-400 font-bold">{{ $i + 1 }}</td>
                                    <td class="p-4 text-sm font-black text-green-500">{{ $vac->date }}</td>
                                    <td class="p-4 text-sm text-gray-900 dark:text-gray-300">{{ $vac->dec }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-8 text-center text-gray-400 font-bold">لا يوجد سجلات للفترة المحددة
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- الأذونات -->
                <div x-show="activeTab === 'tab4'" style="display: none;"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0" class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-[#111] border-b border-gray-100 dark:border-white/5">
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">#</th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">التاريخ</th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">النوع</th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">المدة (دقيقة)
                                </th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">الخصم (دقيقة)
                                </th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">قيمة الخصم
                                </th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">السبب</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                            @forelse ($plzs as $i => $plz)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                                    <td class="p-4 text-sm text-gray-600 dark:text-gray-400 font-bold">{{ $i + 1 }}</td>
                                    <td class="p-4 text-sm font-bold text-gray-600 dark:text-gray-400">{{ $plz->date }}</td>
                                    <td class="p-4 text-sm font-bold"><span
                                            class="px-3 py-1 rounded-full text-xs {{ $plz->in_out === 0 ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400' : 'bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-400' }}">{{ $plz->in_out === 0 ? 'استئذان دخول' : 'استئذان خروج' }}</span>
                                    </td>
                                    <td class="p-4 text-sm font-bold text-gray-900 dark:text-white">{{ $plz->minutes }}</td>
                                    <td class="p-4 text-sm font-bold text-red-500">{{ $plz->dis }}</td>
                                    <td class="p-4 text-sm font-black text-red-600">{{ $plz->dis_value }}</td>
                                    <td class="p-4 text-sm text-gray-900 dark:text-gray-300">{{ $plz->dec }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-gray-400 font-bold">لا يوجد سجلات للفترة المحددة
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- الحوافز والمكافآت -->
                <div x-show="activeTab === 'tab5'" style="display: none;"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0" class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-[#111] border-b border-gray-100 dark:border-white/5">
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">#</th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">قيمة الحافز
                                </th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">السبب</th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                            @forelse ($hafzs as $i => $hafz)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                                    <td class="p-4 text-sm text-gray-600 dark:text-gray-400 font-bold">{{ $i + 1 }}</td>
                                    <td class="p-4 text-sm font-black text-green-500">{{ $hafz->hafz }}</td>
                                    <td class="p-4 text-sm text-gray-900 dark:text-gray-300">{{ $hafz->dec }}</td>
                                    <td class="p-4 text-sm font-bold text-gray-600 dark:text-gray-400">{{ $hafz->date }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-gray-400 font-bold">لا يوجد سجلات للفترة المحددة
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- القروض -->
                <div x-show="activeTab === 'tab6'" style="display: none;"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0" class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-[#111] border-b border-gray-100 dark:border-white/5">
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">#</th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">إجمالي القرض
                                </th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">الخصم الشهري
                                </th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">المتبقي من
                                    القرض</th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">باقي كام شهر
                                </th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">إجمالي الشهور
                                </th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">بداية القرض
                                </th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">نهاية القرض
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                            @forelse ($loans as $i => $emp)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                                    <td class="p-4 text-sm text-gray-600 dark:text-gray-400 font-bold">{{ $i + 1 }}</td>
                                    <td class="p-4 text-sm font-black text-indigo-500">{{ $emp->mony }}</td>
                                    <td class="p-4 text-sm font-bold text-red-500">{{ $emp->mony_now }}</td>
                                    <td class="p-4 text-sm font-bold text-orange-500">{{ $emp->mony_baky }}</td>
                                    <td class="p-4 text-sm font-bold text-gray-900 dark:text-white">{{ $emp->month_baky }}</td>
                                    <td class="p-4 text-sm font-bold text-gray-500">{{ $emp->month_now }}</td>
                                    <td class="p-4 text-sm text-gray-600 dark:text-gray-400">{{ $emp->date_start }}</td>
                                    <td class="p-4 text-sm text-gray-600 dark:text-gray-400">{{ $emp->date_end }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-8 text-center text-gray-400 font-bold">لا يوجد سجلات للفترة المحددة
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- المشتريات -->
                <div x-show="activeTab === 'tab7'" style="display: none;"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0" class="overflow-x-auto">
                    <table class="w-full text-right border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-[#111] border-b border-gray-100 dark:border-white/5">
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">#</th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">المبلغ</th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">البيان</th>
                                <th class="p-4 text-[11px] font-black text-gray-500 uppercase tracking-wider">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                            @forelse ($purchases as $i => $purchas)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                                    <td class="p-4 text-sm text-gray-600 dark:text-gray-400 font-bold">{{ $i + 1 }}</td>
                                    <td class="p-4 text-sm font-black text-teal-500">{{ $purchas->mony }}</td>
                                    <td class="p-4 text-sm text-gray-900 dark:text-gray-300">{{ $purchas->dec }}</td>
                                    <td class="p-4 text-sm font-bold text-gray-600 dark:text-gray-400">{{ $purchas->date }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-gray-400 font-bold">لا يوجد سجلات للفترة المحددة
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Alpine.js is typically included in layouts_new.app, if not, add script tag here -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection