@extends('layouts_new.app')
@section('title')
    إشعارات السلف | نظام إدارة الموظفين
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
                <span class="text-sky-600 dark:text-sky-500">الإشعارات</span>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">إشعارات السلف</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">إشعارات السلف</h1>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection

@section('content')
    <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse pixel-table-modern">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-[#111]">
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">#</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">التاريخ</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">السلفة</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">سبب السلفة</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @if(isset($emp))
                    <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                        <td class="py-3 px-4 text-xs text-gray-900 dark:text-white">{{ $i }}</td>
                        <td class="py-3 px-4 text-xs text-gray-900 dark:text-white">{{ $emp->datesalf }}</td>
                        <td class="py-3 px-4 text-xs font-bold text-sky-600">{{ $emp->salf }}</td>
                        <td class="py-3 px-4 text-xs text-gray-900 dark:text-white">
                            <div class="bg-gray-50 dark:bg-white/5 p-3 rounded-lg border border-gray-100 dark:border-white/10 text-[11px] leading-relaxed">
                                {{ $emp->textsalf }}
                            </div>
                        </td>
                        <td class="py-3 px-4 text-xs">
                            @if ($emp->status == 0)
                                <span class="px-2 py-1 bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-500 rounded-lg text-[10px] font-bold italic">قبول</span>
                            @elseif ($emp->status == 1)
                                <span class="px-2 py-1 bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-500 rounded-lg text-[10px] font-bold italic">رفض</span>
                            @elseif ($emp->status == 3)
                                <span class="px-2 py-1 bg-sky-100 dark:bg-sky-500/10 text-sky-600 dark:text-sky-500 rounded-lg text-[10px] font-bold italic">جاري التنفيذ</span>
                            @endif
                        </td>
                    </tr>
                    @endif
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
            // Table is automatically initialized by js.js
        });
    </script>
@endsection
