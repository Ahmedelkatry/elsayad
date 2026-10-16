@extends('layouts_new.app')
@section('title')
    تقرير خطوط سير ومواقع الموظفين | نظام الإدارة
@endsection

@section('css')
    <style>
        .pixel-card {
            background-color: #ffffff;
            border: 1px solid #f3f4f6;
        }
        .dark .pixel-card {
            background-color: #181818;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
@endsection

@section('page-header')
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <a href="{{ route('check_attendance_location') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">المواقع المباشرة</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">تقرير المواقع والسجلات</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">تقرير سجل مواقع الموظفين</h1>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- فلتر البحث -->
    <div class="pixel-card rounded-[1.5rem] p-6 mb-6 shadow-sm">
        <form action="{{ route('employee.locations_report') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label for="employee_id" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">الموظف</label>
                <select name="employee_id" id="employee_id" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    <option value="">كل الموظفين</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ $employeeId == $emp->id ? 'selected' : '' }}>
                            {{ $emp->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="from_date" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">من تاريخ</label>
                <input type="date" name="from_date" id="from_date" value="{{ $fromDate }}" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors font-mono">
            </div>

            <div>
                <label for="to_date" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">إلى تاريخ</label>
                <input type="date" name="to_date" id="to_date" value="{{ $toDate }}" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors font-mono">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-sky-500 hover:bg-sky-600 text-white font-bold text-xs px-6 py-3 rounded-xl shadow-lg shadow-sky-500/20 transition-all active:scale-95 text-center">
                    بحث
                </button>
                <a href="{{ route('employee.locations_report') }}" class="bg-gray-100 hover:bg-gray-200 dark:bg-white/5 dark:hover:bg-white/10 text-gray-600 dark:text-gray-300 font-bold text-xs px-4 py-3 rounded-xl transition-all text-center flex items-center justify-center">
                    إعادة ضبط
                </a>
            </div>
        </form>
    </div>

    <!-- جدول البيانات -->
    <div class="pixel-card rounded-[1.5rem] overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-white/5 border-b border-gray-100 dark:border-white/5">
                        <th class="p-4 text-xs font-bold text-gray-500 dark:text-gray-400">الموظف</th>
                        <th class="p-4 text-xs font-bold text-gray-500 dark:text-gray-400">التاريخ والوقت</th>
                        <th class="p-4 text-xs font-bold text-gray-500 dark:text-gray-400">خط العرض (Lat)</th>
                        <th class="p-4 text-xs font-bold text-gray-500 dark:text-gray-400">خط الطول (Lon)</th>
                        <th class="p-4 text-xs font-bold text-gray-500 dark:text-gray-400 text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                    @forelse($locations as $loc)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-sky-500/10 text-sky-600 flex items-center justify-center font-bold text-xs">
                                        {{ mb_substr($loc->employees->name ?? 'م', 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="text-xs font-bold text-gray-900 dark:text-white block">{{ $loc->employees->name ?? 'غير معروف' }}</span>
                                        <span class="text-[10px] text-gray-400 font-mono">ID: {{ $loc->employees_id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-xs text-gray-600 dark:text-gray-300 font-mono">
                                {{ $loc->recorded_at ? $loc->recorded_at->format('Y-m-d h:i A') : '-' }}
                            </td>
                            <td class="p-4 text-xs text-gray-500 dark:text-gray-400 font-mono">
                                {{ round($loc->latitude, 6) }}
                            </td>
                            <td class="p-4 text-xs text-gray-500 dark:text-gray-400 font-mono">
                                {{ round($loc->longitude, 6) }}
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- رابط قوقل ماب -->
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $loc->latitude }},{{ $loc->longitude }}" 
                                       target="_blank" 
                                       title="عرض على خرائط جوجل"
                                       class="p-2 bg-blue-500/10 text-blue-600 hover:bg-blue-500 hover:text-white rounded-lg transition-all">
                                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                                    </a>
                                    <!-- رابط تفاصيل اليوم والمسار -->
                                    @if($loc->recorded_at)
                                        <a href="{{ route('employee.location_history', ['employee_id' => $loc->employees_id, 'date' => $loc->recorded_at->format('Y-m-d')]) }}" 
                                           title="عرض مسار تحركات اليوم بالكامل"
                                           class="p-2 bg-sky-500/10 text-sky-600 hover:bg-sky-500 hover:text-white rounded-lg transition-all">
                                            <i data-lucide="navigation" class="w-4 h-4"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center text-gray-400 text-xs">
                                <i data-lucide="map-pin-off" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                                لا توجد سجلات مواقع تطابق الفلاتر المحددة.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- الترقيم -->
        @if($locations->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-white/[0.01]">
                {!! $locations->appends(request()->query())->links() !!}
            </div>
        @endif
    </div>
@endsection
