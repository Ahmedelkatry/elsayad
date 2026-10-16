@extends('layouts_new.app')
@section('title')
    طلبات الأذونات | نظام إدارة الموظفين
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
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4 text-right">
        <div class="flex-1">
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2 justify-end">
                <span class="text-sky-600 dark:text-sky-500">الأذونات</span>
                <i data-lucide="chevron-right" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">الطلبات</span>
                <i data-lucide="chevron-right" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <a href="{{ route('dashboard') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
            </nav>
            <div class="flex items-center gap-3 justify-end">
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">طلبات الأذونات</h1>
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
            </div>
        </div>
        
        <div class="flex flex-wrap items-center gap-3 justify-end">
            <button onclick="showPixelModal('add-tlb-modal')" class="flex items-center gap-2 px-5 py-2.5 bg-sky-500 text-white rounded-xl text-xs font-bold hover:bg-sky-400 shadow-lg shadow-sky-500/20 transition-all active:scale-95">
                <i data-lucide="plus" class="w-4 h-4 text-white"></i>
                إضافة إذن جديد
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 text-right" role="alert">
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
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse pixel-table-modern">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-[#111]">
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">#</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">التاريخ</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">النوع</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">المدة</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">خصم دقائق</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">خصم مالي</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">الحالة</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">السبب</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 0; @endphp
                    @foreach ($tlbs as $shf)
                        @if ($shf->dateplz && $shf->textplz)
                            <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                <td class="py-3 px-4 text-xs text-gray-900 dark:text-white font-bold">{{ ++$i }}</td>
                                <td class="py-3 px-4 text-xs text-gray-900 dark:text-white font-mono">{{ $shf->dateplz }}</td>
                                <td class="py-3 px-4 text-xs font-bold">
                                    @if($shf->in_out == 0)
                                        <span class="text-blue-500 flex items-center gap-1 justify-end">دخول <i data-lucide="log-in" class="w-3 h-3"></i></span>
                                    @else
                                        <span class="text-orange-500 flex items-center gap-1 justify-end">خروج <i data-lucide="log-out" class="w-3 h-3"></i></span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-xs text-gray-900 dark:text-white">{{ $shf->min }} دقيقة</td>
                                <td class="py-3 px-4 text-xs text-gray-900 dark:text-white">{{ $shf->dis }} دقيقة</td>
                                <td class="py-3 px-4 text-xs font-bold text-red-500">{{ $shf->dis_value }} ج.م</td>
                                <td class="py-3 px-4">
                                    @if ($shf->status == 0)
                                        <span class="px-2.5 py-1 bg-green-500/10 text-green-600 dark:text-green-500 rounded-lg text-[10px] font-bold">مقبول</span>
                                    @elseif ($shf->status == 1)
                                        <span class="px-2.5 py-1 bg-red-500/10 text-red-600 dark:text-red-500 rounded-lg text-[10px] font-bold">مرفوض</span>
                                    @elseif ($shf->status == 3)
                                        <span class="px-2.5 py-1 bg-sky-500/10 text-sky-600 dark:text-sky-500 rounded-lg text-[10px] font-bold">جاري المراجعة</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-xs text-gray-600 dark:text-gray-400">
                                    <p class="max-w-[150px] truncate" title="{{ $shf->textplz }}">{{ $shf->textplz }}</p>
                                </td>
                                <td class="py-3 px-4 text-sm">
                                    @if($shf->status == 3)
                                        <button onclick="showPixelModal('delete-modal-{{ $shf->id }}')" class="p-2 bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-500 hover:bg-red-200 dark:hover:bg-red-500/20 rounded-lg transition-colors">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>

                            <!-- Delete Modal -->
                            <div id="delete-modal-{{ $shf->id }}" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
                                <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                                <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-md rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col text-right">
                                    <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                                        <h3 class="text-sm font-bold text-red-500 flex items-center gap-2">
                                            <i data-lucide="alert-triangle" class="w-5 h-5"></i> تأكيد الحذف
                                        </h3>
                                        <button type="button" onclick="hidePixelModal('delete-modal-{{ $shf->id }}')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                                            <i data-lucide="x" class="w-5 h-5"></i>
                                        </button>
                                    </div>
                                    <form action="{{ route('plzplzdel') }}" method="post">
                                        {{ method_field('delete') }}
                                        {{ csrf_field() }}
                                        <div class="p-6 space-y-4">
                                            <p class="text-sm text-gray-900 dark:text-white font-bold">هل أنت متأكد من حذف هذا الإذن؟</p>
                                            <input type="hidden" name="id" value="{{ $shf->id }}">
                                        </div>
                                        <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3">
                                            <button type="button" onclick="hidePixelModal('delete-modal-{{ $shf->id }}')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                                            <button type="submit" class="px-5 py-2.5 bg-red-500 text-white hover:bg-red-600 rounded-xl text-xs font-bold shadow-lg shadow-red-500/10 transition-all active:scale-95">تأكيد الحذف</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="add-tlb-modal" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-lg rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col text-right">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="plus-circle" class="w-5 h-5 text-sky-500"></i> إضافة طلب إذن جديد
                </h3>
                <button type="button" onclick="hidePixelModal('add-tlb-modal')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form action="{{ route('plzplzstore') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="num" value="{{ Auth::user()->num }}">
                <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                
                <div class="p-6 space-y-6 text-right">
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">التاريخ</label>
                        <input type="date" name="dateplz" required value="{{ date('Y-m-d') }}" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors font-mono">
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-3">نوع الإذن</label>
                        <div class="flex gap-6 justify-end">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300 group-hover:text-sky-500 transition-colors">دخول</span>
                                <input type="radio" name="in_out" value="0" required class="w-4 h-4 accent-sky-500">
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300 group-hover:text-sky-500 transition-colors">خروج</span>
                                <input type="radio" name="in_out" value="1" required class="w-4 h-4 accent-sky-500">
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">مدة الاستئذان (بالدقائق)</label>
                        <input type="number" name="min" required value="0" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors font-mono">
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">سبب الإذن</label>
                        <textarea name="textplz" required rows="3" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors resize-none"></textarea>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3">
                    <button type="button" onclick="hidePixelModal('add-tlb-modal')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                    <button type="submit" class="px-5 py-2.5 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-xs font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95">تأكيد الطلب</button>
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
    
    <script>
        $(document).ready(function() {
            // Table is automatically initialized by js.js
        });
    </script>
@endsection
