@extends('layouts_new.app')
@section('title')
    إدارة الموظفين | نظام إدارة الموظفين
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
                <span class="text-sky-600 dark:text-sky-500">إدارة الموظفين</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">إدارة الموظفين</h1>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            @can('اضافه موظف')
                <button onclick="showPixelModal('add-emp-modal')" class="flex items-center gap-2 px-5 py-2.5 bg-sky-500 text-white rounded-xl text-xs font-bold hover:bg-sky-400 shadow-lg shadow-sky-500/20 transition-all active:scale-95">
                    <i data-lucide="user-plus" class="w-4 h-4 text-white"></i>
                    إضافة موظف
                </button>
            @endcan
            @can('اضافه موظف بدون بصمه')
                <button onclick="showPixelModal('add-emp-no-zkt-modal')" class="flex items-center gap-2 px-5 py-2.5 bg-blue-500 text-white rounded-xl text-xs font-bold hover:bg-blue-600 shadow-lg shadow-blue-500/10 transition-all active:scale-95">
                    <i data-lucide="user-minus" class="w-4 h-4 text-white"></i>
                    بدون بصمة
                </button>
            @endcan
            <button onclick="showPixelModal('add-emp-zkt-modal')" class="flex items-center gap-2 px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-black rounded-xl text-xs font-bold hover:bg-black dark:hover:bg-gray-100 shadow-lg transition-all active:scale-95">
                <i data-lucide="fingerprint" class="w-4 h-4"></i>
                في البصمة
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
    <div class="overflow-x-auto">
        <table class="w-full text-right border-collapse pixel-table-modern">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-[#111]">
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">#</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">رقم البصمة</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">اسم الموظف</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">القسم</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">الشيفت</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">نظام العمل</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">المرتب</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $i => $emp)
                        <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <td class="py-3 px-4 text-xs text-gray-900 dark:text-white">{{ $i + 1 }}</td>
                            <td class="py-3 px-4 text-xs font-bold text-gray-900 dark:text-white">{{ $emp->num }}</td>
                            <td class="py-3 px-4 text-xs font-bold text-gray-900 dark:text-white">{{ $emp->name }}</td>
                            <td class="py-3 px-4 text-xs text-gray-900 dark:text-white">{{ $emp->section->name }}</td>
                            <td class="py-3 px-4 text-xs text-gray-900 dark:text-white">{{ $emp->shift->name }}</td>
                            <td class="py-3 px-4 text-xs text-gray-900 dark:text-white">{{ $emp->sysemp }}</td>
                            <td class="py-3 px-4 text-xs font-bold text-sky-600">{{ $emp->kabd }}</td>
                            <td class="py-3 px-4 text-xs">
                                <div class="flex items-center gap-2">
                                    @can('تعديل موظف')
                                        <button type="button" onclick="showPixelModal('update-emp-modal-{{ $emp->id }}')" class="p-2 bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-500 hover:bg-green-200 dark:hover:bg-green-500/20 rounded-lg transition-colors">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </button>
                                    @endcan
                                    @can('حذف موظف')
                                        <button type="button" onclick="showPixelModal('delete-emp-modal-{{ $emp->id }}')" class="p-2 bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-500 hover:bg-red-200 dark:hover:bg-red-500/20 rounded-lg transition-colors">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>

                        <!-- Delete Modal -->
                        <div id="delete-emp-modal-{{ $emp->id }}" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
                            <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                            <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-md rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
                                <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                                    <h3 class="text-sm font-bold text-red-500 flex items-center gap-2">
                                        <i data-lucide="alert-triangle" class="w-5 h-5"></i> تأكيد الحذف
                                    </h3>
                                    <button type="button" onclick="hidePixelModal('delete-emp-modal-{{ $emp->id }}')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                                        <i data-lucide="x" class="w-5 h-5"></i>
                                    </button>
                                </div>
                                <form action="{{ route('employees.destroy', $emp->id) }}" method="post">
                                    @method('delete')
                                    @csrf
                                    <div class="p-6 space-y-4">
                                        <p class="text-sm text-gray-900 dark:text-white font-bold">هل أنت متأكد من حذف هذا الموظف؟</p>
                                        <div class="bg-gray-50 dark:bg-[#111] p-4 rounded-xl border border-gray-100 dark:border-white/5">
                                            <p class="text-xs text-gray-500 mb-1">الموظف: <span class="text-gray-900 dark:text-white font-bold">{{ $emp->name }}</span></p>
                                            <p class="text-xs text-gray-500">رقم البصمة: <span class="text-gray-900 dark:text-white font-bold">{{ $emp->num }}</span></p>
                                        </div>
                                        <div>
                                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">Ip البصمة</label>
                                            <input type="text" name="ipz" value="192.168.1.201" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-red-500/50 text-xs transition-colors">
                                            <input type="hidden" name="idzkt" value="{{ $emp->num }}">
                                            <input type="hidden" name="idd" value="{{ $emp->id }}">
                                            <input type="hidden" name="email" value="{{ $emp->email }}">
                                        </div>
                                    </div>
                                    <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3">
                                        <button type="button" onclick="hidePixelModal('delete-emp-modal-{{ $emp->id }}')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                                        <button type="submit" class="px-5 py-2.5 bg-red-500 text-white hover:bg-red-600 rounded-xl text-xs font-bold shadow-lg shadow-red-500/10 transition-all active:scale-95">تأكيد الحذف</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Update Modal -->
                        <div id="update-emp-modal-{{ $emp->id }}" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
                            <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                            <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-2xl rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
                                <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                        <i data-lucide="edit" class="w-5 h-5 text-green-500"></i> تعديل بيانات الموظف
                                    </h3>
                                    <button type="button" onclick="hidePixelModal('update-emp-modal-{{ $emp->id }}')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                                        <i data-lucide="x" class="w-5 h-5"></i>
                                    </button>
                                </div>
                                <form action="{{ route('employees.update', $emp->id) }}" method="post" class="overflow-y-auto max-h-[70vh]">
                                    @method('patch')
                                    @csrf
                                    <div class="p-6 space-y-4">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">رقم البصمة</label>
                                                <input type="number" name="num" value="{{ $emp->num }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">اسم الموظف</label>
                                                <input type="text" name="name" value="{{ $emp->name }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">العمر</label>
                                                <input type="number" name="age" value="{{ $emp->age }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الموبايل</label>
                                                <input type="number" name="mobile" value="{{ $emp->mobile }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">القسم</label>
                                                <select name="section_id" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                                    <option value="{{ $emp->section->id }}">{{ $emp->section->name }}</option>
                                                    @foreach ($sections as $sec)
                                                        @if($sec->id != $emp->section->id)
                                                            <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الشيفت</label>
                                                <select name="shift_id" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                                    <option value="{{ $emp->shift->id }}">{{ $emp->shift->name }}</option>
                                                    @foreach ($shifts as $shift)
                                                        @if($shift->id != $emp->shift->id)
                                                            <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">أيام الإجازة</label>
                                            <div class="flex flex-wrap gap-4 bg-gray-50 dark:bg-white/5 p-4 rounded-xl border border-gray-100 dark:border-white/10">
                                                @php $days = ['الجمعة', 'السبت', 'الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس']; @endphp
                                                @foreach($days as $day)
                                                    <label class="flex items-center gap-2 cursor-pointer group">
                                                        <input type="checkbox" name="free_d[]" value="{{ $day }}" {{ is_array($emp->free_d) && in_array($day, $emp->free_d) ? 'checked' : '' }} class="rounded border-gray-300 text-sky-500 shadow-sm focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                                        <span class="text-xs text-gray-600 dark:text-gray-400 group-hover:text-sky-600 transition-colors">{{ $day }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">نظام اليوم</label>
                                                <input type="number" name="sysemp" value="{{ $emp->sysemp }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">إجازة OT</label>
                                                <input type="number" name="friday" value="{{ $emp->friday ?? 0 }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">المرتب</label>
                                            <input type="number" name="kabd" value="{{ $emp->kabd }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">العنوان</label>
                                            <textarea name="title" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors" rows="2">{{ $emp->title }}</textarea>
                                        </div>
                                    </div>
                                    <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3 sticky bottom-0">
                                        <button type="button" onclick="hidePixelModal('update-emp-modal-{{ $emp->id }}')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                                        <button type="submit" class="px-5 py-2.5 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-xs font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95">حفظ التعديلات</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Modal (Regular) -->
    <div id="add-emp-modal" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-2xl rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-5 h-5 text-sky-500"></i> إضافة موظف جديد
                </h3>
                <button type="button" onclick="hidePixelModal('add-emp-modal')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('employees.store') }}" method="post" class="overflow-y-auto max-h-[70vh]">
                @csrf
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">رقم البصمة</label>
                            <input type="number" name="num" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">اسم الموظف</label>
                            <input type="text" name="name" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">العمر</label>
                            <input type="number" name="age" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الموبايل</label>
                            <input type="number" name="mobile" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">القسم</label>
                            <select name="section_id" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                @foreach ($sections as $sec)
                                    <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الشيفت</label>
                            <select name="shift_id" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                @foreach ($shifts as $shift)
                                    <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">أيام الإجازة</label>
                        <div class="flex flex-wrap gap-4 bg-gray-50 dark:bg-white/5 p-4 rounded-xl border border-gray-100 dark:border-white/10">
                            @foreach(['الجمعة', 'السبت', 'الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس'] as $day)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" name="free_d[]" value="{{ $day }}" {{ $day == 'الجمعة' ? 'checked' : '' }} class="rounded border-gray-300 text-sky-500 shadow-sm focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                    <span class="text-xs text-gray-600 dark:text-gray-400 group-hover:text-sky-600 transition-colors">{{ $day }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">نظام اليوم</label>
                            <input type="number" name="sysemp" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">إجازة OT</label>
                            <input type="number" name="friday" value="0" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">المرتب</label>
                        <input type="number" name="kabd" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">العنوان</label>
                        <textarea name="title" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors" rows="2"></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3 sticky bottom-0">
                    <button type="button" onclick="hidePixelModal('add-emp-modal')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                    <button type="submit" class="px-5 py-2.5 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-xs font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95">إضافة الموظف</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Modal (No ZKT) -->
    <div id="add-emp-no-zkt-modal" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-2xl rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                <h3 class="text-sm font-bold text-blue-500 flex items-center gap-2">
                    <i data-lucide="user-minus" class="w-5 h-5 text-blue-500"></i> إضافة موظف بدون بصمة
                </h3>
                <button type="button" onclick="hidePixelModal('add-emp-no-zkt-modal')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('store_nozkt') }}" method="post" class="overflow-y-auto max-h-[70vh]">
                @csrf
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">رقم البصمة</label>
                            <input type="number" name="num" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-blue-500/50 text-xs transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">اسم الموظف</label>
                            <input type="text" name="name" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-blue-500/50 text-xs transition-colors">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">البريد الإلكتروني</label>
                            <input type="email" name="email" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-blue-500/50 text-xs transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">كلمة المرور</label>
                            <input type="password" name="password" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-blue-500/50 text-xs transition-colors">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">العمر</label>
                            <input type="number" name="age" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-blue-500/50 text-xs transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الموبايل</label>
                            <input type="number" name="mobile" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-blue-500/50 text-xs transition-colors">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">القسم</label>
                            <select name="section_id" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-blue-500/50 text-xs transition-colors">
                                @foreach ($sections as $sec)
                                    <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الشيفت</label>
                            <select name="shift_id" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-blue-500/50 text-xs transition-colors">
                                @foreach ($shifts as $shift)
                                    <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">أيام الإجازة</label>
                        <div class="flex flex-wrap gap-4 bg-gray-50 dark:bg-white/5 p-4 rounded-xl border border-gray-100 dark:border-white/10">
                            @foreach(['الجمعة', 'السبت', 'الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس'] as $day)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" name="free_d[]" value="{{ $day }}" {{ $day == 'الجمعة' ? 'checked' : '' }} class="rounded border-gray-300 text-blue-500 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="text-xs text-gray-600 dark:text-gray-400 group-hover:text-blue-600 transition-colors">{{ $day }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">نظام اليوم</label>
                            <input type="number" name="sysemp" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-blue-500/50 text-xs transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">إجازة OT</label>
                            <input type="number" name="friday" value="0" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-blue-500/50 text-xs transition-colors">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">المرتب</label>
                        <input type="number" name="kabd" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-blue-500/50 text-xs transition-colors">
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">العنوان</label>
                        <textarea name="title" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-blue-500/50 text-xs transition-colors" rows="2"></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3 sticky bottom-0">
                    <button type="button" onclick="hidePixelModal('add-emp-no-zkt-modal')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-500 text-white hover:bg-blue-600 rounded-xl text-xs font-bold shadow-lg shadow-blue-500/10 transition-all active:scale-95">إضافة الموظف</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Modal (ZKT) -->
    <div id="add-emp-zkt-modal" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-2xl rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="fingerprint" class="w-5 h-5 text-gray-900 dark:text-white"></i> إضافة موظف في البصمة
                </h3>
                <button type="button" onclick="hidePixelModal('add-emp-zkt-modal')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('store_addzkt') }}" method="post" class="overflow-y-auto max-h-[70vh]">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">ip البصمة</label>
                        <input type="text" name="ipz" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">رقم البصمة</label>
                            <input type="number" name="num" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">اسم الموظف</label>
                            <input type="text" name="name" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">كلمة المرور (اختياري)</label>
                        <input type="password" name="password" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">العمر</label>
                            <input type="number" name="age" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الموبايل</label>
                            <input type="number" name="mobile" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">القسم</label>
                            <select name="section_id" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                @foreach ($sections as $sec)
                                    <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الشيفت</label>
                            <select name="shift_id" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                @foreach ($shifts as $shift)
                                    <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">أيام الإجازة</label>
                        <div class="flex flex-wrap gap-4 bg-gray-50 dark:bg-white/5 p-4 rounded-xl border border-gray-100 dark:border-white/10">
                            @foreach(['الجمعة', 'السبت', 'الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس'] as $day)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="checkbox" name="free_d[]" value="{{ $day }}" {{ $day == 'الجمعة' ? 'checked' : '' }} class="rounded border-gray-300 text-sky-500 shadow-sm focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                    <span class="text-xs text-gray-600 dark:text-gray-400 group-hover:text-sky-600 transition-colors">{{ $day }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">نظام اليوم</label>
                            <input type="number" name="sysemp" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">إجازة OT</label>
                            <input type="number" name="friday" value="0" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">المرتب</label>
                        <input type="number" name="kabd" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">العنوان</label>
                        <textarea name="title" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors" rows="2"></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3 sticky bottom-0">
                    <button type="button" onclick="hidePixelModal('add-emp-zkt-modal')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                    <button type="submit" class="px-5 py-2.5 bg-gray-900 dark:bg-white text-white dark:text-black hover:bg-black dark:hover:bg-gray-100 rounded-xl text-xs font-bold shadow-lg transition-all active:scale-95">إضافة الموظف</button>
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
            // Table is automatically initialized by js.js
        });
    </script>
@endsection
