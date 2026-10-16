@extends('layouts_new.app')
@section('title')
    إدارة الوقت الإضافي | نظام إدارة الموظفين
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
                <span class="text-sky-600 dark:text-sky-500">شؤون الموظفين</span>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">الوقت الإضافي (OT)</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">إدارة الوقت الإضافي</h1>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <form action="{{ route('plzot.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <select name="numid" class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-xl text-xs outline-none focus:border-sky-500/50 w-44">
                    <option value="">كل الموظفين</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->num }}" {{ request('numid') == $employee->num ? 'selected' : '' }}>
                            {{ $employee->name }}
                        </option>
                    @endforeach
                </select>
                <input type="date" name="start" value="{{ request('start') }}" class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-xl text-xs outline-none focus:border-sky-500/50">
                <input type="date" name="end" value="{{ request('end') }}" class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-xl text-xs outline-none focus:border-sky-500/50">
                <button type="submit" class="p-2 bg-sky-500 text-white rounded-xl hover:bg-sky-400 transition-all active:scale-95">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </button>
            </form>
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
        <form action="{{ route('ok_all_ot') }}" method="POST" id="bulk_approve_form">
            @csrf
            <div class="mb-4 flex items-center justify-between">
                <button type="submit" id="bulk_approve_btn" style="display: none;" class="items-center gap-2 px-5 py-2.5 bg-green-500 text-white rounded-xl text-xs font-bold hover:bg-green-600 shadow-lg shadow-green-500/10 transition-all active:scale-95">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    قبول التحديد
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse pixel-table-modern">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-white/5">
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400"># <input type="checkbox" id="select_all" class="rounded border-gray-300 text-sky-500 shadow-sm focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"></th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">اسم الموظف</th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">التاريخ</th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">وقت OT</th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">الحساب</th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">الحالة</th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plzots as $i => $emp)
                            <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">
                                    {{ $i + 1 }}
                                    <input type="checkbox" name="id[]" value="{{ $emp->id }}" class="box1 rounded border-gray-300 text-sky-500 shadow-sm focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 mr-2">
                                </td>
                                <td class="py-3 px-4 text-sm font-bold text-gray-900 dark:text-white">{{ $emp->name }}</td>
                                <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">{{ $emp->date }}</td>
                                <td class="py-3 px-4 text-sm font-bold text-green-500">
                                    {{ Carbon\Carbon::parse($emp->othours)->format('H:i:s') }}
                                </td>
                                <td class="py-3 px-4 text-sm font-bold text-sky-600">{{ $emp->count_ot }}</td>
                                <td class="py-3 px-4 text-sm">
                                    @if($emp->status == 0)
                                        <span class="px-2 py-1 bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-500 rounded-lg text-[10px] font-bold italic">مرفوض</span>
                                    @else
                                        <span class="px-2 py-1 bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-500 rounded-lg text-[10px] font-bold italic">مقبول</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        @if ($emp->status == 0)
                                            <a href="{{ route('plzotup', $emp->id) }}" class="p-2 bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-500 hover:bg-green-200 dark:hover:bg-green-500/20 rounded-lg transition-colors" title="قبول">
                                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('showu', $emp->id) }}" class="p-2 bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-500 hover:bg-red-200 dark:hover:bg-red-500/20 rounded-lg transition-colors" title="رفض">
                                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
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

            // Bulk Action Logic
            $(document).on('click', '#select_all', function(e) {
                $(".box1").prop('checked', $(this).prop('checked'));
                toggleApproveBtn();
            });

            $(document).on('change', '.box1', function() {
                toggleApproveBtn();
            });

            function toggleApproveBtn() {
                if ($(".box1:checked").length > 0) {
                    $("#bulk_approve_btn").show().css('display', 'flex');
                } else {
                    $("#bulk_approve_btn").hide();
                }
            }
        });
    </script>
@endsection
