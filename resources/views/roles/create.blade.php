@extends('layouts_new.app')
@section('title')
    إضافة صلاحية جديدة | نظام إدارة الموظفين
@endsection

@section('css')
    <!--Internal treeview -->
    <link href="{{ URL::asset('assets/plugins/treeview/treeview-rtl.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-header')
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <a href="{{ route('roles.index') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الصلاحيات</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">إضافة نوع مستخدم</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">إضافة صلاحية جديدة</h1>
            </div>
        </div>
    </div>

    @if (count($errors) > 0)
        <div class="bg-red-500/10 border border-red-500/20 text-red-500 px-6 py-4 rounded-[1.5rem] mb-6 flex flex-col gap-2">
            <div class="flex items-center gap-2 font-bold text-sm">
                <i data-lucide="alert-circle" class="w-5 h-5"></i>
                عفواً، هناك بعض الأخطاء:
            </div>
            <ul class="list-disc list-inside text-xs opacity-80">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection

@section('content')
    {!! Form::open(['route' => 'roles.store', 'method' => 'POST']) !!}
    <div class="space-y-6">
        <!-- Main Card -->
        <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2.5rem] shadow-xl shadow-black/5 overflow-hidden transition-all">
            <div class="p-8 space-y-8">
                <!-- Role Name Input -->
                <div class="max-w-md">
                    <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-3 flex items-center gap-2">
                        <i data-lucide="shield" class="w-4 h-4 text-sky-500"></i> اسم الصلاحية
                    </label>
                    {!! Form::text('name', null, [
                        'class' => 'w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-5 py-4 rounded-2xl outline-none focus:border-sky-500/50 text-sm transition-all shadow-inner placeholder:text-gray-400',
                        'placeholder' => 'مثال: مدير الموارد البشرية',
                        'required'
                    ]) !!}
                </div>

                <div class="h-px bg-gray-100 dark:bg-white/5 w-full"></div>

                <!-- Permissions Section -->
                <div>
                    <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-6 flex items-center gap-2">
                        <i data-lucide="check-square" class="w-4 h-4 text-green-500"></i> تحديد الصلاحيات المتاحة
                    </label>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($permission as $value)
                            <label class="group relative flex items-center gap-4 p-4 rounded-2xl bg-gray-50 dark:bg-white/[0.02] border border-transparent hover:border-sky-500/30 hover:bg-white dark:hover:bg-white/[0.05] transition-all cursor-pointer overflow-hidden">
                                <div class="absolute inset-0 bg-sky-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                
                                <div class="relative flex items-center justify-center">
                                    {{ Form::checkbox('permission[]', $value->id, false, [
                                        'id' => 'perm-'.$value->id,
                                        'class' => 'w-5 h-5 rounded-lg border-gray-300 dark:border-white/10 text-sky-500 focus:ring-sky-500/20 bg-white dark:bg-[#111] transition-all'
                                    ]) }}
                                </div>
                                
                                <span class="relative text-xs font-bold text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">
                                    {{ $value->name }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="px-8 py-6 bg-gray-50 dark:bg-transparent border-t border-gray-100 dark:border-white/5 flex justify-end">
                <button type="submit" class="flex items-center gap-2 px-8 py-4 bg-sky-500 text-white hover:bg-sky-400 rounded-2xl font-black text-sm shadow-xl shadow-sky-500/20 transition-all active:scale-95 group">
                    <span>تأكيد البيانات وحفظ الصلاحية</span>
                    <i data-lucide="check-circle" class="w-5 h-5 group-hover:rotate-12 transition-transform"></i>
                </button>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@section('js')
    <!-- Internal Treeview js -->
    <script src="{{ URL::asset('assets/plugins/treeview/treeview.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
@endsection