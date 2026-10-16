@extends('layouts_new.app')
@section('title')
    إضافة سجل حضور | تقرير الموظفين
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
        <span class="text-sky-600 dark:text-sky-500">إضافة سجل</span>
    </nav>
    <div class="flex items-center gap-3">
        <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
        <h1 class="text-2xl font-black text-gray-900 dark:text-white">إضافة سجل حضور جديد</h1>
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
    <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-8 shadow-sm">
        <form action="{{ route('zkts.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- الموظف --}}
            <div>
                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">
                    الموظف <span class="text-red-500">*</span>
                </label>
                <select name="numid" id="employee-select" class="form-control select2 w-full" required>
                    <option value="">-- اختر الموظف --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->num }}" {{ old('numid') == $emp->num ? 'selected' : '' }}>
                            {{ $emp->num }} - {{ $emp->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- التاريخ --}}
            <div>
                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">
                    التاريخ <span class="text-red-500">*</span>
                </label>
                <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                    class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-sky-500/50 text-sm transition-colors">
            </div>

            {{-- دخول / خروج --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">وقت الدخول</label>
                    <input type="time" name="in" value="{{ old('in') }}"
                        class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-green-500/50 text-sm transition-colors">
                </div>
                <div>
                    <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">وقت الخروج</label>
                    <input type="time" name="out" value="{{ old('out') }}"
                        class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-red-500/50 text-sm transition-colors">
                </div>
            </div>

            {{-- خروج / دخول الاستراحة --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">خروج الاستراحة</label>
                    <input type="time" name="break_out" value="{{ old('break_out') }}"
                        class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-orange-500/50 text-sm transition-colors">
                </div>
                <div>
                    <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">دخول الاستراحة</label>
                    <input type="time" name="break_in" value="{{ old('break_in') }}"
                        class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-orange-500/50 text-sm transition-colors">
                </div>
            </div>

            {{-- أزرار --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-sm font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    حفظ السجل
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
    <script>
        $(document).ready(function () {
            $('.select2').select2({ placeholder: 'اختر الموظف...', allowClear: true, width: '100%' });
        });
    </script>
@endsection
