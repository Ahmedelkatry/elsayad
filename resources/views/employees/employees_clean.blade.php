@extends('layouts_new.app')
@section('title')
    الموظفين | نظام إدارة الموظفين
@endsection

@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
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
        #modalMap { height: 400px; width: 100%; border-radius: 0.75rem; z-index: 10;}
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
        #location-map {
            height: 300px;
            border-radius: 1rem;
            z-index: 0;
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
                <span class="text-sky-600 dark:text-sky-500">الموظفين</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">إدارة الموظفين</h1>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            @can('اضافه موظف')
                <a href="{{ route('employees.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-sky-500 text-white rounded-xl text-xs font-bold hover:bg-sky-400 shadow-lg shadow-sky-500/20 transition-all active:scale-95">
                    <i data-lucide="user-plus" class="w-4 h-4 text-white"></i>
                    إضافة موظف
                </a>
                <button type="button" class="btn btn-danger hidden items-center gap-2 px-5 py-2.5 bg-red-500 text-white rounded-xl text-xs font-bold hover:bg-red-600 shadow-lg shadow-red-500/10 transition-all active:scale-95" id="bulk_delete_btn">
                    <i data-lucide="trash-2" class="w-4 h-4 text-white"></i>
                    حذف المحدد
                </button>
            @endcan
            <button type="button" onclick="openBranchesModal()" class="flex items-center gap-2 px-5 py-2.5 bg-blue-500 text-white rounded-xl text-xs font-bold hover:bg-blue-400 shadow-lg shadow-blue-500/10 transition-all active:scale-95">
                <i data-lucide="map-pin" class="w-4 h-4"></i>
                إدارة الفروع والمواقع
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

    <!-- Live Locations Responses -->
    <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-6 shadow-sm mb-6">
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i data-lucide="map-pin" class="w-5 h-5 text-blue-500"></i> آخر ردود تحديد الموقع (اللحظي)
            </h4>
            <button type="button" onclick="loadLatestResponses()" class="flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 rounded-lg text-xs font-bold hover:bg-gray-200 dark:hover:bg-white/10 transition-colors">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i> تحديث
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/5">
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">الموظف</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">وقت الرد</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">الموقع</th>
                    </tr>
                </thead>
                <tbody id="responsesTableBody">
                    <!-- سيتم التحميل بالجافا سكريبت -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Employees Table -->
    <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-6 shadow-sm">
        
        <!-- Legacy Search Removed: PixelTableEnhancer provides integrated search -->

        <form action="{{ route('employee.bulk_delete') }}" method="POST" id="bulk_delete_form">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse myTable whitespace-nowrap pixel-table-modern">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-[#111]">
                            <th class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400"># <input type="checkbox" id="select_all" class="rounded border-gray-300 text-sky-500 shadow-sm focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 ml-1"></th>
                            <th class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400">رقم البصمة</th>
                            <th class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400">اسم الموظف</th>
                            <th class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400">الايميل</th>
                            <th class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400">القسم</th>
                            <th class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400">الشيفت</th>
                            <th class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400">نظام الموظف</th>
                            <th class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400">ايام الاجازة</th>
                            <th class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400">الاجازة OT</th>
                            <th class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400">المرتب</th>
                            <th class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400">يوم العمل</th>
                            <th class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400">ساعة العمل</th>
                            <th class="py-3 px-2 text-xs font-bold text-gray-500 dark:text-gray-400 text-center">العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $i => $emp)
                            <tr class="tro border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                <td class="py-3 px-2 text-xs text-gray-900 dark:text-white">{{ $i + 1 }} <input type="checkbox" name="ids[]" value="{{ $emp->id }}" class="box1 rounded border-gray-300 text-sky-500 shadow-sm focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50 ml-1"></td>
                                <td class="py-3 px-2 text-xs font-bold text-blue-500">{{ $emp->num }}</td>
                                <td class="py-3 px-2 text-xs font-bold text-gray-900 dark:text-white">
                                    <a href="{{ route('employees.show', $emp->id) }}" class="hover:text-sky-500 transition-colors">{{ $emp->name }}</a>
                                </td>
                                <td class="py-3 px-2 text-xs text-green-600 dark:text-green-500">{{ $emp->email }}</td>
                                <td class="py-3 px-2 text-xs text-gray-900 dark:text-white">{{ $emp->section->name }}</td>
                                <td class="py-3 px-2 text-xs text-gray-900 dark:text-white">{{ $emp->shift->name }}</td>
                                <td class="py-3 px-2 text-xs text-gray-900 dark:text-white">{{ $emp->sysemp }}</td>
                                <td class="py-3 px-2 text-xs text-gray-900 dark:text-white">{{ is_array($emp->free_d) ? implode('-', $emp->free_d) : $emp->free_d }}</td>
                                <td class="py-3 px-2 text-xs text-gray-900 dark:text-white">{{ $emp->friday }}</td>
                                <td class="py-3 px-2 text-xs font-bold text-gray-900 dark:text-white">{{ $emp->kabd }}</td>
                                <td class="py-3 px-2 text-xs text-gray-900 dark:text-white">{{ number_format($emp->countday, 2) }}</td>
                                <td class="py-3 px-2 text-xs text-gray-900 dark:text-white">{{ number_format($emp->counth, 2) }}</td>
                                <td class="py-3 px-2 text-sm">
                                    <div class="flex items-center justify-center gap-2">
                                        @can('تعديل موظف')
                                            <a href="{{ route('employees.edit', $emp->id) }}" class="p-2 bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-500 hover:bg-green-200 dark:hover:bg-green-500/20 rounded-lg transition-colors">
                                                <i data-lucide="edit" class="w-4 h-4"></i>
                                            </a>
                                        @endcan
                                        
                                        @can('حذف موظف')
                                            <button type="button" onclick="showPixelModal('delete-employee-modal-{{ $emp->id }}')" class="p-2 bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-500 hover:bg-red-200 dark:hover:bg-red-500/20 rounded-lg transition-colors">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        @endcan

                                        @if ($emp->email)
                                            <button type="button" onclick="sendLocationRequest('{{ $emp->num }}', '{{ $emp->name }}')" class="p-2 bg-orange-100 dark:bg-orange-500/10 text-orange-600 dark:text-orange-500 hover:bg-orange-200 dark:hover:bg-orange-500/20 rounded-lg transition-colors" title="طلب تحديد الموقع">
                                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                                            </button>

                                            <button type="button" onclick="event.preventDefault(); document.getElementById('reset-form-{{ $emp->num }}').submit();" class="p-2 bg-blue-100 dark:bg-blue-500/10 text-blue-600 dark:text-blue-500 hover:bg-blue-200 dark:hover:bg-blue-500/20 rounded-lg transition-colors" title="إعادة مزامنة">
                                                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                                            </button>
                                            <form id="reset-form-{{ $emp->num }}" action="{{ route('emp.reset', $emp->num) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('post')
                                            </form>
                                            <button type="button"
                                                onclick="openLocationModal({{ $emp->id }}, '{{ addslashes($emp->name) }}')"
                                                class="p-2 bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-500 hover:bg-green-200 dark:hover:bg-green-500/20 rounded-lg transition-colors"
                                                title="مواقع البصمة المسموحة">
                                                <i data-lucide="map" class="w-4 h-4"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Delete Modal -->
                            <div id="delete-employee-modal-{{ $emp->id }}" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
                                <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                                <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-md rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
                                    <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                                        <h3 class="text-sm font-bold text-red-500 flex items-center gap-2">
                                            <i data-lucide="alert-triangle" class="w-5 h-5"></i> تأكيد الحذف
                                        </h3>
                                        <button type="button" onclick="hidePixelModal('delete-employee-modal-{{ $emp->id }}')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                                            <i data-lucide="x" class="w-5 h-5"></i>
                                        </button>
                                    </div>
                                    <form action="{{ route('employees.destroy', $emp->id) }}" method="post">
                                        {{ method_field('delete') }}
                                        {{ csrf_field() }}
                                        <div class="p-6 space-y-4">
                                            <p class="text-sm text-gray-900 dark:text-white font-bold">هل أنت متأكد من حذف هذا الموظف؟</p>
                                            <div class="bg-gray-50 dark:bg-[#111] p-4 rounded-xl border border-gray-100 dark:border-white/5">
                                                <p class="text-xs text-gray-500 mb-1">الموظف: <span class="text-gray-900 dark:text-white font-bold">{{ $emp->name }}</span></p>
                                                <p class="text-xs text-gray-500">رقم البصمة: <span class="text-blue-500 font-bold">{{ $emp->num }}</span></p>
                                            </div>
                                            <!-- Hidden Data Inputs -->
                                            <input type="hidden" name="idzkt" value="{{ $emp->num }}">
                                            <input type="hidden" name="idd" value="{{ $emp->id }}">
                                            <input type="hidden" name="email" value="{{ $emp->email }}">
                                        </div>
                                        <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3">
                                            <button type="button" onclick="hidePixelModal('delete-employee-modal-{{ $emp->id }}')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                                            <button type="submit" class="px-5 py-2.5 bg-red-500 text-white hover:bg-red-600 rounded-xl text-xs font-bold shadow-lg shadow-red-500/10 transition-all active:scale-95">تأكيد الحذف</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>

    <!-- Map Modal -->
    <div id="mapModal" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-4xl rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2" id="mapModalLabel">
                    <i data-lucide="map" class="w-5 h-5 text-blue-500"></i> موقع الموظف اللحظي
                </h3>
                <button type="button" onclick="hidePixelModal('mapModal')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="p-6">
                <div id="modalMap" class="w-full bg-gray-100 dark:bg-[#111] rounded-xl overflow-hidden border border-gray-200 dark:border-white/10"></div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3">
                <button type="button" onclick="hidePixelModal('mapModal')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إغلاق</button>
            </div>
        </div>
    </div>

    {{-- ==================== Modal إدارة مواقع الشركة (الفروع) ==================== --}}
    <div id="branches-modal" class="pixel-modal fixed inset-0 z-[200] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeBranchesModal()"></div>
        <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-3xl rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent shrink-0">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="building-2" class="w-5 h-5 text-blue-500"></i>
                    إدارة الفروع ومواقع البصمة
                </h3>
                <button type="button" onclick="closeBranchesModal()" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="overflow-y-auto flex-1 p-6 space-y-5">
                {{-- قائمة الفروع الحالية --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest">الفروع والمواقع المعرّفة</h4>
                        <span id="branches-count" class="text-[10px] bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 px-2 py-0.5 rounded-full font-bold">0 فروع</span>
                    </div>
                    <div id="branches-list" class="space-y-2">
                        <p class="text-xs text-gray-400 text-center py-4">لا توجد فروع مضافة بعد.</p>
                    </div>
                </div>

                {{-- الخريطة لإضافة موقع جديد --}}
                <div>
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">إضافة فرع/موقع جديد — اضغط على الخريطة لتحديد الإحداثيات</h4>
                    <div id="branches-map" style="height:320px; border-radius:1rem; z-index:0;"></div>
                </div>

                {{-- نموذج الإضافة --}}
                <div id="add-branch-form" class="hidden bg-gray-50 dark:bg-[#111] rounded-2xl border border-gray-200 dark:border-white/10 p-4 space-y-3">
                    <p class="text-xs font-bold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4 text-blue-500"></i>
                        موقع جديد مختار
                    </p>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-1">خط العرض</label>
                            <input id="branch-lat" type="text" readonly class="w-full bg-white dark:bg-[#1a1a1a] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-lg text-xs">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-1">خط الطول</label>
                            <input id="branch-lng" type="text" readonly class="w-full bg-white dark:bg-[#1a1a1a] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-lg text-xs">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-1">اسم الفرع / الموقع</label>
                        <input id="branch-name" type="text" placeholder="مثال: المقر الرئيسي، فرع الزمالك..." class="w-full bg-white dark:bg-[#1a1a1a] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-lg text-xs outline-none focus:border-blue-500/50">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-1">النطاق المسموح (بالمتر)</label>
                        <input id="branch-radius" type="number" value="100" min="10" max="5000" class="w-full bg-white dark:bg-[#1a1a1a] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-lg text-xs outline-none focus:border-blue-500/50">
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button type="button" onclick="cancelNewBranch()" class="flex-1 px-4 py-2 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-bold hover:bg-gray-300 transition-all">إلغاء</button>
                        <button type="button" onclick="saveNewBranch()" class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-xl text-xs font-bold hover:bg-blue-600 shadow-lg shadow-blue-500/20 transition-all active:scale-95">
                            <i data-lucide="save" class="w-3 h-3 inline"></i> حفظ الفرع
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== Modal تحديد مواقع الموظف ==================== --}}
    <div id="location-modal" class="pixel-modal fixed inset-0 z-[200] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeLocationModal()"></div>
        <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-lg rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent shrink-0">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-5 h-5 text-green-500"></i>
                    مواقع البصمة المسموحة — <span id="loc-emp-name" class="text-green-500 mr-1"></span>
                </h3>
                <button type="button" onclick="closeLocationModal()" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="overflow-y-auto flex-1 p-6">
                <p class="text-xs text-gray-500 mb-4">اختر الفروع التي يُسمح للموظف بالبصمة منها. يمكن اختيار أكثر من فرع.</p>

                <div id="emp-locations-list" class="space-y-2">
                    <p class="text-xs text-gray-400 text-center py-4">
                        <i data-lucide="loader" class="w-4 h-4 inline animate-spin"></i> جاري التحميل...
                    </p>
                </div>

                <div id="no-branches-warning" class="hidden mt-4 bg-sky-50 dark:bg-sky-500/10 border border-sky-200 dark:border-sky-500/20 rounded-xl p-4 text-xs text-sky-700 dark:text-sky-400">
                    <i data-lucide="alert-triangle" class="w-4 h-4 inline ml-1"></i>
                    لا توجد فروع معرّفة. اضغط على "إدارة الفروع والمواقع" أولاً لإضافة مواقع الشركة.
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3 shrink-0">
                <button type="button" onclick="closeLocationModal()" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                <button type="button" onclick="saveEmployeeLocations()" class="px-5 py-2.5 bg-green-500 text-white hover:bg-green-600 rounded-xl text-xs font-bold shadow-lg shadow-green-500/10 transition-all active:scale-95">
                    <i data-lucide="save" class="w-4 h-4 inline ml-1"></i> حفظ التغييرات
                </button>
            </div>
        </div>
    </div>

