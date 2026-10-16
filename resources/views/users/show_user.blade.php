@extends('layouts_new.app')
@section('title')
    قائمة المستخدمين | نظام إدارة الموظفين
@endsection

@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <style>
        .dataTables_wrapper .dataTables_filter input {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.5rem;
            margin-right: 0.5rem;
        }
        .dark .dataTables_wrapper .dataTables_filter input {
            background: #111;
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
        }
    </style>
@endsection

@section('page-header')
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">المستخدمين</span>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">قائمة المستخدمين</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">إدارة المستخدمين</h1>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            @can('اضافه مستخدم')
                <a href="{{ route('users.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-sky-500 text-white rounded-xl text-xs font-bold hover:bg-sky-400 shadow-lg shadow-sky-500/20 transition-all active:scale-95">
                    <i data-lucide="user-plus" class="w-4 h-4 text-white"></i>
                    إضافة مستخدم
                </a>
            @endcan
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong>{{ session('success') }}</strong>
        </div>
    @endif
@endsection

@section('content')
    <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse whitespace-nowrap pixel-table-modern">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-[#111]">
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">#</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">اسم المستخدم</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">البريد الإلكتروني</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">الحالة</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">نوع المستخدم</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; ?>
                    @foreach ($data as $key => $user)
                        @if ($user->email !== 'katryana22@gmail.com' && $user->email !== 'user@pixel.com' )
                            <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                <td class="py-3 px-4 text-xs text-gray-900 dark:text-white">{{ ++$i }}</td>
                                <td class="py-3 px-4 text-xs font-bold text-gray-900 dark:text-white">{{ $user->name }}</td>
                                <td class="py-3 px-4 text-xs text-gray-600 dark:text-gray-400 font-mono">{{ $user->email }}</td>
                                <td class="py-3 px-4">
                                    @if($user->Status == 'مفعل')
                                        <span class="px-2.5 py-1 bg-green-500/10 text-green-600 dark:text-green-500 rounded-lg text-[10px] font-bold">{{ $user->Status }}</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-red-500/10 text-red-600 dark:text-red-500 rounded-lg text-[10px] font-bold">{{ $user->Status }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @php
                                        $roles = is_array($user->roles_name) ? $user->roles_name : [$user->roles_name];
                                    @endphp
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($roles as $role)
                                            <span class="px-2 py-0.5 bg-sky-500/10 text-sky-600 dark:text-sky-500 rounded text-[9px] font-bold">{{ $role }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        @can('تعديل مستخدم')
                                            <a href="{{ route('users.edit', $user->id) }}" class="p-2 bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-500 hover:bg-green-200 dark:hover:bg-green-500/20 rounded-lg transition-colors" title="تعديل">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </a>
                                        @endcan
                                        @can('حذف مستخدم')
                                            <button onclick="confirmDelete('{{ $user->id }}', '{{ $user->name }}')" class="p-2 bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-500 hover:bg-red-200 dark:hover:bg-red-500/20 rounded-lg transition-colors" title="حذف">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-user-modal" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-md rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent text-right">
                <h3 class="text-sm font-bold text-red-500 flex items-center gap-2">
                    <i data-lucide="alert-triangle" class="w-5 h-5"></i> تأكيد الحذف
                </h3>
                <button type="button" onclick="hidePixelModal('delete-user-modal')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('users.destroy', 'test') }}" method="post">
                {{ method_field('delete') }}
                {{ csrf_field() }}
                <div class="p-6 space-y-4 text-right">
                    <p class="text-sm text-gray-900 dark:text-white font-bold">هل أنت متأكد من حذف هذا المستخدم؟</p>
                    <div class="bg-gray-50 dark:bg-[#111] p-4 rounded-xl border border-gray-100 dark:border-white/5 text-center">
                        <input type="hidden" name="user_id" id="delete_user_id">
                        <span id="delete_username" class="text-lg text-gray-900 dark:text-white font-bold"></span>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3">
                    <button type="button" onclick="hidePixelModal('delete-user-modal')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                    <button type="submit" class="px-5 py-2.5 bg-red-500 text-white hover:bg-red-600 rounded-xl text-xs font-bold shadow-lg shadow-red-500/10 transition-all active:scale-95">تأكيد الحذف</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            // Table is automatically initialized by js.js
        });

        function confirmDelete(id, name) {
            document.getElementById('delete_user_id').value = id;
            document.getElementById('delete_username').textContent = name;
            showPixelModal('delete-user-modal');
        }
    </script>
@endsection
