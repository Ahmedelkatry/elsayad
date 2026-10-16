@extends('layouts_new.app')
@section('title')
    الأذونات | نظام إدارة الموظفين
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
        
        /* Custom Radio & Checkbox Styles for Tailwind */
        .custom-radio {
            appearance: none;
            background-color: #fff;
            margin: 0;
            font: inherit;
            color: currentColor;
            width: 1.15em;
            height: 1.15em;
            border: 0.15em solid currentColor;
            border-radius: 50%;
            display: grid;
            place-content: center;
        }
        .dark .custom-radio {
            background-color: #111;
        }
        .custom-radio::before {
            content: "";
            width: 0.65em;
            height: 0.65em;
            border-radius: 50%;
            transform: scale(0);
            transition: 120ms transform ease-in-out;
            box-shadow: inset 1em 1em var(--tw-ring-color);
            background-color: CanvasText;
        }
        .custom-radio:checked::before {
            transform: scale(1);
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
                <span class="text-sky-600 dark:text-sky-500">الأذونات</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">إدارة الأذونات</h1>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            @can('اضافه اذن')
                <button onclick="showPixelModal('add-please-modal')" class="flex items-center gap-2 px-5 py-2.5 bg-sky-500 text-white rounded-xl text-xs font-bold hover:bg-sky-400 shadow-lg shadow-sky-500/20 transition-all active:scale-95">
                    <i data-lucide="plus" class="w-4 h-4 text-white"></i>
                    إضافة إذن
                </button>
                <a href="{{ route('please.multi') }}" class="flex items-center gap-2 px-5 py-2.5 bg-blue-500 text-white rounded-xl text-xs font-bold hover:bg-blue-600 shadow-lg shadow-blue-500/10 transition-all active:scale-95">
                    <i data-lucide="clock" class="w-4 h-4 text-white"></i>
                    إضافة أذونات متعددة
                </a>
            @endcan
            <button type="button" style="display: none;" class="btn btn-danger items-center gap-2 px-5 py-2.5 bg-red-500 text-white rounded-xl text-xs font-bold hover:bg-red-600 shadow-lg shadow-red-500/10 transition-all active:scale-95" id="bulk_delete_btn">
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
    <div id="vue-app" class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-6 shadow-sm">
        <form action="{{ route('please.bulk_delete') }}" method="POST" id="bulk_delete_form">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse pixel-table-modern">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-white/5">
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400"># <input type="checkbox" id="select_all" class="rounded border-gray-300 text-sky-500 shadow-sm focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 ml-2"></th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">اسم الموظف</th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">التاريخ</th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">نوع الإذن</th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">مدة الإذن</th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">خصم دقيقة</th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">خصم قيمة</th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">السبب</th>
                            <th class="py-3 px-4 text-sm font-bold text-gray-500 dark:text-gray-400">العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plzs as $i => $shf)
                            <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">{{ $i + 1 }} <input type="checkbox" name="ids[]" value="{{ $shf->id }}" class="box1 rounded border-gray-300 text-sky-500 shadow-sm focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 ml-2"></td>
                                <td class="py-3 px-4 text-sm font-bold text-gray-900 dark:text-white">{{ $shf->employees->name }}</td>
                                <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">{{ $shf->date }}</td>
                                <td class="py-3 px-4 text-sm">
                                    <span class="px-2 py-1 {{ $shf->in_out === 0 ? 'bg-green-100 text-green-600 dark:bg-green-500/10 dark:text-green-500' : 'bg-orange-100 text-orange-600 dark:bg-orange-500/10 dark:text-orange-500' }} rounded text-xs font-bold">
                                        {{ $shf->in_out === 0 ? 'دخول' : 'خروج' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">{{ $shf->minutes }} دقيقة</td>
                                <td class="py-3 px-4 text-sm font-bold text-red-500">{{ $shf->dis }}</td>
                                <td class="py-3 px-4 text-sm font-bold text-red-500">{{ $shf->dis_value }}</td>
                                <td class="py-3 px-4 text-sm text-gray-900 dark:text-white">
                                    <span class="truncate block max-w-[150px]">{{ $shf->dec }}</span>
                                </td>
                                <td class="py-3 px-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        @can('تعديل اذن')
                                            <button type="button" onclick="showPixelModal('update-please-modal-{{ $shf->id }}')" class="p-2 bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-500 hover:bg-green-200 dark:hover:bg-green-500/20 rounded-lg transition-colors">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </button>
                                        @endcan
                                        @can('حذف اذن')
                                            <button type="button" onclick="showPixelModal('delete-please-modal-{{ $shf->id }}')" class="p-2 bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-500 hover:bg-red-200 dark:hover:bg-red-500/20 rounded-lg transition-colors">
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

        {{-- Modals خارج الـ form تماماً --}}
        @foreach ($plzs as $shf)
            <!-- Delete Modal -->
            <div id="delete-please-modal-{{ $shf->id }}" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
                <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-md rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                        <h3 class="text-sm font-bold text-red-500 flex items-center gap-2">
                            <i data-lucide="alert-triangle" class="w-5 h-5"></i> تأكيد الحذف
                        </h3>
                        <button type="button" onclick="hidePixelModal('delete-please-modal-{{ $shf->id }}')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <form action="{{ route('ple.destroy', $shf->id) }}" method="post">
                        {{ method_field('delete') }}
                        {{ csrf_field() }}
                        <div class="p-6 space-y-4">
                            <p class="text-sm text-gray-900 dark:text-white font-bold">هل أنت متأكد من حذف هذا الإذن؟</p>
                            <div class="bg-gray-50 dark:bg-[#111] p-4 rounded-xl border border-gray-100 dark:border-white/5">
                                <p class="text-xs text-gray-500 mb-1">الموظف: <span class="text-gray-900 dark:text-white font-bold">{{ $shf->employees->name }}</span></p>
                                <p class="text-xs text-gray-500">التاريخ: <span class="text-blue-500 font-bold">{{ $shf->date }}</span></p>
                            </div>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3">
                            <button type="button" onclick="hidePixelModal('delete-please-modal-{{ $shf->id }}')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                            <button type="submit" class="px-5 py-2.5 bg-red-500 text-white hover:bg-red-600 rounded-xl text-xs font-bold shadow-lg shadow-red-500/10 transition-all active:scale-95">تأكيد الحذف</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Update Modal -->
            <div id="update-please-modal-{{ $shf->id }}" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
                <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-lg rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <i data-lucide="edit" class="w-5 h-5 text-green-500"></i> تعديل الإذن
                        </h3>
                        <button type="button" onclick="hidePixelModal('update-please-modal-{{ $shf->id }}')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <form action="{{ route('ple.update', $shf->id) }}" method="post">
                        {{ method_field('patch') }}
                        {{ csrf_field() }}
                        <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                            <div>
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">اسم الموظف</label>
                                <select required name="employees_id" class="form-control form-control-sm select2 w-full">
                                    @foreach ($emps as $emp)
                                        <option value="{{ $emp->id }}" {{ $shf->employees_id == $emp->id ? 'selected' : '' }}>{{ $emp->num . ' - ' . $emp->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">التاريخ</label>
                                    <input type="date" name="date" value="{{ $shf->date }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">مدة الإذن بالدقيقة</label>
                                    <input type="number" name="minutes" value="{{ $shf->minutes }}" min="0" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">استئذان (دخول / خروج)</label>
                                <div class="flex items-center gap-6 bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 px-4 py-3 rounded-xl">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="in_out" value="0" {{ $shf->in_out == 0 ? 'checked' : '' }} required class="custom-radio text-sky-500 focus:ring-sky-500">
                                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">دخول</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="in_out" value="1" {{ $shf->in_out == 1 ? 'checked' : '' }} required class="custom-radio text-sky-500 focus:ring-sky-500">
                                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">خروج</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">نوع الخصم</label>
                                <div class="flex items-center gap-6 bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 px-4 py-3 rounded-xl">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" value="dis" v-model="edit_dis_types['{{ $shf->id }}']" @change="switchType('{{ $shf->id }}', 'dis')" class="custom-radio text-sky-500 focus:ring-sky-500">
                                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">خصم بالدقيقة</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" value="dis_value" v-model="edit_dis_types['{{ $shf->id }}']" @change="switchType('{{ $shf->id }}', 'dis_value')" class="custom-radio text-sky-500 focus:ring-sky-500">
                                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">خصم قيمة</span>
                                    </label>
                                </div>
                            </div>

                            <div v-show="edit_dis_types['{{ $shf->id }}'] == 'dis'">
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-red-500 mb-2">الخصم بالدقيقة</label>
                                <input type="number" name="dis" v-model.number="edit_diss['{{ $shf->id }}']" class="w-full bg-red-50 dark:bg-red-500/5 border border-red-200 dark:border-red-500/20 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl outline-none focus:border-red-500 text-xs transition-colors">
                            </div>

                            <div v-show="edit_dis_types['{{ $shf->id }}'] == 'dis_value'">
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-red-500 mb-2">خصم قيمة</label>
                                <input type="number" name="dis_value" v-model.number="edit_dis_values['{{ $shf->id }}']" class="w-full bg-red-50 dark:bg-red-500/5 border border-red-200 dark:border-red-500/20 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl outline-none focus:border-red-500 text-xs transition-colors">
                            </div>

                            <div>
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">السبب</label>
                                <textarea name="dec" rows="2" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">{{ $shf->dec }}</textarea>
                            </div>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3">
                            <button type="button" onclick="hidePixelModal('update-please-modal-{{ $shf->id }}')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                            <button type="submit" class="px-5 py-2.5 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-xs font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95">حفظ التعديلات</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

        <!-- Add Modal -->

        <div id="add-please-modal" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
            <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
            <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-lg rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-5 h-5 text-sky-500"></i> إضافة إذن جديد
                    </h3>
                    <button type="button" onclick="hidePixelModal('add-please-modal')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <form action="{{ route('ple.store') }}" method="post">
                    {{ csrf_field() }}
                    <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">اسم الموظف</label>
                            <select name="employees_id" class="form-control form-control-sm select2 w-full" required>
                                <option value="" disabled selected>ابحث واختر الموظف</option>
                                @foreach ($emps as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->num . ' - ' . $emp->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-1">
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">التاريخ</label>
                                <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                            </div>
                            <div class="flex-1">
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">مدة الإذن بالدقيقة</label>
                                <input type="number" name="minutes" value="0" min="0" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">استئذان (دخول / خروج)</label>
                            <div class="flex items-center gap-6 bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 px-4 py-3 rounded-xl">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="in_out" value="0" checked required class="custom-radio text-sky-500 focus:ring-sky-500">
                                    <span class="text-sm font-bold text-gray-700 dark:text-gray-300">دخول</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="in_out" value="1" required class="custom-radio text-sky-500 focus:ring-sky-500">
                                    <span class="text-sm font-bold text-gray-700 dark:text-gray-300">خروج</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">نوع الخصم</label>
                            <div class="flex items-center gap-6 bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 px-4 py-3 rounded-xl">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="dis_type_add" value="dis" v-model="add_dis_type" @change="switchAddType('dis')" class="custom-radio text-sky-500 focus:ring-sky-500">
                                    <span class="text-sm font-bold text-gray-700 dark:text-gray-300">خصم بالدقيقة</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="dis_type_add" value="dis_value" v-model="add_dis_type" @change="switchAddType('dis_value')" class="custom-radio text-sky-500 focus:ring-sky-500">
                                    <span class="text-sm font-bold text-gray-700 dark:text-gray-300">خصم قيمة</span>
                                </label>
                            </div>
                        </div>

                        <div v-show="add_dis_type == 'dis'">
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-red-500 mb-2">الخصم بالدقيقة</label>
                            <input type="number" name="dis" v-model.number="add_dis" class="w-full bg-red-50 dark:bg-red-500/5 border border-red-200 dark:border-red-500/20 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl outline-none focus:border-red-500 text-xs transition-colors">
                        </div>

                        <div v-show="add_dis_type == 'dis_value'">
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-red-500 mb-2">خصم قيمة</label>
                            <input type="number" name="dis_value" v-model.number="add_dis_value" class="w-full bg-red-50 dark:bg-red-500/5 border border-red-200 dark:border-red-500/20 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl outline-none focus:border-red-500 text-xs transition-colors">
                        </div>

                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">السبب</label>
                            <textarea name="dec" rows="2" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors" placeholder="اكتب سبب الإذن هنا..."></textarea>
                        </div>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3">
                        <button type="button" onclick="hidePixelModal('add-please-modal')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                        <button type="submit" class="px-5 py-2.5 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-xs font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95">إضافة الإذن</button>
                    </div>
                </form>
            </div>
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
                // Reinitialize select2 if in this modal
                $(modal).find('.select2').select2({
                    placeholder: 'ابحث بالكتابة عن اسم الموظف...',
                    allowClear: true,
                    dir: "rtl",
                    width: '100%',
                    dropdownParent: $(modal)
                });
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

            // Initialize general Select2
            $('.select2').select2({
                placeholder: 'ابحث بالكتابة عن اسم الموظف...',
                allowClear: true,
                dir: "rtl",
                width: '100%'
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
                    $("#bulk_delete_btn").show().css('display', 'flex');
                } else {
                    $("#bulk_delete_btn").hide();
                }
            }

            $("#bulk_delete_btn").click(function() {
                if (confirm('هل أنت متأكد من حذف السجلات المختارة؟')) {
                    $("#bulk_delete_form").submit();
                }
            });
        });

        // Vue Initialization
        const { createApp } = Vue;
        createApp({
            data() {
                return {
                    add_dis_type: 'dis',
                    add_dis: 0,
                    add_dis_value: 0,
                    edit_dis_types: {
                        @foreach ($plzs as $shf)
                        '{{ $shf->id }}': '{{ $shf->dis_value > 0 ? "dis_value" : "dis" }}',
                        @endforeach
                    },
                    edit_diss: {
                        @foreach ($plzs as $shf)
                        '{{ $shf->id }}': {{ $shf->dis ?: 0 }},
                        @endforeach
                    },
                    edit_dis_values: {
                        @foreach ($plzs as $shf)
                        '{{ $shf->id }}': {{ $shf->dis_value ?: 0 }},
                        @endforeach
                    }
                }
            },
            watch: {
                add_dis_type(newVal) {
                    if (newVal === 'dis') {
                        this.add_dis_value = 0;
                    } else {
                        this.add_dis = 0;
                    }
                }
            },
            methods: {
                switchType(id, type) {
                    if (type === 'dis') {
                        this.edit_dis_values[id] = 0;
                    } else {
                        this.edit_diss[id] = 0;
                    }
                },
                switchAddType(type) {
                    if (type === 'dis') {
                        this.add_dis_value = 0;
                    } else {
                        this.add_dis = 0;
                    }
                }
            }
        }).mount('#vue-app');
    </script>
@endsection
