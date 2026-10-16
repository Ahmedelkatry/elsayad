@extends('layouts_new.app')
@section('title')
    سجلات البصمة | نظام إدارة الموظفين
@endsection

@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
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
                <span class="text-sky-600 dark:text-sky-500">الإعدادات</span>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">سجلات البصمة</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">إدارة سجلات البصمة</h1>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            @can('اضافه بصمه')
                <button onclick="showPixelModal('add-count-modal')" class="flex items-center gap-2 px-5 py-2.5 bg-sky-500 text-white rounded-xl text-xs font-bold hover:bg-sky-400 shadow-lg shadow-sky-500/20 transition-all active:scale-95">
                    <i data-lucide="plus" class="w-4 h-4 text-white"></i>
                    إضافة سجل يدوي
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
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse pixel-table-modern">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-[#111]">
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">#</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">الموظف</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">التاريخ</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">دخول</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">خروج</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400 text-center">خصم دخول</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400 text-center">خصم خروج</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400 text-center">تأخير</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400 text-center">ساعات العمل</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400 text-center">OT</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400 text-center">الغياب</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; ?>
                    @foreach ($zktcounts as $count)
                        <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors {{ $count->updated_at != $count->created_at ? 'bg-sky-500/5' : '' }}">
                            <td class="py-3 px-4 text-xs {{ $count->updated_at != $count->created_at ? 'text-sky-600' : 'text-gray-900 dark:text-white' }} font-bold">{{ ++$i }}</td>
                            <td class="py-3 px-4 text-xs font-bold text-gray-900 dark:text-white">{{ $count->name }}</td>
                            <td class="py-3 px-4 text-xs text-gray-900 dark:text-white font-mono">{{ $count->date }}</td>
                            <td class="py-3 px-4 text-xs text-gray-900 dark:text-white font-mono">{{ $count->in }}</td>
                            <td class="py-3 px-4 text-xs text-gray-900 dark:text-white font-mono">{{ $count->out }}</td>
                            <td class="py-3 px-4 text-center text-xs font-bold text-red-500">{{ round($count->dis, 2) }}</td>
                            <td class="py-3 px-4 text-center text-xs font-bold text-red-500">{{ round($count->disout, 2) }}</td>
                            <td class="py-3 px-4 text-center text-xs font-bold text-red-600">{{ $count->early }}</td>
                            <td class="py-3 px-4 text-center text-xs font-bold text-green-600">{{ Carbon\Carbon::parse($count->dayh)->format("H:i:s") }}</td>
                            <td class="py-3 px-4 text-center text-xs font-bold text-green-500">{{ Carbon\Carbon::parse($count->othours)->format("H:i:s") }}</td>
                            <td class="py-3 px-4 text-center">
                                @if($count->absent)
                                    <span class="px-2 py-0.5 bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-500 rounded text-[9px] font-bold">غياب</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-sm">
                                <div class="flex items-center gap-2">
                                    @can('تعديل بصمه')
                                        <button onclick="showPixelModal('update-count-modal-{{ $count->id }}')" class="p-2 bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-500 hover:bg-green-200 dark:hover:bg-green-500/20 rounded-lg transition-colors">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </button>
                                    @endcan
                                    @can('حذف بصمه')
                                        <button onclick="showPixelModal('delete-count-modal-{{ $count->id }}')" class="p-2 bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-500 hover:bg-red-200 dark:hover:bg-red-500/20 rounded-lg transition-colors">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>

                        <!-- Update Modal -->
                        <div id="update-count-modal-{{ $count->id }}" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
                            <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                            <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-md rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
                                <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent text-right">
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                        <i data-lucide="edit" class="w-5 h-5 text-green-500"></i> تعديل سجل: {{ $count->name }}
                                    </h3>
                                    <button type="button" onclick="hidePixelModal('update-count-modal-{{ $count->id }}')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                                        <i data-lucide="x" class="w-5 h-5"></i>
                                    </button>
                                </div>
                                <form action="{{ route('counts.update', $count->id) }}" method="post">
                                    {{ method_field('patch') }}
                                    {{ csrf_field() }}
                                    <div class="p-6 space-y-4">
                                        <div class="flex gap-4">
                                            <div class="flex-1">
                                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الدخول</label>
                                                <input type="text" disabled value="{{date(' g:i A', strtotime($count->in))}}" class="w-full bg-gray-100 dark:bg-[#111]/50 border border-gray-200 dark:border-white/5 text-gray-500 px-4 py-2.5 rounded-xl text-xs font-mono">
                                            </div>
                                            <div class="flex-1">
                                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الخروج</label>
                                                <input type="text" disabled value="{{date(' g:i A', strtotime($count->out))}}" class="w-full bg-gray-100 dark:bg-[#111]/50 border border-gray-200 dark:border-white/5 text-gray-500 px-4 py-2.5 rounded-xl text-xs font-mono">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">خصم دخول</label>
                                                <input type="number" name="dis" step="0.01" value="{{ round($count->dis, 2) }}" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">خصم خروج</label>
                                                <input type="number" name="disout" step="0.01" value="{{ round($count->disout, 2) }}" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                            </div>
                                        </div>
                                        <input type="hidden" name="early" value="{{ $count->early }}">
                                        <input type="hidden" name="othours" value="{{ Carbon\Carbon::parse($count->othours)->format('H:i:s') }}">
                                        
                                        <div>
                                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الغياب / الحضور</label>
                                            <select name="absent" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors appearance-none">
                                                <option value="1" {{ $count->absent ? 'selected' : '' }}>غياب</option>
                                                <option value="0" {{ !$count->absent ? 'selected' : '' }}>حضور</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3">
                                        <button type="button" onclick="hidePixelModal('update-count-modal-{{ $count->id }}')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                                        <button type="submit" class="px-5 py-2.5 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-xs font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95">تأكيد التعديل</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Delete Modal -->
                        <div id="delete-count-modal-{{ $count->id }}" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
                            <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                            <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-md rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col text-right">
                                <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                                    <h3 class="text-sm font-bold text-red-500 flex items-center gap-2">
                                        <i data-lucide="alert-triangle" class="w-5 h-5"></i> تأكيد الحذف
                                    </h3>
                                    <button type="button" onclick="hidePixelModal('delete-count-modal-{{ $count->id }}')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                                        <i data-lucide="x" class="w-5 h-5"></i>
                                    </button>
                                </div>
                                <form action="{{ route('counts.destroy', $count->id) }}" method="post">
                                    {{ method_field('delete') }}
                                    {{ csrf_field() }}
                                    <div class="p-6 space-y-4">
                                        <p class="text-sm text-gray-900 dark:text-white font-bold">هل أنت متأكد من حذف هذا السجل؟</p>
                                        <div class="bg-gray-50 dark:bg-[#111] p-4 rounded-xl border border-gray-100 dark:border-white/5">
                                            <p class="text-lg text-gray-900 dark:text-white font-bold">{{ $count->name }}</p>
                                            <p class="text-xs text-gray-500 mt-1">تاريخ: {{ $count->date }}</p>
                                        </div>
                                    </div>
                                    <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3">
                                        <button type="button" onclick="hidePixelModal('delete-count-modal-{{ $count->id }}')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                                        <button type="submit" class="px-5 py-2.5 bg-red-500 text-white hover:bg-red-600 rounded-xl text-xs font-bold shadow-lg shadow-red-500/10 transition-all active:scale-95">تأكيد الحذف</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Count Modal -->
    <div id="add-count-modal" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-md rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="plus-circle" class="w-5 h-5 text-sky-500"></i> إضافة سجل جديد
                </h3>
                <button type="button" onclick="hidePixelModal('add-count-modal')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('zktstore') }}" method="post">
                {{ csrf_field() }}
                <div class="p-6 space-y-4 text-right">
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">اسم الموظف</label>
                        <select name="name" class="form-control select2 w-full" required>
                            <option value="" disabled selected>اختر الموظف</option>
                            @foreach ($emps as $emp)
                                <option value="{{ $emp->name }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">التاريخ</label>
                        <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors font-mono">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الدخول</label>
                            <input type="time" name="in" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors font-mono">
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الخروج</label>
                            <input type="time" name="out" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors font-mono">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الحالة</label>
                        <select name="absent" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors appearance-none">
                            <option value="0">حضور</option>
                            <option value="1">غياب</option>
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3">
                    <button type="button" onclick="hidePixelModal('add-count-modal')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                    <button type="submit" class="px-5 py-2.5 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-xs font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95">تأكيد الإضافة</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Table is automatically initialized by js.js

            $('.select2').select2({
                placeholder: 'اختر الموظف...',
                dir: 'rtl',
                width: '100%'
            });
        });
    </script>
@endsection
