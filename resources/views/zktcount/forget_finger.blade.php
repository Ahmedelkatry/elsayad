@extends('layouts_new.app')
@section('title')
    نسيان البصمة | نظام إدارة الموظفين
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
                <span class="text-sky-600 dark:text-sky-500">الإعدادات</span>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">نسيان البصمة</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">إدارة نسيان البصمة</h1>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            @can("اضافه بصمه موظف")
                <button onclick="showPixelModal('add-finger-modal')" class="flex items-center gap-2 px-5 py-2.5 bg-sky-500 text-white rounded-xl text-xs font-bold hover:bg-sky-400 shadow-lg shadow-sky-500/20 transition-all active:scale-95">
                    <i data-lucide="plus" class="w-4 h-4 text-white"></i>
                    إضافة بصمة
                </button>
                <a href="{{ route('forget_finger.multi') }}" class="flex items-center gap-2 px-5 py-2.5 bg-blue-500 text-white rounded-xl text-xs font-bold hover:bg-blue-600 shadow-lg shadow-blue-500/10 transition-all active:scale-95">
                    <i data-lucide="users" class="w-4 h-4 text-white"></i>
                    إضافة بصمات متعددة
                </a>
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

    @if (session()->has('Add'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong>{{ session()->get('Add') }}</strong>
        </div>
    @endif
    @if (session()->has('delete'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong>{{ session()->get('delete') }}</strong>
        </div>
    @endif
    @if (session()->has('edit'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong>{{ session()->get('edit') }}</strong>
        </div>
    @endif
@endsection

@section('content')
    <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-6 shadow-sm">
        <form action="{{ route('forget_finger.bulk_delete') }}" method="POST" id="bulk_delete_form">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse pixel-table-modern">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-white/5">
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400"># <input type="checkbox" id="select_all" class="rounded border-gray-300 text-sky-500 shadow-sm focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"></th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">اسم الموظف</th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">التاريخ</th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">الدخول</th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">الخروج</th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($forget as $i => $count)
                            <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">{{ $i + 1 }} <input type="checkbox" name="ids[]" value="{{ $count->id }}" class="box1 rounded border-gray-300 text-sky-500 shadow-sm focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50"></td>
                                <td class="py-3 px-4 text-sm font-bold text-gray-900 dark:text-white">{{ $count->employees->name }}</td>
                                <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">{{ $count->date }}</td>
                                <td class="py-3 px-4 text-sm text-green-500 font-bold">{{ $count->in }}</td>
                                <td class="py-3 px-4 text-sm text-red-500 font-bold">{{ $count->out }}</td>
                                <td class="py-3 px-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        @can('تعديل بصمه موظف')
                                            <button type="button" onclick="showPixelModal('update-finger-modal-{{ $count->id }}')" class="p-2 bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-500 hover:bg-green-200 dark:hover:bg-green-500/20 rounded-lg transition-colors">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </button>
                                        @endcan
                                        @can('حذف بصمه موظف')
                                            <button type="button" onclick="showPixelModal('delete-finger-modal-{{ $count->id }}')" class="p-2 bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-500 hover:bg-red-200 dark:hover:bg-red-500/20 rounded-lg transition-colors">
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
        @foreach ($forget as $count)
            <!-- Delete Modal -->
            <div id="delete-finger-modal-{{ $count->id }}" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
                <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-md rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                        <h3 class="text-sm font-bold text-red-500 flex items-center gap-2">
                            <i data-lucide="alert-triangle" class="w-5 h-5"></i> تأكيد الحذف
                        </h3>
                        <button type="button" onclick="hidePixelModal('delete-finger-modal-{{ $count->id }}')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <form action="{{ route('forget_finger.destroy', $count->id) }}" method="post">
                        {{ method_field('delete') }}
                        {{ csrf_field() }}
                        <div class="p-6 space-y-4">
                            <p class="text-sm text-gray-900 dark:text-white font-bold">هل أنت متأكد من حذف هذا السجل؟</p>
                            <div class="bg-gray-50 dark:bg-[#111] p-4 rounded-xl border border-gray-100 dark:border-white/5">
                                <p class="text-xs text-gray-500 mb-1">الموظف: <span class="text-gray-900 dark:text-white font-bold">{{ $count->employees->name }}</span></p>
                                <p class="text-xs text-gray-500">التاريخ: <span class="text-blue-500 font-bold">{{ $count->date }}</span></p>
                            </div>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3">
                            <button type="button" onclick="hidePixelModal('delete-finger-modal-{{ $count->id }}')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                            <button type="submit" class="px-5 py-2.5 bg-red-500 text-white hover:bg-red-600 rounded-xl text-xs font-bold shadow-lg shadow-red-500/10 transition-all active:scale-95">تأكيد الحذف</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Update Modal -->
            <div id="update-finger-modal-{{ $count->id }}" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
                <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-lg rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <i data-lucide="edit" class="w-5 h-5 text-green-500"></i> تعديل سجل البصمة
                        </h3>
                        <button type="button" onclick="hidePixelModal('update-finger-modal-{{ $count->id }}')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <form action="{{ route('forget_finger.update', $count->id) }}" method="post">
                        {{ method_field('patch') }}
                        {{ csrf_field() }}
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">اسم الموظف</label>
                                <select name="employees_id" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors" readonly>
                                    <option value="{{ $count->employees->id }}">{{ $count->employees->name }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">التاريخ</label>
                                <input type="date" name="date" value="{{ $count->date }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                            </div>
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الدخول</label>
                                    <input type="time" name="in" value="{{ $count->in }}" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الخروج</label>
                                    <input type="time" name="out" value="{{ $count->out }}" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3">
                            <button type="button" onclick="hidePixelModal('update-finger-modal-{{ $count->id }}')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                            <button type="submit" class="px-5 py-2.5 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-xs font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95">حفظ التعديلات</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Add Modal -->
    <div id="add-finger-modal" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-lg rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="plus-circle" class="w-5 h-5 text-sky-500"></i> إضافة بصمة جديدة
                </h3>
                <button type="button" onclick="hidePixelModal('add-finger-modal')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('forget_finger.store') }}" method="post">
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
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">التاريخ</label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الدخول</label>
                            <input type="time" name="in" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                        </div>
                        <div class="flex-1">
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الخروج</label>
                            <input type="time" name="out" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3">
                    <button type="button" onclick="hidePixelModal('add-finger-modal')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                    <button type="submit" class="px-5 py-2.5 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-xs font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95">إضافة البصمة</button>
                </div>
            </form>
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
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>

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
                dropdownParent: $('#add-finger-modal') // Fix select2 in modal
            });

            // Bulk Delete Logic
            $("#select_all").click(function(e) {
                e.stopPropagation();
                $(".box1").prop('checked', $(this).prop('checked'));
                toggleDeleteBtn();
            });

            $(document).on('change', '.box1', function() {
                toggleDeleteBtn();
            });

            function toggleDeleteBtn() {
                if ($(".box1:checked").length > 0) {
                    $("#bulk_delete_btn").removeClass("hidden").addClass("flex");
                } else {
                    $("#bulk_delete_btn").addClass("hidden").removeClass("flex");
                }
            }

            $("#bulk_delete_btn").click(function() {
                if (confirm('هل أنت متأكد من حذف السجلات المختارة؟')) {
                    $("#bulk_delete_form").submit();
                }
            });
        });
    </script>
@endsection
