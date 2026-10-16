@extends('layouts_new.app')
@section('title')
    الإجراءات الجماعية الشاملة | نظام إدارة الموظفين
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
                <a href="{{ route('employees.index') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الموظفين</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">إجراءات الموظفين الفردية</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">إجراءات الموظفين الجماعية (المتنوعة)</h1>
            </div>
        </div>
        <div>
            <button type="button" id="add_employee_btn" class="flex items-center gap-2 px-5 py-2.5 bg-sky-500 text-white rounded-xl text-xs font-bold hover:bg-sky-400 shadow-lg shadow-sky-500/20 transition-all active:scale-95">
                <i data-lucide="plus-circle" class="w-4 h-4 text-white"></i>
                إضافة موظف آخر
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="">
        <form action="{{ route('bulk_action.store') }}" method="post">
            @csrf

            <div id="employees_container">
                <!-- First Employee Card -->
                <div class="employee-card bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-6 shadow-sm mb-6 relative">
                    <div class="flex justify-between items-center mb-6 border-b border-gray-100 dark:border-white/5 pb-4">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <i data-lucide="user" class="w-4 h-4 text-sky-500"></i> بيانات الموظف
                        </h3>
                        <button type="button" class="remove-card px-3 py-1.5 bg-red-500/10 text-red-600 dark:text-red-500 hover:bg-red-500/20 rounded-xl text-xs font-bold transition-all flex items-center gap-1 hidden">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> حذف
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Employee Select -->
                        <div class="md:col-span-3">
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">اسم الموظف</label>
                            <select name="employees_id[]" class="select2-new w-full" required>
                                <option value="" disabled selected>اختر الموظف...</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->num . ' - ' . $emp->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Loan Section -->
                        <div class="bg-gray-50 dark:bg-[#111] p-5 rounded-2xl border border-gray-100 dark:border-white/5 space-y-4">
                            <h4 class="text-xs font-black text-green-500 flex items-center gap-1.5 mb-2">
                                <i data-lucide="banknote" class="w-4 h-4"></i> السلفة
                            </h4>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">قيمة السلفة</label>
                                <input type="number" name="salf[]" min="0" step="0.01" class="w-full bg-white dark:bg-[#181818] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-xl outline-none text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">التاريخ</label>
                                <input type="date" name="salf_date[]" value="{{ date('Y-m-d') }}" class="w-full bg-white dark:bg-[#181818] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-xl outline-none text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">السبب / ملاحظات</label>
                                <input type="text" name="salf_dec[]" class="w-full bg-white dark:bg-[#181818] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-xl outline-none text-xs placeholder-gray-400" placeholder="ملاحظات السلفة">
                            </div>
                        </div>

                        <!-- Deduction Section -->
                        <div class="bg-gray-50 dark:bg-[#111] p-5 rounded-2xl border border-gray-100 dark:border-white/5 space-y-4">
                            <h4 class="text-xs font-black text-red-500 flex items-center gap-1.5 mb-2">
                                <i data-lucide="minus-circle" class="w-4 h-4"></i> الخصم
                            </h4>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">قيمة الخصم</label>
                                <input type="number" name="dis_value[]" min="0" step="0.01" class="w-full bg-white dark:bg-[#181818] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-xl outline-none text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">التاريخ</label>
                                <input type="date" name="dis_date[]" value="{{ date('Y-m-d') }}" class="w-full bg-white dark:bg-[#181818] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-xl outline-none text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">السبب / ملاحظات</label>
                                <input type="text" name="dis_dec[]" class="w-full bg-white dark:bg-[#181818] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-xl outline-none text-xs placeholder-gray-400" placeholder="ملاحظات الخصم">
                            </div>
                        </div>

                        <!-- Incentive Section -->
                        <div class="bg-gray-50 dark:bg-[#111] p-5 rounded-2xl border border-gray-100 dark:border-white/5 space-y-4">
                            <h4 class="text-xs font-black text-blue-500 flex items-center gap-1.5 mb-2">
                                <i data-lucide="sparkles" class="w-4 h-4"></i> الحافز
                            </h4>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">قيمة الحافز</label>
                                <input type="number" name="hafz[]" min="0" step="0.01" class="w-full bg-white dark:bg-[#181818] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-xl outline-none text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">التاريخ</label>
                                <input type="date" name="hafz_date[]" value="{{ date('Y-m-d') }}" class="w-full bg-white dark:bg-[#181818] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-xl outline-none text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">السبب / ملاحظات</label>
                                <input type="text" name="hafz_dec[]" class="w-full bg-white dark:bg-[#181818] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-xl outline-none text-xs placeholder-gray-400" placeholder="ملاحظات الحافز">
                            </div>
                        </div>

                        <!-- Vacation Section -->
                        <div class="bg-gray-50 dark:bg-[#111] p-5 rounded-2xl border border-gray-100 dark:border-white/5 space-y-4">
                            <h4 class="text-xs font-black text-orange-500 flex items-center gap-1.5 mb-2">
                                <i data-lucide="plane" class="w-4 h-4"></i> الإجازة
                            </h4>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">تاريخ الإجازة</label>
                                <input type="date" name="vacation_date[]" class="w-full bg-white dark:bg-[#181818] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-xl outline-none text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">خصم الإجازة (قيمة)</label>
                                <input type="number" name="disvac[]" min="0" class="w-full bg-white dark:bg-[#181818] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-xl outline-none text-xs" placeholder="0">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">السبب / ملاحظات</label>
                                <input type="text" name="vacation_dec[]" class="w-full bg-white dark:bg-[#181818] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-xl outline-none text-xs placeholder-gray-400" placeholder="ملاحظات الإجازة">
                            </div>
                        </div>

                        <!-- Permission Section -->
                        <div class="bg-gray-50 dark:bg-[#111] p-5 rounded-2xl border border-gray-100 dark:border-white/5 space-y-4 md:col-span-2">
                            <h4 class="text-xs font-black text-purple-500 flex items-center gap-1.5 mb-2">
                                <i data-lucide="clock" class="w-4 h-4"></i> الإذن (الاستئذان)
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-500 mb-1">تاريخ الإذن</label>
                                        <input type="date" name="please_date[]" class="w-full bg-white dark:bg-[#181818] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-xl outline-none text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-500 mb-1">نوع الاستئذان</label>
                                        <select name="in_out[]" class="w-full bg-white dark:bg-[#181818] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-xl outline-none text-xs">
                                            <option value="0">إذن دخول</option>
                                            <option value="1">إذن خروج</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-500 mb-1">التأخير بالدقيقة</label>
                                        <input type="number" name="please_minutes[]" min="0" class="w-full bg-white dark:bg-[#181818] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-xl outline-none text-xs" placeholder="0">
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-500 mb-1">نوع الخصم</label>
                                        <select name="please_dis_type[]" class="w-full bg-white dark:bg-[#181818] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-xl outline-none text-xs please-dis-type-select">
                                            <option value="dis">خصم بالدقيقة</option>
                                            <option value="dis_value">خصم قيمة مالية</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-red-500 mb-1 please-dis-val-label">قيمة الخصم بالدقيقة</label>
                                        <input type="number" name="please_dis_val[]" min="0" step="0.01" class="w-full bg-red-50 dark:bg-red-500/5 border border-red-200 dark:border-red-500/20 text-red-700 dark:text-red-400 px-3 py-2 rounded-xl outline-none text-xs" placeholder="0">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-500 mb-1">السبب / ملاحظات الإذن</label>
                                        <input type="text" name="please_dec[]" class="w-full bg-white dark:bg-[#181818] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-xl outline-none text-xs placeholder-gray-400" placeholder="ملاحظات الإذن">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end pt-6 gap-3">
                <a href="{{ route('employees.index') }}" class="px-6 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-sm font-bold transition-all">إلغاء</a>
                <button type="submit" class="px-8 py-2.5 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-sm font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> حفظ كل الإجراءات
                </button>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Function to re-initialize lucide icons
            function initIcons() {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }

            // Save clean row/card template before any select2 initialization
            var $cardTemplate = $('.employee-card:first').clone();
            // Clear inputs in template
            $cardTemplate.find('input[type="number"]').val('');
            $cardTemplate.find('input[type="text"]').val('');
            $cardTemplate.find('select').prop('selectedIndex', 0);
            // Enable and show remove button in template
            $cardTemplate.find('.remove-card').removeClass('hidden');

            // Initialize select2 on the first card
            $('.select2-new').select2({
                placeholder: 'اختر الموظف...',
                dir: "rtl",
                width: '100%'
            });

            // Handle adding new card
            $('#add_employee_btn').click(function() {
                var $newCard = $cardTemplate.clone();
                
                // Add default date to the new inputs
                $newCard.find('input[type="date"]').val('{{ date("Y-m-d") }}');
                $newCard.find('input[name="vacation_date[]"], input[name="please_date[]"]').val('');

                // Append card
                $('#employees_container').append($newCard);

                // Initialize select2 on the new card's select
                $newCard.find('.select2-new').select2({
                    placeholder: 'اختر الموظف...',
                    dir: "rtl",
                    width: '100%'
                });

                initIcons();
            });

            // Handle removing card
            $(document).on('click', '.remove-card', function() {
                $(this).closest('.employee-card').remove();
            });

            // Handle permission deduction type change label dynamically
            $(document).on('change', '.please-dis-type-select', function() {
                var val = $(this).val();
                var card = $(this).closest('.employee-card');
                var label = card.find('.please-dis-val-label');
                if (val === 'dis') {
                    label.text('قيمة الخصم بالدقيقة');
                } else {
                    label.text('قيمة الخصم بالجنيه');
                }
            });
        });
    </script>
@endsection
