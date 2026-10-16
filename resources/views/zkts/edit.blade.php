@extends('layouts_new.app')
@section('title')
    تعديل سجل حضور | تقرير الموظفين
@endsection

@section('css')
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('page-header')
<div class="mb-8">
    <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
        <a href="{{ route('dashboard') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
        <i data-lucide="chevron-left" class="w-3 h-3"></i>
        <a href="{{ route('zkts.index') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">تقرير الموظفين</a>
        <i data-lucide="chevron-left" class="w-3 h-3"></i>
        <span class="text-sky-600 dark:text-sky-500">تعديل سجل</span>
    </nav>
    <div class="flex items-center gap-3">
        <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
        <h1 class="text-2xl font-black text-gray-900 dark:text-white">تعديل سجل حضور</h1>
    </div>
</div>

@if($errors->any())
    <div class="flex items-start gap-3 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-700 dark:text-red-400 px-5 py-4 rounded-2xl mb-6 text-sm font-bold">
        <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
        <ul class="space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif
@endsection

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- بطاقة معلومات الموظف --}}
    <div class="bg-sky-50 dark:bg-sky-500/5 border border-sky-200 dark:border-sky-500/20 rounded-2xl p-5 mb-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-sky-200 dark:bg-sky-500/20 flex items-center justify-center text-sky-700 dark:text-sky-400 text-lg font-black shrink-0">
            {{ mb_substr($zkt->name ?? '؟', 0, 1) }}
        </div>
        <div>
            <p class="text-sm font-black text-gray-900 dark:text-white">{{ $zkt->name }}</p>
            <p class="text-xs text-gray-500">رقم الموظف: <span class="font-bold text-sky-600 dark:text-sky-500">{{ $zkt->numid }}</span></p>
            <p class="text-xs text-gray-500">التاريخ الحالي: <span class="font-bold">{{ $zkt->date }}</span></p>
        </div>
        <div class="mr-auto">
            @php
                $statusClass = match($zkt->absent) {
                    'حاضر' => 'bg-green-100 dark:bg-green-500/10 text-green-700 dark:text-green-400 border-green-200 dark:border-green-500/20',
                    'غائب' => 'bg-red-100 dark:bg-red-500/10 text-red-700 dark:text-red-400 border-red-200 dark:border-red-500/20',
                    default => 'bg-orange-100 dark:bg-orange-500/10 text-orange-700 dark:text-orange-400 border-orange-200 dark:border-orange-500/20',
                };
            @endphp
            <span class="{{ $statusClass }} px-3 py-1.5 rounded-full text-xs font-black border">{{ $zkt->absent ?? 'غير محدد' }}</span>
        </div>
    </div>

    <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-8 shadow-sm">
        <form action="{{ route('zkts.update', $zkt->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- التاريخ --}}
            <div>
                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">
                    التاريخ <span class="text-red-500">*</span>
                </label>
                <input type="date" name="date" value="{{ old('date', $zkt->date) }}" required
                    class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-sky-500/50 text-sm transition-colors">
            </div>

            {{-- دخول / خروج --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">وقت الدخول</label>
                    <div class="relative">
                        <input type="time" name="in" value="{{ old('in', $zkt->in ? \Carbon\Carbon::parse($zkt->in)->format('H:i') : '') }}"
                            class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-green-500/30 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-green-500/50 text-sm transition-colors">
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">وقت الخروج</label>
                    <input type="time" name="out" value="{{ old('out', $zkt->out ? \Carbon\Carbon::parse($zkt->out)->format('H:i') : '') }}"
                        class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-red-500/30 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-red-500/50 text-sm transition-colors">
                </div>
            </div>

            {{-- خروج / دخول الاستراحة --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">خروج الاستراحة</label>
                    <input type="time" name="break_out" value="{{ old('break_out', $zkt->break_out ? \Carbon\Carbon::parse($zkt->break_out)->format('H:i') : '') }}"
                        class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-orange-500/50 text-sm transition-colors">
                </div>
                <div>
                    <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">دخول الاستراحة</label>
                    <input type="time" name="break_in" value="{{ old('break_in', $zkt->break_in ? \Carbon\Carbon::parse($zkt->break_in)->format('H:i') : '') }}"
                        class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-orange-500/50 text-sm transition-colors">
                </div>
            </div>

            {{-- أزرار --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-sm font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    حفظ التعديلات
                </button>
                <a href="{{ route('zkts.index') }}"
                    class="px-6 py-3 bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-white/10 rounded-xl text-sm font-bold transition-all border border-gray-200 dark:border-white/5">
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
@endsection
