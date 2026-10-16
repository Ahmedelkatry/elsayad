@extends('layouts_new.app')
@section('title')
    الشيفتات | نظام إدارة الموظفين
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
                <span class="text-sky-600 dark:text-sky-500">الشيفتات</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">إدارة الشيفتات</h1>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            @can('اضافه شيفت')
                <!-- Uncomment below if you want the link instead of the modal -->
                <!-- <a href="shifts/create" class="flex items-center gap-2 px-5 py-2.5 bg-sky-500 text-white rounded-xl text-xs font-bold hover:bg-sky-400 shadow-lg shadow-sky-500/20 transition-all active:scale-95">
                    <i data-lucide="plus" class="w-4 h-4 text-white"></i>
                    إضافة شيفت
                </a> -->
                
                <a href="{{ route('shifts.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-sky-500 text-white rounded-xl text-xs font-bold hover:bg-sky-400 shadow-lg shadow-sky-500/20 transition-all active:scale-95">
                    <i data-lucide="plus" class="w-4 h-4 text-white"></i>
                    إضافة شيفت
                </a>
                <button type="button" class="btn btn-danger items-center gap-2 px-5 py-2.5 bg-red-500 text-white rounded-xl text-xs font-bold hover:bg-red-600 shadow-lg shadow-red-500/10 transition-all active:scale-95" id="delete_all_btn" style="display: none;">
                    <i data-lucide="trash-2" class="w-4 h-4 text-white"></i>
                    حذف المحدد
                </button>
            @endcan
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
        <form action="{{ route('shifts.bulk_delete') }}" method="POST" id="bulk_delete_form">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full text-center border-collapse whitespace-nowrap pixel-table-modern">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-[#111]">
                            <th class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400"># <input type="checkbox" id="select_all" class="rounded border-gray-300 text-sky-500 shadow-sm focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 ml-1"></th>
                            <th class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400">اسم الشيفت</th>
                            <th class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400">دخول</th>
                            <th class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400">خروج</th>
                            <th class="py-3 px-2 text-xs font-bold text-red-500">تأخير 1</th>
                            <th class="py-3 px-2 text-xs font-bold text-red-500">خصم بالدقيقة 1</th>
                            <th class="py-3 px-2 text-xs font-bold text-orange-500">تأخير 2</th>
                            <th class="py-3 px-2 text-xs font-bold text-orange-500">خصم بالدقيقة 2</th>
                            <th class="py-3 px-2 text-xs font-bold text-sky-600">تأخير 3</th>
                            <th class="py-3 px-2 text-xs font-bold text-sky-600">خصم بالدقيقة 3</th>
                            <th class="py-3 px-2 text-xs font-bold text-red-700">تأخير 4</th>
                            <th class="py-3 px-2 text-xs font-bold text-red-700">خصم بالدقيقة 4</th>
                            <th class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400">خروج بدري سماح</th>
                            <th class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400">خصم خروج</th>
                            <th class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400">ساعات العمل</th>
                            <th class="py-3 px-2 text-xs font-bold text-green-500">بداية الـ OT</th>
                            <th class="py-3 px-2 text-xs font-bold text-green-500">OT بالساعة</th>
                            <th class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400">الغياب</th>
                            <th class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400">العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shifts as $i => $shift)
                            <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                <td class="py-3 px-2 text-xs text-gray-900 dark:text-white">{{ $i + 1 }} <input type="checkbox" name="ids[]" value="{{ $shift->id }}" class="box1 rounded border-gray-300 text-sky-500 shadow-sm focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 ml-1"></td>
                                <td class="py-3 px-2 text-xs font-bold text-gray-900 dark:text-white">{{ $shift->name }}</td>
                                <td class="py-3 px-2 text-xs text-gray-900 dark:text-white">{{ $shift->from ? date(' g:i A', strtotime($shift->from)) : '' }}</td>
                                <td class="py-3 px-2 text-xs text-gray-900 dark:text-white">{{ $shift->to ? date(' g:i A', strtotime($shift->to)) : '' }}</td>
                                
                                <td class="py-3 px-2 text-xs text-red-500 font-bold">{{ $shift->late }}</td>
                                <td class="py-3 px-2 text-xs text-red-500">{{ $shift->dism }}</td>
                                
                                <td class="py-3 px-2 text-xs text-orange-500 font-bold">{{ $shift->late1 }}</td>
                                <td class="py-3 px-2 text-xs text-orange-500">{{ $shift->dism1 }}</td>
                                
                                <td class="py-3 px-2 text-xs text-sky-600 font-bold">{{ $shift->late2 }}</td>
                                <td class="py-3 px-2 text-xs text-sky-600">{{ $shift->dism2 }}</td>
                                
                                <td class="py-3 px-2 text-xs text-red-700 font-bold">{{ $shift->late3 }}</td>
                                <td class="py-3 px-2 text-xs text-red-700">{{ $shift->dism3 }}</td>
                                
                                <td class="py-3 px-2 text-xs text-gray-900 dark:text-white">{{ $shift->bdry }}</td>
                                <td class="py-3 px-2 text-xs text-gray-900 dark:text-white">{{ $shift->disout }}</td>
                                <td class="py-3 px-2 text-xs font-bold text-blue-500">{{ $shift->workh }}</td>
                                <td class="py-3 px-2 text-xs text-green-500 font-bold">{{ $shift->start_ot ? date(' g:i A', strtotime($shift->start_ot)) : '' }}</td>
                                <td class="py-3 px-2 text-xs text-green-500">{{ $shift->ot }}</td>
                                <td class="py-3 px-2 text-xs text-gray-900 dark:text-white">{{ $shift->absent }}</td>
                                <td class="py-3 px-2 text-sm">
                                    <div class="flex items-center justify-center gap-2">
                                        @can('تعديل شيفت')
                                            <a href="{{ route('shifts.edit', $shift->id) }}" class="p-2 bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-500 hover:bg-green-200 dark:hover:bg-green-500/20 rounded-lg transition-colors">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </a>
                                        @endcan
                                        @can('حذف شيفت')
                                            <button type="button" onclick="showPixelModal('delete-shift-modal-{{ $shift->id }}')" class="p-2 bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-500 hover:bg-red-200 dark:hover:bg-red-500/20 rounded-lg transition-colors">
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

        <!-- Delete Modals (Separated from the main form to avoid nested forms) -->
        @foreach ($shifts as $shift)
            <div id="delete-shift-modal-{{ $shift->id }}" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300 text-right">
                <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-md rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                        <h3 class="text-sm font-bold text-red-500 flex items-center gap-2">
                            <i data-lucide="alert-triangle" class="w-5 h-5"></i> تأكيد الحذف
                        </h3>
                        <button type="button" onclick="hidePixelModal('delete-shift-modal-{{ $shift->id }}')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <form action="{{ route('shifts.destroy', $shift->id) }}" method="post">
                        {{ method_field('delete') }}
                        {{ csrf_field() }}
                        <div class="p-6 space-y-4">
                            <p class="text-sm text-gray-900 dark:text-white font-bold">هل أنت متأكد من حذف هذا الشيفت؟</p>
                            <div class="bg-gray-50 dark:bg-[#111] p-4 rounded-xl border border-gray-100 dark:border-white/5 text-center">
                                <span class="text-lg text-gray-900 dark:text-white font-bold">{{ $shift->name }}</span>
                            </div>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3">
                            <button type="button" onclick="hidePixelModal('delete-shift-modal-{{ $shift->id }}')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                            <button type="submit" class="px-5 py-2.5 bg-red-500 text-white hover:bg-red-600 rounded-xl text-xs font-bold shadow-lg shadow-red-500/10 transition-all active:scale-95">تأكيد الحذف</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

        <!-- Add Shift Modal -->
        <div id="add-shift-modal" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300 text-right">
            <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
            <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-2xl rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-5 h-5 text-sky-500"></i> إضافة شيفت جديد
                    </h3>
                    <button type="button" onclick="hidePixelModal('add-shift-modal')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <form action="{{ route('shifts.store') }}" method="post">
                    {{ csrf_field() }}
                    <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                        
                        <!-- الأساسيات -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-bold text-sky-600 dark:text-sky-500 uppercase tracking-wider border-b border-gray-100 dark:border-white/5 pb-2">الأساسيات</h4>
                            
                            <div>
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">اسم الشيفت</label>
                                <input type="text" name="name" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                            </div>

                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">من (دخول)</label>
                                    <input type="time" name="from" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">إلى (خروج)</label>
                                    <input type="time" name="to" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">عدد ساعات العمل</label>
                                <input type="number" name="workh" min="0" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                            </div>
                        </div>

                        <!-- قواعد التأخير -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-bold text-sky-600 dark:text-sky-500 uppercase tracking-wider border-b border-gray-100 dark:border-white/5 pb-2">قواعد التأخير (الدخول)</h4>
                            
                            <div class="grid grid-cols-2 gap-4 bg-red-50/50 dark:bg-red-500/5 p-4 rounded-xl border border-red-100 dark:border-red-500/10">
                                <div>
                                    <label class="block text-[11px] font-bold text-red-500 mb-2">1- تأخير بالدقيقة</label>
                                    <input type="number" name="late" value="0" min="0" required class="w-full bg-white dark:bg-[#111] border border-red-200 dark:border-red-500/20 text-gray-900 dark:text-white px-3 py-2 rounded-lg outline-none focus:border-red-500 text-xs">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-red-500 mb-2">خصم بالدقيقة</label>
                                    <input type="number" name="dism" value="0" min="0" required class="w-full bg-white dark:bg-[#111] border border-red-200 dark:border-red-500/20 text-gray-900 dark:text-white px-3 py-2 rounded-lg outline-none focus:border-red-500 text-xs">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 bg-orange-50/50 dark:bg-orange-500/5 p-4 rounded-xl border border-orange-100 dark:border-orange-500/10">
                                <div>
                                    <label class="block text-[11px] font-bold text-orange-500 mb-2">2- تأخير بالدقيقة</label>
                                    <input type="number" name="late1" value="0" min="0" required class="w-full bg-white dark:bg-[#111] border border-orange-200 dark:border-orange-500/20 text-gray-900 dark:text-white px-3 py-2 rounded-lg outline-none focus:border-orange-500 text-xs">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-orange-500 mb-2">2- خصم بالدقيقة</label>
                                    <input type="number" name="dism1" value="0" min="0" required class="w-full bg-white dark:bg-[#111] border border-orange-200 dark:border-orange-500/20 text-gray-900 dark:text-white px-3 py-2 rounded-lg outline-none focus:border-orange-500 text-xs">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 bg-sky-50/50 dark:bg-sky-500/5 p-4 rounded-xl border border-sky-100 dark:border-sky-500/10">
                                <div>
                                    <label class="block text-[11px] font-bold text-sky-600 mb-2">3- تأخير بالدقيقة</label>
                                    <input type="number" name="late2" value="0" min="0" required class="w-full bg-white dark:bg-[#111] border border-sky-200 dark:border-sky-500/20 text-gray-900 dark:text-white px-3 py-2 rounded-lg outline-none focus:border-sky-500 text-xs">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-sky-600 mb-2">3- خصم بالدقيقة</label>
                                    <input type="number" name="dism2" value="0" min="0" required class="w-full bg-white dark:bg-[#111] border border-sky-200 dark:border-sky-500/20 text-gray-900 dark:text-white px-3 py-2 rounded-lg outline-none focus:border-sky-500 text-xs">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 bg-red-100/50 dark:bg-red-500/10 p-4 rounded-xl border border-red-200 dark:border-red-500/20">
                                <div>
                                    <label class="block text-[11px] font-bold text-red-700 dark:text-red-400 mb-2">4- تأخير بالدقيقة</label>
                                    <input type="number" name="late3" value="0" min="0" required class="w-full bg-white dark:bg-[#111] border border-red-300 dark:border-red-500/30 text-gray-900 dark:text-white px-3 py-2 rounded-lg outline-none focus:border-red-500 text-xs">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-red-700 dark:text-red-400 mb-2">4- خصم بالدقيقة</label>
                                    <input type="number" name="dism3" value="0" min="0" required class="w-full bg-white dark:bg-[#111] border border-red-300 dark:border-red-500/30 text-gray-900 dark:text-white px-3 py-2 rounded-lg outline-none focus:border-red-500 text-xs">
                                </div>
                            </div>
                        </div>

                        <!-- قواعد الخروج والغياب -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-bold text-sky-600 dark:text-sky-500 uppercase tracking-wider border-b border-gray-100 dark:border-white/5 pb-2">الخروج المبكر والغياب</h4>
                            
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-[11px] font-bold text-gray-500 mb-2">سماح خروج بدري (بالدقيقة)</label>
                                    <input type="number" name="bdry" value="0" min="0" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-[11px] font-bold text-gray-500 mb-2">خصم خروج بالدقيقة</label>
                                    <input type="number" name="disout" value="0" min="0" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-[11px] font-bold text-gray-500 mb-2">خصم الغياب (باليوم)</label>
                                <input type="number" name="absent" value="1" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                            </div>
                        </div>

                        <!-- قواعد الوقت الإضافي -->
                        <div class="space-y-4">
                            <h4 class="text-xs font-bold text-sky-600 dark:text-sky-500 uppercase tracking-wider border-b border-gray-100 dark:border-white/5 pb-2">الوقت الإضافي (OT)</h4>
                            
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-[11px] font-bold text-gray-500 mb-2">بداية الوقت الإضافي</label>
                                    <input type="time" name="start_ot" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-[11px] font-bold text-gray-500 mb-2">العمل الإضافي بالساعة</label>
                                    <input type="number" name="ot" value="1" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3">
                        <button type="button" onclick="hidePixelModal('add-shift-modal')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                        <button type="submit" class="px-5 py-2.5 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-xs font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95">حفظ الشيفت</button>
                    </div>
                </form>
            </div>
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
                    $("#delete_all_btn").show().css('display', 'inline-flex');
                } else {
                    $("#delete_all_btn").hide();
                }
            }

            $(document).on('click', '#delete_all_btn', function() {
                if (confirm('هل أنت متأكد من حذف السجلات المختارة؟')) {
                    $("#bulk_delete_form").submit();
                }
            });
        });
    </script>
@endsection
