@extends('layouts_new.app')
@section('title')
    إضافة خصومات متعددة | نظام إدارة الموظفين
@endsection

@section('css')
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        .select2-container--default .select2-selection--single {
            background-color: #f9fafb !important;
            border-color: #e5e7eb !important;
            border-radius: 0.75rem !important;
            height: 42px !important;
            display: flex !important;
            align-items: center !important;
        }
        .dark .select2-container--default .select2-selection--single {
            background-color: #111 !important;
            border-color: rgba(255,255,255,0.1) !important;
        }
        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #fff !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
        }
        .dark .select2-dropdown {
            background-color: #111 !important;
            border-color: rgba(255,255,255,0.1) !important;
        }
        .dark .select2-container--default .select2-search--dropdown .select2-search__field {
            background-color: #000 !important;
            color: #fff !important;
            border-color: rgba(255,255,255,0.1) !important;
        }
        .dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #eab308 !important;
            color: #000 !important;
        }
    </style>
@endsection

@section('page-header')
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <a href="{{ route('disemp.index') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الخصومات</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">إضافة متعددة</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">إضافة خصومات למجموعة موظفين</h1>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-6 shadow-sm">
        <form action="{{ route('disemp.store') }}" method="post">
            @csrf
            
            <div class="flex justify-between items-center mb-6">
                <h4 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="users" class="w-4 h-4 text-sky-500"></i> قائمة الخصومات
                </h4>
                <button type="button" id="add_row" class="px-4 py-2 bg-sky-500/10 text-sky-600 dark:text-sky-400 hover:bg-sky-500/20 rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                    <i data-lucide="plus" class="w-3 h-3"></i> إضافة موظف آخر
                </button>
            </div>

            <div class="overflow-x-auto mb-6">
                <table class="w-full text-right border-collapse" id="multi_table">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-[#111]">
                            <th class="py-3 px-4 text-[11px] uppercase tracking-widest font-bold text-gray-500">الموظف</th>
                            <th class="py-3 px-4 text-[11px] uppercase tracking-widest font-bold text-gray-500">قيمة الخصم</th>
                            <th class="py-3 px-4 text-[11px] uppercase tracking-widest font-bold text-gray-500">التاريخ</th>
                            <th class="py-3 px-4 text-[11px] uppercase tracking-widest font-bold text-gray-500">ملاحظات</th>
                            <th class="py-3 px-4 text-[11px] uppercase tracking-widest font-bold text-gray-500 text-center">حذف</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <td class="py-3 px-4 w-1/3">
                                <select name="employees_id[]" class="select2-new w-full" required>
                                    <option value="" disabled selected>اختر الموظف...</option>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->num . ' - ' . $emp->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="py-3 px-4 w-1/6">
                                <input type="number" name="dis[]" min="0" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2.5 rounded-xl outline-none focus:border-red-500 text-xs font-bold text-red-500">
                            </td>
                            <td class="py-3 px-4 w-1/6">
                                <input type="date" name="date[]" value="{{ date('Y-m-d') }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2.5 rounded-xl outline-none focus:border-sky-500 text-xs">
                            </td>
                            <td class="py-3 px-4 w-1/4">
                                <input type="text" name="dec[]" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2.5 rounded-xl outline-none focus:border-sky-500 text-xs placeholder-gray-400" placeholder="سبب الخصم">
                            </td>
                            <td class="py-3 px-4 text-center">
                                <button type="button" class="p-2 bg-gray-100 dark:bg-white/5 text-gray-400 rounded-lg cursor-not-allowed remove-row" disabled>
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end border-t border-gray-100 dark:border-white/5 pt-6 gap-3">
                <a href="{{ route('disemp.index') }}" class="px-6 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-sm font-bold transition-all">إلغاء</a>
                <button type="submit" class="px-8 py-2.5 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-sm font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> حفظ كل الخصومات
                </button>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Reinitialize lucide icons inside dynamically added rows
            function initIcons() {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }

            // Save a clean template of the row before Select2 is initialized
            var $rowTemplate = $('#multi_table tbody tr:first').clone();
            
            // Initialize select2 on the first row
            $('.select2-new').select2({ 
                width: '100%',
                dir: "rtl"
            });

            $('#add_row').click(function() {
                var $tableBody = $('#multi_table tbody');
                var $newRow = $rowTemplate.clone();
                
                // Reset inputs
                $newRow.find('input[type="number"]').val('');
                $newRow.find('input[type="text"]').val('');
                $newRow.find('input[type="date"]').val('{{ date('Y-m-d') }}');
                
                // Enable delete button for added rows
                var deleteBtn = $newRow.find('.remove-row');
                deleteBtn.removeAttr('disabled');
                deleteBtn.removeClass('bg-gray-100 dark:bg-white/5 text-gray-400 cursor-not-allowed')
                         .addClass('bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-500 hover:bg-red-200 dark:hover:bg-red-500/20 cursor-pointer');
                
                $tableBody.append($newRow);
                
                // Initialize Select2 on the new row's select
                $newRow.find('.select2-new').select2({ 
                    width: '100%',
                    dir: "rtl"
                });

                initIcons();
            });

            $(document).on('click', '.remove-row:not([disabled])', function() {
                $(this).closest('tr').remove();
            });
        });
    </script>
@endsection
