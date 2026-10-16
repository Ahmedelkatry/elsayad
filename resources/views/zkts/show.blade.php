@extends('layouts_new.app')
@section('title')
    تفاصيل سجل الحضور | تقرير الموظفين
@endsection

@section('page-header')
<div class="mb-8 flex items-center justify-between gap-4">
    <div>
        <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
            <a href="{{ route('dashboard') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
            <i data-lucide="chevron-left" class="w-3 h-3"></i>
            <a href="{{ route('zkts.index') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">تقرير الموظفين</a>
            <i data-lucide="chevron-left" class="w-3 h-3"></i>
            <span class="text-sky-600 dark:text-sky-500">تفاصيل السجل</span>
        </nav>
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">تفاصيل سجل الحضور</h1>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('zkts.edit', $zkt->id) }}"
            class="flex items-center gap-2 px-4 py-2.5 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-sm font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95">
            <i data-lucide="pencil" class="w-4 h-4"></i>
            تعديل
        </a>
        <a href="{{ route('zkts.index') }}"
            class="flex items-center gap-2 px-4 py-2.5 bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-white/10 rounded-xl text-sm font-bold transition-all border border-gray-200 dark:border-white/5">
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
            رجوع
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- بطاقة هوية الموظف --}}
    <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-8 shadow-sm">
        <div class="flex items-center gap-6 mb-8">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center text-black text-2xl font-black shadow-lg shadow-sky-500/30 shrink-0">
                {{ mb_substr($zkt->name ?? '؟', 0, 1) }}
            </div>
            <div>
                <h2 class="text-xl font-black text-gray-900 dark:text-white mb-1">{{ $zkt->name }}</h2>
                <p class="text-sm text-gray-500">رقم الموظف: <span class="font-bold text-sky-600 dark:text-sky-500">{{ $zkt->numid }}</span></p>
            </div>
            <div class="mr-auto">
                @php
                    $statusClass = match($zkt->absent) {
                        'حاضر' => 'bg-green-100 dark:bg-green-500/10 text-green-700 dark:text-green-400 border-green-200 dark:border-green-500/20',
                        'غائب' => 'bg-red-100 dark:bg-red-500/10 text-red-700 dark:text-red-400 border-red-200 dark:border-red-500/20',
                        default => 'bg-orange-100 dark:bg-orange-500/10 text-orange-700 dark:text-orange-400 border-orange-200 dark:border-orange-500/20',
                    };
                @endphp
                <span class="{{ $statusClass }} px-4 py-2 rounded-full text-sm font-black border">{{ $zkt->absent ?? 'غير محدد' }}</span>
            </div>
        </div>

        {{-- شبكة المعلومات --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-gray-50 dark:bg-[#111] rounded-2xl p-4 text-center">
                <p class="text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-2">التاريخ</p>
                <p class="text-base font-black text-gray-900 dark:text-white">{{ $zkt->date }}</p>
                <p class="text-[10px] text-gray-400 mt-1">{{ \Carbon\Carbon::parse($zkt->date)->locale('ar')->dayName }}</p>
            </div>

            <div class="bg-green-50 dark:bg-green-500/5 border border-green-100 dark:border-green-500/10 rounded-2xl p-4 text-center">
                <p class="text-[10px] uppercase tracking-widest font-bold text-green-500 mb-2">وقت الدخول</p>
                <p class="text-xl font-black text-green-700 dark:text-green-400">
                    {{ $zkt->in ? \Carbon\Carbon::parse($zkt->in)->format('H:i') : '--:--' }}
                </p>
                @if($zkt->in)
                    <p class="text-[10px] text-green-500 mt-1">تم التسجيل</p>
                @else
                    <p class="text-[10px] text-gray-400 mt-1">لا يوجد</p>
                @endif
            </div>

            <div class="bg-red-50 dark:bg-red-500/5 border border-red-100 dark:border-red-500/10 rounded-2xl p-4 text-center">
                <p class="text-[10px] uppercase tracking-widest font-bold text-red-500 mb-2">وقت الخروج</p>
                <p class="text-xl font-black text-red-700 dark:text-red-400">
                    {{ $zkt->out ? \Carbon\Carbon::parse($zkt->out)->format('H:i') : '--:--' }}
                </p>
                @if($zkt->out)
                    <p class="text-[10px] text-red-500 mt-1">تم التسجيل</p>
                @else
                    <p class="text-[10px] text-gray-400 mt-1">لا يوجد</p>
                @endif
            </div>

            @if($zkt->in && $zkt->out)
            <div class="bg-blue-50 dark:bg-blue-500/5 border border-blue-100 dark:border-blue-500/10 rounded-2xl p-4 text-center">
                <p class="text-[10px] uppercase tracking-widest font-bold text-blue-500 mb-2">ساعات العمل</p>
                @php
                    $in = \Carbon\Carbon::parse($zkt->date . ' ' . $zkt->in);
                    $out = \Carbon\Carbon::parse($zkt->date . ' ' . $zkt->out);
                    if ($out->lt($in)) $out->addDay();
                    $diffSeconds = $in->diffInSeconds($out);
                    $hours = floor($diffSeconds / 3600);
                    $mins  = floor(($diffSeconds % 3600) / 60);
                @endphp
                <p class="text-xl font-black text-blue-700 dark:text-blue-400">{{ sprintf('%02d:%02d', $hours, $mins) }}</p>
                <p class="text-[10px] text-blue-500 mt-1">ساعة:دقيقة</p>
            </div>
            @else
            <div class="bg-gray-50 dark:bg-[#111] rounded-2xl p-4 text-center">
                <p class="text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-2">ساعات العمل</p>
                <p class="text-xl font-black text-gray-400">--:--</p>
            </div>
            @endif
        </div>

        {{-- الاستراحة --}}
        @if($zkt->break_out || $zkt->break_in)
        <div class="mt-6 pt-6 border-t border-gray-100 dark:border-white/5">
            <h4 class="text-sm font-black text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">
                <i data-lucide="coffee" class="w-4 h-4 text-orange-500"></i>
                بيانات الاستراحة
            </h4>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-orange-50 dark:bg-orange-500/5 border border-orange-100 dark:border-orange-500/10 rounded-2xl p-4 text-center">
                    <p class="text-[10px] uppercase tracking-widest font-bold text-orange-500 mb-2">خروج الاستراحة</p>
                    <p class="text-lg font-black text-orange-700 dark:text-orange-400">
                        {{ $zkt->break_out ? \Carbon\Carbon::parse($zkt->break_out)->format('H:i') : '--:--' }}
                    </p>
                </div>
                <div class="bg-orange-50 dark:bg-orange-500/5 border border-orange-100 dark:border-orange-500/10 rounded-2xl p-4 text-center">
                    <p class="text-[10px] uppercase tracking-widest font-bold text-orange-500 mb-2">دخول الاستراحة</p>
                    <p class="text-lg font-black text-orange-700 dark:text-orange-400">
                        {{ $zkt->break_in ? \Carbon\Carbon::parse($zkt->break_in)->format('H:i') : '--:--' }}
                    </p>
                </div>
            </div>
        </div>
        @endif

        {{-- بيانات الموقع --}}
        @if($zkt->latitude_in || $zkt->latitude_out)
        <div class="mt-6 pt-6 border-t border-gray-100 dark:border-white/5">
            <h4 class="text-sm font-black text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">
                <i data-lucide="map-pin" class="w-4 h-4 text-blue-500"></i>
                بيانات الموقع الجغرافي
            </h4>
            <div class="grid grid-cols-2 gap-4 text-xs">
                @if($zkt->latitude_in && $zkt->longitude_in)
                <div class="bg-blue-50 dark:bg-blue-500/5 border border-blue-100 dark:border-blue-500/10 rounded-xl p-4">
                    <p class="font-bold text-blue-600 mb-1">موقع الدخول</p>
                    <p class="text-gray-500">{{ $zkt->latitude_in }}, {{ $zkt->longitude_in }}</p>
                </div>
                @endif
                @if($zkt->latitude_out && $zkt->longitude_out)
                <div class="bg-blue-50 dark:bg-blue-500/5 border border-blue-100 dark:border-blue-500/10 rounded-xl p-4">
                    <p class="font-bold text-blue-600 mb-1">موقع الخروج</p>
                    <p class="text-gray-500">{{ $zkt->latitude_out }}, {{ $zkt->longitude_out }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- تاريخ الإنشاء --}}
        <div class="mt-6 pt-6 border-t border-gray-100 dark:border-white/5 flex items-center justify-between text-xs text-gray-400">
            <span>تاريخ الإنشاء: {{ $zkt->created_at ? $zkt->created_at->format('Y-m-d H:i') : '--' }}</span>
            <span>آخر تعديل: {{ $zkt->updated_at ? $zkt->updated_at->format('Y-m-d H:i') : '--' }}</span>
        </div>
    </div>

    {{-- زر الحذف --}}
    <div class="flex justify-end">
        <form action="{{ route('zkts.destroy', $zkt->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا السجل؟')">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="flex items-center gap-2 px-5 py-2.5 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 rounded-xl text-sm font-bold transition-all border border-red-200 dark:border-red-500/20">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
                حذف السجل
            </button>
        </form>
    </div>
</div>
@endsection
