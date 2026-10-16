@extends('layouts_new.app')
@section('title')
    صلاحيات المستخدمين | نظام إدارة الموظفين
@endsection

@section('css')
    <!--Internal Notify -->
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection

@section('page-header')
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">المستخدمين</span>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">صلاحيات المستخدمين</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">إدارة الصلاحيات</h1>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            @can('اضافة صلاحية')
                <a href="{{ route('roles.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-sky-500 text-white rounded-xl text-xs font-bold hover:bg-sky-400 shadow-lg shadow-sky-500/20 transition-all active:scale-95">
                    <i data-lucide="plus" class="w-4 h-4 text-white"></i>
                    إضافة صلاحية
                </a>
            @endcan
        </div>
    </div>

    @if (session()->has('Add'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong>تم إضافة الصلاحية بنجاح</strong>
        </div>
    @endif
    @if (session()->has('edit'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong>تم تحديث بيانات الصلاحية بنجاح</strong>
        </div>
    @endif
    @if (session()->has('delete'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong>تم حذف الصلاحية بنجاح</strong>
        </div>
    @endif
@endsection

@section('content')
    <div class="overflow-x-auto">
        <table class="w-full text-right border-collapse whitespace-nowrap pixel-table-modern">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-[#111]">
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">#</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">الاسم</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $key => $role)
                        @if($role->name !== "owner")
                            <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                <td class="py-3 px-4 text-xs text-gray-900 dark:text-white">{{ ++$i }}</td>
                                <td class="py-3 px-4 text-xs font-bold text-gray-900 dark:text-white">
                                    <span class="px-3 py-1 bg-sky-500/10 text-sky-600 dark:text-sky-500 rounded-lg">{{ $role->name }}</span>
                                </td>
                                <td class="py-3 px-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        @can('عرض صلاحية')
                                            <a href="{{ route('roles.show', $role->id) }}" class="p-2 bg-blue-100 dark:bg-blue-500/10 text-blue-600 dark:text-blue-500 hover:bg-blue-200 dark:hover:bg-blue-500/20 rounded-lg transition-colors" title="عرض">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </a>
                                        @endcan
                                        
                                        @can('تعديل صلاحية')
                                            <a href="{{ route('roles.edit', $role->id) }}" class="p-2 bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-500 hover:bg-green-200 dark:hover:bg-green-500/20 rounded-lg transition-colors" title="تعديل">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </a>
                                        @endcan

                                        @if ($role->name !== 'owner')
                                            @can('حذف صلاحية')
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id], 'style' => 'display:inline']) !!}
                                                    <button type="submit" class="p-2 bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-500 hover:bg-red-200 dark:hover:bg-red-500/20 rounded-lg transition-colors" title="حذف" onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                    </button>
                                                {!! Form::close() !!}
                                            @endcan
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>
@endsection
