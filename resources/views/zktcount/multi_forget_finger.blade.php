@extends('layouts_new.app')
@section('title')
    إضافة بصمات منسية متعددة
@endsection
@section('css')
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        .select2-container .select2-selection--single {
            height: 42px !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 0.75rem !important;
            display: flex !important;
            align-items: center !important;
        }
        .dark .select2-container .select2-selection--single {
            background-color: #111 !important;
            border-color: rgba(255,255,255,0.1) !important;
        }
        .dark .select2-container .select2-selection--single .select2-selection__rendered {
            color: #fff !important;
        }
        .dark .select2-dropdown {
            background-color: #181818 !important;
            border-color: rgba(255,255,255,0.1) !important;
        }
        .dark .select2-results__option {
            color: #fff !important;
        }
        .dark .select2-results__option[aria-selected=true] {
            background-color: rgba(255,255,255,0.1) !important;
        }
    </style>
@endsection
@section('page-header')
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">البصمات المنسية</span>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">إضافة متعددة</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">إضافة بصمات لمجموعة موظفين</h1>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-6 shadow-sm">
                <form action="{{ route('forget_finger.store') }}" method="post">
                    @csrf
                    <div class="overflow-x-auto print:overflow-visible mb-6">
                        <table class="w-full text-center border-collapse text-sm whitespace-nowrap" id="multi_table">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-[#111] border-b border-gray-200 dark:border-white/10">
                                    <th class="py-3 px-4 font-bold text-gray-500 dark:text-gray-400 text-right">الموظف</th>
                                    <th class="py-3 px-4 font-bold text-gray-500 dark:text-gray-400">التاريخ</th>
                                    <th class="py-3 px-4 font-bold text-gray-500 dark:text-gray-400">وقت الدخول</th>
                                    <th class="py-3 px-4 font-bold text-gray-500 dark:text-gray-400">وقت الخروج</th>
                                    <th class="py-3 px-4 font-bold text-gray-500 dark:text-gray-400 text-center">حذف</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-gray-50 dark:border-white/5">
                                    <td class="py-3 px-2">
                                        <select name="employees_id[]" class="form-control select2-new w-full" required>
                                            <option value="">اختر الموظف</option>
                                            @foreach ($employees as $emp)
                                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-3 px-2">
                                        <input type="date" name="date[]" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2 rounded-xl outline-none focus:border-sky-500/50 text-sm transition-colors" value="{{ date('Y-m-d') }}" required>
                                    </td>
                                    <td class="py-3 px-2">
                                        <input type="time" name="in[]" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2 rounded-xl outline-none focus:border-sky-500/50 text-sm transition-colors">
                                    </td>
                                    <td class="py-3 px-2">
                                        <input type="time" name="out[]" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2 rounded-xl outline-none focus:border-sky-500/50 text-sm transition-colors">
                                    </td>
                                    <td class="py-3 px-2 text-center">
                                        <button type="button" class="px-3 py-2 bg-red-100 dark:bg-red-500/10 text-red-600 hover:bg-red-200 dark:hover:bg-red-500/20 rounded-xl text-sm font-bold transition-all remove-row" disabled>
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <button type="button" class="px-5 py-2.5 bg-blue-50 dark:bg-blue-500/10 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-500/20 rounded-xl text-sm font-bold transition-all flex items-center gap-2 mb-6" id="add_row">
                        <i data-lucide="plus" class="w-4 h-4"></i> إضافة موظف آخر
                    </button>
                    
                    <hr class="border-gray-100 dark:border-white/5 mb-6">
                    
                    <div class="flex gap-3">
                        <button type="submit" class="px-6 py-2.5 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-sm font-bold shadow-lg shadow-sky-500/20 transition-all flex items-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i> حفظ الكل
                        </button>
                        <a href="{{ route('forget_finger.index') }}" class="px-6 py-2.5 bg-gray-100 dark:bg-white/5 text-gray-900 dark:text-white hover:bg-gray-200 dark:hover:bg-white/10 rounded-xl text-sm font-bold transition-all flex items-center gap-2">
                            <i data-lucide="x" class="w-4 h-4"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Save a clean template of the row before Select2 is initialized
            var $rowTemplate = $('#multi_table tbody tr:first').clone();
            
            $('.select2-new').select2({ width: '100%' });

            $('#add_row').click(function() {
                var $tableBody = $('#multi_table tbody');
                var $newRow = $rowTemplate.clone();
                
                // Reset inputs to current date
                $newRow.find('input[type="date"]').val('{{ date('Y-m-d') }}');
                $newRow.find('.remove-row').removeAttr('disabled');
                
                $tableBody.append($newRow);
                
                // Initialize Select2 on the new row's select
                $newRow.find('.select2-new').select2({ width: '100%' });
                
                // Refresh Lucide icons if available
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });

            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
            });
        });
    </script>
@endsection
