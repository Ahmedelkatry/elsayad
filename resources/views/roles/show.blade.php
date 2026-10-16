@extends('layouts_new.app')
@section('title')
    عرض تفاصيل الصلاحية | نظام إدارة الموظفين
@endsection

@section('page-header')
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <a href="{{ route('roles.index') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الصلاحيات</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">عرض الصلاحية</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-blue-500 rounded-full shadow-[0_0_15px_rgba(59,130,246,0.3)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">تفاصيل الصلاحية: <span class="text-blue-500">{{ $role->name }}</span></h1>
            </div>
        </div>
        
        <a href="{{ route('roles.index') }}" class="flex items-center gap-2 px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-bold hover:bg-gray-300 dark:hover:bg-white/10 transition-all active:scale-95">
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
            رجوع للقائمة
        </a>
    </div>
@endsection

@section('content')
    <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2.5rem] shadow-xl shadow-black/5 overflow-hidden transition-all">
        <div class="p-8 space-y-8">
            <div>
                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-6 flex items-center gap-2">
                    <i data-lucide="shield-check" class="w-4 h-4 text-green-500"></i> قائمة الصلاحيات الممنوحة
                </label>
                
                @if(!empty($rolePermissions))
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($rolePermissions as $v)
                            <div class="flex items-center gap-3 p-4 rounded-2xl bg-gray-50 dark:bg-white/[0.02] border border-transparent transition-all">
                                <div class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.4)]"></div>
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300 italic">{{ $v->name }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-20 bg-gray-50 dark:bg-white/[0.02] rounded-[2rem] border border-dashed border-gray-200 dark:border-white/10">
                        <i data-lucide="alert-circle" class="w-12 h-12 text-gray-300 dark:text-gray-700 mx-auto mb-4"></i>
                        <p class="text-sm text-gray-500">لا توجد صلاحيات محددة لهذا النوع.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/treeview/treeview.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
@endsection