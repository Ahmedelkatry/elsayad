@extends('layouts_new.app')
@section('title')
    القروض | نظام إدارة الموظفين
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
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
                <span class="text-sky-600 dark:text-sky-500">شؤون الموظفين</span>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">القروض</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">إدارة القروض</h1>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            @can('اضافه قرض')
                <button onclick="showPixelModal('add-loan-modal')" class="flex items-center gap-2 px-5 py-2.5 bg-sky-500 text-white rounded-xl text-xs font-bold hover:bg-sky-400 shadow-lg shadow-sky-500/20 transition-all active:scale-95">
                    <i data-lucide="plus" class="w-4 h-4 text-white"></i>
                    إضافة قرض
                </button>
            @endcan
            <button type="button" class="btn btn-danger items-center gap-2 px-5 py-2.5 bg-red-500 text-white rounded-xl text-xs font-bold hover:bg-red-600 shadow-lg shadow-red-500/10 transition-all active:scale-95" id="bulk_delete_btn" style="display: none;">
                <i data-lucide="trash-2" class="w-4 h-4 text-white"></i>
                حذف المحدد
            </button>
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
        <form action="{{ route('loans.bulk_delete') }}" method="POST" id="bulk_delete_form">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse pixel-table-modern">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-white/5">
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400"># <input type="checkbox" id="select_all" class="rounded border-gray-300 text-sky-500 shadow-sm focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"></th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">اسم الموظف</th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">القرض</th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">الخصم الشهري</th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">الباقي</th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">باقي شهور</th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">مدة القرض</th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">البداية</th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">النهاية</th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loans as $i => $emp)
                            <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">{{ $i + 1 }} <input type="checkbox" name="ids[]" value="{{ $emp->id }}" class="box1 rounded border-gray-300 text-sky-500 shadow-sm focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"></td>
                                <td class="py-3 px-4 text-sm font-bold text-gray-900 dark:text-white">{{ $emp->employees->name }}</td>
                                <td class="py-3 px-4 text-sm font-bold text-green-500">{{ $emp->mony }}</td>
                                <td class="py-3 px-4 text-sm text-red-500">{{ $emp->mony_now }}</td>
                                <td class="py-3 px-4 text-sm font-bold text-blue-500">{{ $emp->mony_baky }}</td>
                                <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">{{ $emp->month_baky }}</td>
                                <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">{{ $emp->month_now }}</td>
                                <td class="py-3 px-4 text-[10px] text-gray-500">{{ $emp->date_start }}</td>
                                <td class="py-3 px-4 text-[10px] text-gray-500">{{ $emp->date_end }}</td>
                                <td class="py-3 px-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        @can('تعديل قرض')
                                            <button type="button" onclick="showPixelModal('update-loan-modal-{{ $emp->id }}')" class="p-2 bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-500 hover:bg-green-200 dark:hover:bg-green-500/20 rounded-lg transition-colors">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </button>
                                        @endcan
                                        @can('حذف قرض')
                                            <button type="button" onclick="showPixelModal('delete-loan-modal-{{ $emp->id }}')" class="p-2 bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-500 hover:bg-red-200 dark:hover:bg-red-500/20 rounded-lg transition-colors">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>

        <!-- Delete & Update Modals (Separated from the main form to avoid nested forms) -->
        @foreach ($loans as $emp)
            <!-- Delete Modal -->
            <div id="delete-loan-modal-{{ $emp->id }}" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
                <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-md rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                        <h3 class="text-sm font-bold text-red-500 flex items-center gap-2">
                            <i data-lucide="alert-triangle" class="w-5 h-5"></i> تأكيد الحذف
                        </h3>
                        <button type="button" onclick="hidePixelModal('delete-loan-modal-{{ $emp->id }}')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <form action="{{ route('loans.destroy', $emp->id) }}" method="post">
                        <input type="hidden" name="id" value="{{ $emp->id }}">
                        {{ method_field('delete') }}
                        {{ csrf_field() }}
                        <div class="p-6 space-y-4">
                            <p class="text-sm text-gray-900 dark:text-white font-bold">هل أنت متأكد من حذف هذا القرض؟</p>
                            <div class="bg-gray-50 dark:bg-[#111] p-4 rounded-xl border border-gray-100 dark:border-white/5">
                                <p class="text-xs text-gray-500 mb-1">الموظف: <span class="text-gray-900 dark:text-white font-bold">{{ $emp->employees->name }}</span></p>
                                <p class="text-xs text-gray-500">إجمالي القرض: <span class="text-green-500 font-bold">{{ $emp->mony }}</span></p>
                            </div>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3">
                            <button type="button" onclick="hidePixelModal('delete-loan-modal-{{ $emp->id }}')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                            <button type="submit" class="px-5 py-2.5 bg-red-500 text-white hover:bg-red-600 rounded-xl text-xs font-bold shadow-lg shadow-red-500/10 transition-all active:scale-95">تأكيد الحذف</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Update Modal -->
            <div id="update-loan-modal-{{ $emp->id }}" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
                <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-lg rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <i data-lucide="edit" class="w-5 h-5 text-green-500"></i> تعديل قرض
                        </h3>
                        <button type="button" onclick="hidePixelModal('update-loan-modal-{{ $emp->id }}')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <form action="{{ route('loans.update', $emp->id) }}" method="post">
                        {{ method_field('patch') }}
                        {{ csrf_field() }}
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">اسم الموظف</label>
                                <select name="employees_id" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors" readonly>
                                    <option value="{{ $emp->employees->id }}">{{ $emp->employees->name }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">القرض</label>
                                <input type="number" name="mony" value="{{ $emp->mony }}" min="0" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                            </div>
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">تاريخ البداية</label>
                                    <input type="date" name="date_start" value="{{ $emp->date_start }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">تاريخ النهاية</label>
                                    <input type="date" name="date_end" value="{{ $emp->date_end }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3">
                            <button type="button" onclick="hidePixelModal('update-loan-modal-{{ $emp->id }}')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                            <button type="submit" class="px-5 py-2.5 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-xs font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95">حفظ التعديلات</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Add Modal -->
    <div id="add-loan-modal" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-lg rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="plus-circle" class="w-5 h-5 text-sky-500"></i> إضافة قرض جديد
                </h3>
                <button type="button" onclick="hidePixelModal('add-loan-modal')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('loans.store') }}" method="post">
                {{ csrf_field() }}
                <div class="p-6 space-y-5">
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">اسم الموظف</label>
                        <select name="employees_id" id="employee-select" class="form-control form-control-sm select2 w-full" required>
                            <option value="" disabled selected>ابحث واختر الموظف</option>
                            @foreach ($emps as $emp)
                                <option value="{{ $emp->id }}" {{ request()->name == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->num . ' - ' . $emp->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">مبلغ القرض الكلي</label>
                        <input type="number" name="mony" required min="0" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors" placeholder="مثال: 5000">
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">تاريخ البداية</label>
                            <input type="date" name="date_start" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                        </div>
                        <div class="flex-1">
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">تاريخ النهاية</label>
                            <input type="date" name="date_end" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3">
                    <button type="button" onclick="hidePixelModal('add-loan-modal')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                    <button type="submit" class="px-5 py-2.5 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-xs font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95">إضافة القرض</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <!-- Datatables -->
    <!-- Datatables & Select2 CDN -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Modal Handlers
        function showPixelModal(id) {
            const modal = document.getElementById(id);
            if(modal) {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modal.querySelector('.modal-content').classList.remove('scale-95');
            }
        }
        
        function hidePixelModal(id) {
            const modal = document.getElementById(id);
            if(modal) {
                modal.classList.add('opacity-0', 'pointer-events-none');
                modal.querySelector('.modal-content').classList.add('scale-95');
            }
        }

        $(document).ready(function() {
            // Initialize DataTable
            // Table is automatically initialized by js.js

            // Initialize Select2
            $('#employee-select').select2({
                placeholder: 'ابحث بالكتابة عن اسم الموظف...',
                allowClear: true,
                dir: "rtl",
                width: '100%',
                dropdownParent: $('#add-loan-modal') // Fix select2 in modal
            });

            // Bulk Delete Logic
            $(document).on('change', '#select_all', function() {
                var isChecked = $(this).prop('checked');
                $(".box1").each(function() {
                    $(this).prop('checked', isChecked);
                });
                toggleDeleteBtn();
            });

            $(document).on('change', '.box1', function() {
                var allChecked = $(".box1:checked").length === $(".box1").length;
                $("#select_all").prop('checked', allChecked);
                toggleDeleteBtn();
            });

            function toggleDeleteBtn() {
                var checkedCount = $(".box1:checked").length;
                if (checkedCount > 0) {
                    $("#bulk_delete_btn").show().css('display', 'inline-flex');
                } else {
                    $("#bulk_delete_btn").hide();
                }
            }

            $(document).on('click', '#bulk_delete_btn', function() {
                if (confirm('هل أنت متأكد من حذف السجلات المختارة؟')) {
                    $("#bulk_delete_form").submit();
                }
            });
        });
    </script>
@endsection
