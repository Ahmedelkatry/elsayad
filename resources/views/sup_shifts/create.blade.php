@extends('layouts_new.app')

@section('title')
    إضافة تعديل شيفت استثنائي | نظام إدارة الموظفين
@endsection

@section('css')
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('page-header')
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">الإعدادات</span>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">تعديل شيفت استثنائي</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">إضافة تعديل شيفت</h1>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-xl shadow-sm" role="alert">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2.5rem] p-8 md:p-12 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-sky-500/0 via-sky-500 to-sky-500/0 opacity-30"></div>
            
            <div class="flex items-center gap-6 mb-10">
                <div class="w-16 h-16 bg-sky-500/10 rounded-3xl flex items-center justify-center text-sky-600">
                    <i data-lucide="clock-alert" class="w-8 h-8"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">تعديل الشيفت الأساسي</h2>
                    <p class="text-gray-500 mt-1 text-sm">قم بتحديد الشيفت البديل لفترة زمنية محددة</p>
                </div>
            </div>

            <form action="{{ route('sup_shifts.store') }}" method="post" autocomplete="off" class="space-y-8">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest px-1">الشيفت الأساسي</label>
                        <select name="shift_id" class="form-control select2 w-full" required>
                            <option value="" selected disabled>اختر الشيفت</option>
                            @foreach ($shifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest px-1">الشيفت البديل (الاستثنائي)</label>
                        <select name="override_shift_id" class="form-control select2 w-full" required>
                            <option value="" selected disabled>اختر الشيفت</option>
                            @foreach ($shifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest px-1">من تاريخ</label>
                        <div class="relative group">
                            <i data-lucide="calendar-days" class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 group-focus-within:text-sky-500 transition-colors"></i>
                            <input type="date" name="start_date" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-100 dark:border-white/10 text-gray-900 dark:text-white pr-12 pl-5 py-4 rounded-2xl outline-none focus:border-sky-500/50 focus:ring-4 focus:ring-sky-500/5 transition-all">
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest px-1">إلى تاريخ</label>
                        <div class="relative group">
                            <i data-lucide="calendar-check-2" class="absolute right-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 group-focus-within:text-sky-500 transition-colors"></i>
                            <input type="date" name="end_date" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-100 dark:border-white/10 text-gray-900 dark:text-white pr-12 pl-5 py-4 rounded-2xl outline-none focus:border-sky-500/50 focus:ring-4 focus:ring-sky-500/5 transition-all">
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full bg-sky-500 text-white font-black py-5 rounded-[2rem] shadow-xl shadow-sky-500/20 hover:bg-sky-400 hover:-translate-y-1 transition-all active:scale-95 flex items-center justify-center gap-3 text-lg">
                        <i data-lucide="save" class="w-6 h-6"></i>
                        حفظ التعديلات الاستثنائية
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                dir: 'rtl',
                width: '100%'
            });
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
@endsection
