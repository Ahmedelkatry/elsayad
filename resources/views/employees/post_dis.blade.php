@extends('layouts_new.app')
@section('title')
    تفاصيل الخصم | نظام إدارة الموظفين
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
        .dataTables_wrapper .dataTables_length select {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.25rem 1rem;
        }
        .dark .dataTables_wrapper .dataTables_length select {
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
                <span class="text-sky-600 dark:text-sky-500">تفاصيل الخصم</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ Auth::user()->name }}</h1>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table id="example" class="w-full text-right border-collapse whitespace-nowrap">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-[#111]">
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400 w-16">#</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">الخصم</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">التاريخ</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400 w-1/2">سبب الخصم</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                        <td class="py-3 px-4 text-xs text-gray-900 dark:text-white">1</td>
                        <td class="py-3 px-4 text-xs font-bold text-red-500">{{ $dis->dis }}</td>
                        <td class="py-3 px-4 text-xs text-gray-900 dark:text-white">{{ $dis->date }}</td>
                        <td class="py-3 px-4 text-xs text-gray-900 dark:text-white whitespace-normal">
                            <textarea readonly class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none text-xs resize-none" rows="3">{{ $dis->dec }}</textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
    <!-- Datatables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#example').DataTable({
                language: {
                    searchPlaceholder: 'بحث...',
                    sSearch: '',
                    lengthMenu: '_MENU_ سجل/صفحة',
                },
                responsive: true,
                paging: false,
                searching: false,
                info: false
            });
        });
    </script>
@endsection
