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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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
        #location-map {
            height: 300px;
            border-radius: 1rem;
            z-index: 0;
        }
        .location-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: rgba(34,197,94,0.1);
            color: #16a34a;
            border: 1px solid rgba(34,197,94,0.2);
            border-radius: 999px;
            padding: 0.2rem 0.6rem;
            font-size: 0.7rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }
        .dark .location-badge {
            background: rgba(34,197,94,0.08);
            color: #4ade80;
            border-color: rgba(34,197,94,0.15);
        }
        .location-badge:hover { opacity: 0.85; }
        #locations-list { max-height: 200px; overflow-y: auto; }
    </style>
@endsection

@section('page-header')
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-yellow-600 dark:hover:text-yellow-500 transition-colors">الرئيسية</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-yellow-600 dark:text-yellow-500">شؤون الموظفين</span>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-yellow-600 dark:text-yellow-500">إدارة الموظفين</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-yellow-500 rounded-full shadow-[0_0_15px_rgba(250,204,21,0.3)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">إدارة الموظفين</h1>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            @can('اضافه موظف')
                <a href="{{ route('employees.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-yellow-500 text-black rounded-xl text-xs font-bold hover:bg-yellow-400 shadow-lg shadow-yellow-500/10 transition-all active:scale-95">
                    <i data-lucide="user-plus" class="w-4 h-4 text-black"></i>
                    إضافة موظف
                </a>
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
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">مواقع البصمة</th>
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
                            <td class="py-3 px-4 text-xs font-bold text-yellow-600">{{ $emp->kabd }}</td>
                            <td class="py-3 px-4 text-xs">
                                <button type="button"
                                    onclick="openLocationModal({{ $emp->id }}, '{{ addslashes($emp->name) }}')"
                                    class="location-badge">
                                    <i data-lucide="map-pin" class="w-3 h-3"></i>
                                    مواقع البصمة
                                </button>
                            </td>
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
                                                <input type="number" name="num" value="{{ $emp->num }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">اسم الموظف</label>
                                                <input type="text" name="name" value="{{ $emp->name }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">العمر</label>
                                                <input type="number" name="age" value="{{ $emp->age }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الموبايل</label>
                                                <input type="number" name="mobile" value="{{ $emp->mobile }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">القسم</label>
                                                <select name="section_id" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
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
                                                <select name="shift_id" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
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
                                                        <input type="checkbox" name="free_d[]" value="{{ $day }}" {{ is_array($emp->free_d) && in_array($day, $emp->free_d) ? 'checked' : '' }} class="rounded border-gray-300 text-yellow-500 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                                        <span class="text-xs text-gray-600 dark:text-gray-400 group-hover:text-yellow-600 transition-colors">{{ $day }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">نظام اليوم</label>
                                                <input type="number" name="sysemp" value="{{ $emp->sysemp }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">إجازة OT</label>
                                                <input type="number" name="friday" value="{{ $emp->friday ?? 0 }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">المرتب</label>
                                            <input type="number" name="kabd" value="{{ $emp->kabd }}" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">العنوان</label>
                                            <textarea name="title" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors" rows="2">{{ $emp->title }}</textarea>
                                        </div>
                                    </div>
                                    <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3 sticky bottom-0">
                                        <button type="button" onclick="hidePixelModal('update-emp-modal-{{ $emp->id }}')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                                        <button type="submit" class="px-5 py-2.5 bg-yellow-500 text-black hover:bg-yellow-400 rounded-xl text-xs font-bold shadow-lg shadow-yellow-500/10 transition-all active:scale-95">حفظ التعديلات</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ==================== Modal إدارة مواقع البصمة ==================== --}}
    <div id="location-modal" class="pixel-modal fixed inset-0 z-[200] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeLocationModal()"></div>
        <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-2xl rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent shrink-0">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-5 h-5 text-green-500"></i>
                    مواقع البصمة الجوال - <span id="loc-emp-name" class="text-green-500"></span>
                </h3>
                <button type="button" onclick="closeLocationModal()" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="overflow-y-auto flex-1 p-6 space-y-5">
                {{-- المواقع المسجلة --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest">المواقع المسجلة</h4>
                        <span id="loc-count" class="text-[10px] bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400 px-2 py-0.5 rounded-full font-bold">0 مواقع</span>
                    </div>
                    <div id="locations-list" class="space-y-2">
                        <p id="no-locations-msg" class="text-xs text-gray-400 text-center py-4">لا توجد مواقع مسجلة. أضف موقعاً من الخريطة.</p>
                    </div>
                </div>

                {{-- الخريطة --}}
                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">اضغط على الخريطة لتحديد موقع جديد</h4>
                    <div id="location-map"></div>
                </div>

                {{-- نموذج الإضافة --}}
                <div id="add-location-form" class="hidden bg-gray-50 dark:bg-[#111] rounded-2xl border border-gray-200 dark:border-white/10 p-4 space-y-3">
                    <p class="text-xs font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4 text-green-500"></i>
                        موقع جديد مختار
                    </p>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-1">خط العرض</label>
                            <input id="new-lat" type="text" readonly class="w-full bg-white dark:bg-[#1a1a1a] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-lg text-xs">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-1">خط الطول</label>
                            <input id="new-lng" type="text" readonly class="w-full bg-white dark:bg-[#1a1a1a] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-lg text-xs">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-1">اسم المكان (مثال: المقر الرئيسي)</label>
                        <input id="new-loc-name" type="text" placeholder="اسم المكان..." class="w-full bg-white dark:bg-[#1a1a1a] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-lg text-xs outline-none focus:border-green-500/50">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-1">النطاق المسموح (بالمتر)</label>
                        <input id="new-radius" type="number" value="100" min="10" max="5000" class="w-full bg-white dark:bg-[#1a1a1a] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-lg text-xs outline-none focus:border-green-500/50">
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button onclick="cancelNewLocation()" class="flex-1 px-4 py-2 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-bold hover:bg-gray-300 transition-all">إلغاء</button>
                        <button onclick="saveNewLocation()" class="flex-1 px-4 py-2 bg-green-500 text-white rounded-xl text-xs font-bold hover:bg-green-600 shadow-lg shadow-green-500/20 transition-all active:scale-95">
                            <i data-lucide="save" class="w-3 h-3 inline"></i> حفظ الموقع
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal (Regular) -->
    <div id="add-emp-modal" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-2xl rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="user-plus" class="w-5 h-5 text-yellow-500"></i> إضافة موظف جديد
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
                            <input type="number" name="num" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">اسم الموظف</label>
                            <input type="text" name="name" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">العمر</label>
                            <input type="number" name="age" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الموبايل</label>
                            <input type="number" name="mobile" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">القسم</label>
                            <select name="section_id" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                                @foreach ($sections as $sec)
                                    <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الشيفت</label>
                            <select name="shift_id" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
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
                                    <input type="checkbox" name="free_d[]" value="{{ $day }}" {{ $day == 'الجمعة' ? 'checked' : '' }} class="rounded border-gray-300 text-yellow-500 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                    <span class="text-xs text-gray-600 dark:text-gray-400 group-hover:text-yellow-600 transition-colors">{{ $day }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">نظام اليوم</label>
                            <input type="number" name="sysemp" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">إجازة OT</label>
                            <input type="number" name="friday" value="0" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">المرتب</label>
                        <input type="number" name="kabd" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">العنوان</label>
                        <textarea name="title" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors" rows="2"></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3 sticky bottom-0">
                    <button type="button" onclick="hidePixelModal('add-emp-modal')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                    <button type="submit" class="px-5 py-2.5 bg-yellow-500 text-black hover:bg-yellow-400 rounded-xl text-xs font-bold shadow-lg shadow-yellow-500/10 transition-all active:scale-95">إضافة الموظف</button>
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
                        <input type="text" name="ipz" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">رقم البصمة</label>
                            <input type="number" name="num" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">اسم الموظف</label>
                            <input type="text" name="name" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">كلمة المرور (اختياري)</label>
                        <input type="password" name="password" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">العمر</label>
                            <input type="number" name="age" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الموبايل</label>
                            <input type="number" name="mobile" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">القسم</label>
                            <select name="section_id" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                                @foreach ($sections as $sec)
                                    <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الشيفت</label>
                            <select name="shift_id" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
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
                                    <input type="checkbox" name="free_d[]" value="{{ $day }}" {{ $day == 'الجمعة' ? 'checked' : '' }} class="rounded border-gray-300 text-yellow-500 shadow-sm focus:border-yellow-300 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                    <span class="text-xs text-gray-600 dark:text-gray-400 group-hover:text-yellow-600 transition-colors">{{ $day }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">نظام اليوم</label>
                            <input type="number" name="sysemp" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">إجازة OT</label>
                            <input type="number" name="friday" value="0" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">المرتب</label>
                        <input type="number" name="kabd" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors">
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">العنوان</label>
                        <textarea name="title" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-yellow-500/50 text-xs transition-colors" rows="2"></textarea>
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
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // ========================
        // Modal Handlers
        // ========================
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

        // ========================
        // Location Modal
        // ========================
        let locationMap = null;
        let locationMarker = null;
        let radiusCircle = null;
        let currentEmployeeId = null;
        let pendingLat = null;
        let pendingLng = null;

        function openLocationModal(empId, empName) {
            currentEmployeeId = empId;
            document.getElementById('loc-emp-name').textContent = empName;
            cancelNewLocation();

            const modal = document.getElementById('location-modal');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.querySelector('.modal-content').classList.remove('scale-95');

            setTimeout(() => {
                if (!locationMap) {
                    locationMap = L.map('location-map').setView([30.0444, 31.2357], 12);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(locationMap);

                    locationMap.on('click', function(e) {
                        pendingLat = e.latlng.lat.toFixed(7);
                        pendingLng = e.latlng.lng.toFixed(7);
                        document.getElementById('new-lat').value = pendingLat;
                        document.getElementById('new-lng').value = pendingLng;
                        document.getElementById('add-location-form').classList.remove('hidden');

                        if (locationMarker) locationMap.removeLayer(locationMarker);
                        if (radiusCircle) locationMap.removeLayer(radiusCircle);

                        locationMarker = L.marker(e.latlng).addTo(locationMap)
                            .bindPopup('موقع جديد - حدد الاسم والنطاق').openPopup();

                        const radius = parseInt(document.getElementById('new-radius').value) || 100;
                        radiusCircle = L.circle(e.latlng, { radius, color: '#22c55e', fillOpacity: 0.15 }).addTo(locationMap);
                    });
                } else {
                    locationMap.invalidateSize();
                }
                loadLocations();
            }, 200);
        }

        function closeLocationModal() {
            const modal = document.getElementById('location-modal');
            modal.classList.add('opacity-0', 'pointer-events-none');
            modal.querySelector('.modal-content').classList.add('scale-95');
            cancelNewLocation();
        }

        function cancelNewLocation() {
            document.getElementById('add-location-form').classList.add('hidden');
            if (locationMap) {
                if (locationMarker) { locationMap.removeLayer(locationMarker); locationMarker = null; }
                if (radiusCircle) { locationMap.removeLayer(radiusCircle); radiusCircle = null; }
            }
            pendingLat = null;
            pendingLng = null;
        }

        document.getElementById('new-radius').addEventListener('input', function() {
            if (radiusCircle && pendingLat && pendingLng) {
                radiusCircle.setRadius(parseInt(this.value) || 100);
            }
        });

        function loadLocations() {
            fetch(`{{ url('/employee-locations') }}/${currentEmployeeId}`)
                .then(r => r.json())
                .then(data => renderLocations(data));
        }

        function renderLocations(locations) {
            const list = document.getElementById('locations-list');
            const noMsg = document.getElementById('no-locations-msg');
            const countEl = document.getElementById('loc-count');

            // clear existing markers
            if (locationMap) {
                locationMap.eachLayer(layer => {
                    if (layer instanceof L.Marker || layer instanceof L.Circle) {
                        if (layer !== locationMarker && layer !== radiusCircle) {
                            locationMap.removeLayer(layer);
                        }
                    }
                });
            }

            list.innerHTML = '';
            countEl.textContent = locations.length + ' مواقع';

            if (locations.length === 0) {
                list.appendChild(document.getElementById('no-locations-msg'));
                noMsg.style.display = 'block';
                return;
            }

            noMsg.style.display = 'none';
            locations.forEach(loc => {
                // Add to map
                if (locationMap) {
                    const m = L.marker([loc.latitude, loc.longitude]).addTo(locationMap)
                        .bindPopup(`<b>${loc.name}</b><br>النطاق: ${loc.radius}م`);
                    L.circle([loc.latitude, loc.longitude], {
                        radius: loc.radius, color: '#3b82f6', fillOpacity: 0.1
                    }).addTo(locationMap);
                    locationMap.panTo([loc.latitude, loc.longitude]);
                }

                const row = document.createElement('div');
                row.className = 'flex items-center justify-between bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 rounded-xl px-4 py-3';
                row.innerHTML = `
                    <div>
                        <p class="text-xs font-bold text-gray-900 dark:text-white">${loc.name}</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">${loc.latitude}, ${loc.longitude} • نطاق: ${loc.radius}م</p>
                    </div>
                    <button onclick="deleteLocation(${loc.id}, this)" class="p-1.5 bg-red-100 dark:bg-red-500/10 text-red-500 hover:bg-red-200 rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                    </button>
                `;
                list.appendChild(row);
            });
        }

        function saveNewLocation() {
            if (!pendingLat || !pendingLng) { alert('اضغط أولاً على الخريطة لتحديد موقع'); return; }
            const name = document.getElementById('new-loc-name').value.trim();
            if (!name) { document.getElementById('new-loc-name').focus(); alert('من فضلك اكتب اسم المكان'); return; }
            const radius = parseInt(document.getElementById('new-radius').value) || 100;

            fetch('{{ route("employee.locations.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    employee_id: currentEmployeeId,
                    name, latitude: pendingLat, longitude: pendingLng, radius
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) { cancelNewLocation(); loadLocations(); }
                else alert('حدث خطأ أثناء الحفظ');
            });
        }

        function deleteLocation(id, btn) {
            if (!confirm('هل تريد حذف هذا الموقع؟')) return;
            fetch(`{{ url('/employee-locations') }}/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(r => r.json())
            .then(data => { if (data.success) loadLocations(); });
        }
    </script>
@endsection