@endsection


@section('js')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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

    <script>
        // ============================================================
        // Modal Helpers
        // ============================================================
        function showPixelModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modal.querySelector('.modal-content').classList.remove('scale-95');
            }
        }
        function hidePixelModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('opacity-0', 'pointer-events-none');
                modal.querySelector('.modal-content').classList.add('scale-95');
            }
        }

        // ============================================================
        // Live Location Request (for map marker modal)
        // ============================================================
        var leafletMap = null;
        var mapMarker = null;

        function sendLocationRequest(numid, name) {
            if (confirm('هل أنت متأكد من رغبتك في طلب الموقع الحالي من ' + name + '؟')) {
                $.ajax({
                    url: '{{ route("location_request.send") }}',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}', numid: numid },
                    success: function(response) { alert(response.message); },
                    error: function() { alert('حدث خطأ أثناء إرسال الطلب'); }
                });
            }
        }

        function loadLatestResponses() {
            $.ajax({
                url: '{{ route("location_request.latest") }}',
                type: 'GET',
                success: function(responses) {
                    let html = '';
                    if (responses.length === 0) {
                        html = '<tr><td colspan="3" class="py-4 px-4 text-center text-sm text-gray-500">لا توجد ردود حالية</td></tr>';
                    } else {
                        responses.forEach(res => {
                            html += `<tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5">
                                <td class="py-3 px-4 text-sm font-bold text-gray-900 dark:text-white">${res.employee_name}</td>
                                <td class="py-3 px-4 text-sm text-gray-500 dark:text-gray-400">${res.time}</td>
                                <td class="py-3 px-4 text-sm flex gap-2">
                                    <button class="px-3 py-1.5 bg-blue-100 text-blue-600 hover:bg-blue-200 dark:bg-blue-500/10 dark:text-blue-500 rounded-lg text-xs font-bold transition-colors flex items-center gap-1" onclick="showOnMap(${res.lat}, ${res.lon}, '${res.employee_name} - ${res.time}')">
                                        <i data-lucide="map-pin" class="w-3 h-3"></i> عرض
                                    </button>
                                    <button class="px-3 py-1.5 bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-500/10 dark:text-red-500 rounded-lg text-xs font-bold transition-colors flex items-center gap-1" onclick="deleteLocationResponse(${res.id})">
                                        <i data-lucide="trash" class="w-3 h-3"></i> مسح
                                    </button>
                                </td>
                            </tr>`;
                        });
                    }
                    $('#responsesTableBody').html(html);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
        }

        function showOnMap(lat, lon, title) {
            if (!lat || !lon || isNaN(lat) || isNaN(lon)) { alert('لا توجد إحداثيات صحيحة'); return; }
            showPixelModal('mapModal');
            $('#mapModalLabel').html(`<i data-lucide="map" class="w-5 h-5 text-blue-500"></i> موقع الموظف: ${title}`);
            if (typeof lucide !== 'undefined') lucide.createIcons();
            setTimeout(function() {
                if (leafletMap === null) {
                    leafletMap = L.map('modalMap').setView([lat, lon], 15);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(leafletMap);
                } else {
                    leafletMap.setView([lat, lon], 15);
                }
                if (mapMarker) leafletMap.removeLayer(mapMarker);
                mapMarker = L.marker([lat, lon]).addTo(leafletMap).bindPopup(title).openPopup();
                leafletMap.invalidateSize();
            }, 500);
        }

        function deleteLocationResponse(id) {
            if (!confirm('هل تريد حذف هذا السجل؟')) return;
            $.ajax({
                url: `{{ url('/location-request') }}/${id}`,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function() { loadLatestResponses(); }
            });
        }

        // ============================================================
        // Branches (Company Locations) Management
        // ============================================================
        var branchesMap = null;
        var branchMarker = null;
        var branchCircle = null;
        var pendingBranchLat = null;
        var pendingBranchLng = null;

        function openBranchesModal() {
            const modal = document.getElementById('branches-modal');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.querySelector('.modal-content').classList.remove('scale-95');

            setTimeout(() => {
                if (!branchesMap) {
                    branchesMap = L.map('branches-map').setView([30.0444, 31.2357], 12);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(branchesMap);

                    branchesMap.on('click', function(e) {
                        pendingBranchLat = e.latlng.lat.toFixed(7);
                        pendingBranchLng = e.latlng.lng.toFixed(7);
                        document.getElementById('branch-lat').value = pendingBranchLat;
                        document.getElementById('branch-lng').value = pendingBranchLng;
                        document.getElementById('add-branch-form').classList.remove('hidden');

                        if (branchMarker) branchesMap.removeLayer(branchMarker);
                        if (branchCircle) branchesMap.removeLayer(branchCircle);

                        branchMarker = L.marker(e.latlng).addTo(branchesMap).bindPopup('موقع جديد').openPopup();
                        const r = parseInt(document.getElementById('branch-radius').value) || 100;
                        branchCircle = L.circle(e.latlng, { radius: r, color: '#3b82f6', fillOpacity: 0.15 }).addTo(branchesMap);
                    });
                } else {
                    branchesMap.invalidateSize();
                }
                loadBranches();
            }, 200);
        }

        function closeBranchesModal() {
            const modal = document.getElementById('branches-modal');
            modal.classList.add('opacity-0', 'pointer-events-none');
            modal.querySelector('.modal-content').classList.add('scale-95');
            cancelNewBranch();
        }

        function cancelNewBranch() {
            document.getElementById('add-branch-form').classList.add('hidden');
            document.getElementById('branch-name').value = '';
            document.getElementById('branch-radius').value = 100;
            if (branchesMap) {
                if (branchMarker) { branchesMap.removeLayer(branchMarker); branchMarker = null; }
                if (branchCircle) { branchesMap.removeLayer(branchCircle); branchCircle = null; }
            }
            pendingBranchLat = null; pendingBranchLng = null;
        }

        $(document).on('input', '#branch-radius', function() {
            if (branchCircle) branchCircle.setRadius(parseInt(this.value) || 100);
        });

        function loadBranches() {
            $.ajax({
                url: '{{ route("company.locations.index") }}',
                type: 'GET',
                success: function(data) { renderBranches(data); }
            });
        }

        function renderBranches(branches) {
            const list = document.getElementById('branches-list');
            const countEl = document.getElementById('branches-count');
            countEl.textContent = branches.length + ' فروع';

            // Clear old markers except the pending one
            if (branchesMap) {
                branchesMap.eachLayer(layer => {
                    if ((layer instanceof L.Marker || layer instanceof L.Circle) && layer !== branchMarker && layer !== branchCircle) {
                        branchesMap.removeLayer(layer);
                    }
                });
            }

            if (branches.length === 0) {
                list.innerHTML = '<p class="text-xs text-gray-400 text-center py-4">لا توجد فروع مضافة بعد.</p>';
                return;
            }

            list.innerHTML = '';
            branches.forEach(b => {
                if (branchesMap) {
                    L.marker([b.latitude, b.longitude]).addTo(branchesMap)
                        .bindPopup(`<b>${b.name}</b><br>النطاق: ${b.radius}م`);
                    L.circle([b.latitude, b.longitude], { radius: b.radius, color: '#3b82f6', fillOpacity: 0.1 }).addTo(branchesMap);
                }

                const row = document.createElement('div');
                row.className = 'flex items-center justify-between bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/10 rounded-xl px-4 py-3';
                row.innerHTML = `
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-500/10 rounded-lg flex items-center justify-center">
                            <i data-lucide="building-2" class="w-4 h-4 text-blue-500"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-900 dark:text-white">${b.name}</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">${b.latitude}, ${b.longitude} • نطاق: ${b.radius}م</p>
                        </div>
                    </div>
                    <button type="button" onclick="deleteBranch(${b.id})" class="p-1.5 bg-red-100 dark:bg-red-500/10 text-red-500 hover:bg-red-200 rounded-lg transition-colors" title="حذف الفرع">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    </button>
                `;
                list.appendChild(row);
            });
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        function saveNewBranch() {
            if (!pendingBranchLat || !pendingBranchLng) { alert('اضغط أولاً على الخريطة لتحديد موقع الفرع'); return; }
            const name = document.getElementById('branch-name').value.trim();
            if (!name) { document.getElementById('branch-name').focus(); alert('من فضلك اكتب اسم الفرع'); return; }
            const radius = parseInt(document.getElementById('branch-radius').value) || 100;

            $.ajax({
                url: '{{ route("company.locations.store") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    name: name,
                    latitude: pendingBranchLat,
                    longitude: pendingBranchLng,
                    radius: radius
                },
                success: function(response) {
                    if (response.success) {
                        cancelNewBranch();
                        loadBranches();
                    }
                },
                error: function() { alert('حدث خطأ أثناء الحفظ'); }
            });
        }

        function deleteBranch(id) {
            if (!confirm('هل تريد حذف هذا الفرع/الموقع؟ سيتم إلغاء ربط الموظفين به أيضاً.')) return;
            $.ajax({
                url: `{{ url('/company-locations') }}/${id}`,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) loadBranches();
                }
            });
        }

        // ============================================================
        // Employee Location Assignment (select from company locations)
        // ============================================================
        let currentEmployeeId = null;

        function openLocationModal(empId, empName) {
            currentEmployeeId = empId;
            document.getElementById('loc-emp-name').textContent = empName;

            const modal = document.getElementById('location-modal');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.querySelector('.modal-content').classList.remove('scale-95');

            loadEmployeeLocationsList();
        }

        function closeLocationModal() {
            const modal = document.getElementById('location-modal');
            modal.classList.add('opacity-0', 'pointer-events-none');
            modal.querySelector('.modal-content').classList.add('scale-95');
        }

        function loadEmployeeLocationsList() {
            const list = document.getElementById('emp-locations-list');
            const warning = document.getElementById('no-branches-warning');
            list.innerHTML = '<p class="text-xs text-gray-400 text-center py-4">جاري التحميل...</p>';
            warning.classList.add('hidden');

            // Fetch all company locations AND employee's assigned ones in parallel
            $.when(
                $.ajax({ url: '{{ route("company.locations.index") }}', type: 'GET' }),
                $.ajax({ url: `{{ url('/company-locations/employee') }}/${currentEmployeeId}`, type: 'GET' })
            ).done(function(branchesRes, assignedRes) {
                const branches = branchesRes[0];
                const assignedIds = assignedRes[0]; // array of IDs

                if (branches.length === 0) {
                    list.innerHTML = '';
                    warning.classList.remove('hidden');
                    return;
                }

                list.innerHTML = '';
                branches.forEach(b => {
                    const isChecked = assignedIds.includes(b.id);
                    const row = document.createElement('label');
                    row.className = `flex items-center gap-3 bg-gray-50 dark:bg-white/5 border ${isChecked ? 'border-green-400 dark:border-green-500/50 bg-green-50 dark:bg-green-500/5' : 'border-gray-100 dark:border-white/10'} rounded-xl px-4 py-3 cursor-pointer hover:border-green-400/50 transition-all`;
                    row.innerHTML = `
                        <input type="checkbox" name="emp_location_ids[]" value="${b.id}" ${isChecked ? 'checked' : ''} class="w-4 h-4 rounded border-gray-300 text-green-500 focus:ring-green-400">
                        <div class="flex items-center gap-2 flex-1">
                            <div class="w-7 h-7 bg-blue-100 dark:bg-blue-500/10 rounded-lg flex items-center justify-center shrink-0">
                                <i data-lucide="building-2" class="w-3.5 h-3.5 text-blue-500"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-900 dark:text-white">${b.name}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">نطاق: ${b.radius}م</p>
                            </div>
                        </div>
                        ${isChecked ? '<span class="text-[10px] text-green-600 dark:text-green-400 font-bold bg-green-100 dark:bg-green-500/10 px-2 py-0.5 rounded-full">مسموح</span>' : ''}
                    `;
                    list.appendChild(row);
                });
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        }

        function saveEmployeeLocations() {
            const checkedIds = [];
            document.querySelectorAll('#emp-locations-list input[type="checkbox"]:checked').forEach(cb => {
                checkedIds.push(parseInt(cb.value));
            });

            $.ajax({
                url: `{{ url('/company-locations/employee') }}/${currentEmployeeId}/sync`,
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ _token: '{{ csrf_token() }}', location_ids: checkedIds }),
                success: function(response) {
                    if (response.success) {
                        closeLocationModal();
                        // Show a quick success indicator
                        const btn = document.querySelector(`button[onclick="openLocationModal(${currentEmployeeId}, '${document.getElementById('loc-emp-name').textContent}')"]`);
                        if (btn) {
                            btn.style.background = '#22c55e20';
                            setTimeout(() => { btn.style.background = ''; }, 1500);
                        }
                    }
                },
                error: function() { alert('حدث خطأ أثناء الحفظ'); }
            });
        }

        // ============================================================
        // Init
        // ============================================================
        $(document).ready(function() {
            loadLatestResponses();
            setInterval(loadLatestResponses, 60000);

            // Bulk Delete Logic
            $("#select_all").click(function(e) {
                e.stopPropagation();
                $(".box1").prop('checked', $(this).prop('checked'));
                toggleDeleteBtn();
            });
            $(document).on('change', '.box1', function() { toggleDeleteBtn(); });

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
