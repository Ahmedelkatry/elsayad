@extends('layouts_new.app')

@section('title')
    طلبات الاستئذان | نظام إدارة الموظفين
@endsection

@section('css')
    <!-- Select2 -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('page-header')
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">الإشعارات</span>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">طلبات الاستئذان</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">متابعة طلبات الاستئذان</h1>
            </div>
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
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse whitespace-nowrap pixel-table-modern">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-[#111]">
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">#</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">الموظف</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">التاريخ</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">النوع</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">المدة</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400">السبب</th>
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400 text-center">الحالة</th>
                        @php
                            $firstItem = isset($tlbs) && count($tlbs) > 0 ? $tlbs->first() : (isset($emp) ? $emp : null);
                        @endphp
                        @if ($firstItem && $firstItem->status == 3)
                        <th class="py-3 px-4 text-xs font-bold text-gray-500 dark:text-gray-400 text-center">العمليات</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; ?>
                    @php
                        $items = isset($tlbs) ? $tlbs : (isset($emp) ? collect([$emp]) : collect([]));
                    @endphp
                    @foreach ($items as $emp)
                        <tr class="border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <td class="py-3 px-4 text-xs text-gray-900 dark:text-white">{{ ++$i }}</td>
                            <td class="py-3 px-4 text-xs font-bold text-gray-900 dark:text-white">{{ $emp->name }}</td>
                            <td class="py-3 px-4 text-xs font-mono text-gray-900 dark:text-white">{{ $emp->dateplz }}</td>
                            <td class="py-3 px-4 text-xs">
                                @if ($emp->in_out === 0)
                                    <span class="px-2 py-1 bg-blue-500/10 text-blue-600 rounded-lg text-[10px] font-bold">دخول</span>
                                @else
                                    <span class="px-2 py-1 bg-orange-500/10 text-orange-600 rounded-lg text-[10px] font-bold">خروج</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-xs text-gray-900 dark:text-white">{{ $emp->min }} دقيقة</td>
                            <td class="py-3 px-4 text-xs text-gray-600 dark:text-gray-400">
                                <span class="block max-w-xs overflow-hidden text-ellipsis">{{ $emp->textplz }}</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if ($emp->status == 0)
                                    <span class="px-2.5 py-1 bg-green-500/10 text-green-600 dark:text-green-500 rounded-lg text-[10px] font-bold">قبول</span>
                                @elseif ($emp->status == 1)
                                    <span class="px-2.5 py-1 bg-red-500/10 text-red-600 dark:text-red-500 rounded-lg text-[10px] font-bold">رفض</span>
                                @elseif ($emp->status == 3)
                                    <span class="px-2.5 py-1 bg-sky-500/10 text-sky-600 dark:text-sky-500 rounded-lg text-[10px] font-bold">جاري التنفيذ</span>
                                @endif
                            </td>
                            @if ($emp->status == 3)
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="showPixelModal('ok-modal-{{ $emp->id }}')" class="p-2 bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-500 hover:bg-green-200 dark:hover:bg-green-500/20 rounded-lg transition-colors">
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                    </button>
                                    <button onclick="showPixelModal('delete-modal-{{ $emp->id }}')" class="p-2 bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-500 hover:bg-red-200 dark:hover:bg-red-500/20 rounded-lg transition-colors">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                            @endif
                        </tr>

                        @if ($emp->status == 3)
                        <!-- Accept Modal -->
                        <div id="ok-modal-{{ $emp->id }}" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
                            <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                            <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-md rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
                                <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                        <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i> قبول الاستئذان
                                    </h3>
                                    <button type="button" onclick="hidePixelModal('ok-modal-{{ $emp->id }}')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                                        <i data-lucide="x" class="w-5 h-5"></i>
                                    </button>
                                </div>
                                <form action="{{ route('ok_plz') }}" method="post">
                                    {{ csrf_field() }}
                                    <div class="p-6 space-y-4">
                                        <input type="hidden" name="employees_id" value="{{ $emp->employees_id }}">
                                        <input type="hidden" name="id" value="{{ $emp->id }}">
                                        
                                        <div>
                                            <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">التاريخ</label>
                                            <input required type="date" name="date" value="{{ $emp->dateplz }}" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                                        </div>

                                        <div class="flex gap-6 py-2">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input name="in_out" type="radio" value="0" {{ $emp->in_out == 0 ? 'checked' : '' }} class="w-4 h-4 text-sky-500 focus:ring-sky-500">
                                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">دخول</span>
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input name="in_out" type="radio" value="1" {{ $emp->in_out == 1 ? 'checked' : '' }} class="w-4 h-4 text-sky-500 focus:ring-sky-500">
                                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">خروج</span>
                                            </label>
                                        </div>

                                        <div class="grid grid-cols-3 gap-4">
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-500 mb-2">المدة (د)</label>
                                                <input required type="number" name="min" value="{{ $emp->min }}" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-2 rounded-xl text-xs">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-red-500 mb-2">خصم (د)</label>
                                                <input required type="number" name="dis" value="0" class="w-full bg-gray-50 dark:bg-[#111] border border-red-200 dark:border-red-500/20 text-gray-900 dark:text-white px-3 py-2 rounded-xl text-xs">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-red-500 mb-2">خصم (قيمة)</label>
                                                <input required type="number" name="dis_value" value="0" class="w-full bg-gray-50 dark:bg-[#111] border border-red-200 dark:border-red-500/20 text-gray-900 dark:text-white px-3 py-2 rounded-xl text-xs">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-[11px] font-bold text-gray-500 mb-2">السبب</label>
                                            <textarea required name="dec" rows="2" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2 rounded-xl text-xs outline-none focus:border-sky-500/50">{{ $emp->textplz }}</textarea>
                                        </div>
                                    </div>
                                    <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3">
                                        <button type="button" onclick="hidePixelModal('ok-modal-{{ $emp->id }}')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                                        <button type="submit" class="px-5 py-2.5 bg-green-500 text-white hover:bg-green-600 rounded-xl text-xs font-bold shadow-lg shadow-green-500/10 transition-all active:scale-95">تأكيد القبول</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Reject Modal -->
                        <div id="delete-modal-{{ $emp->id }}" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
                            <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                            <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-md rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
                                <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent">
                                    <h3 class="text-sm font-bold text-red-500 flex items-center gap-2">
                                        <i data-lucide="alert-triangle" class="w-5 h-5"></i> رفض الطلب
                                    </h3>
                                    <button type="button" onclick="hidePixelModal('delete-modal-{{ $emp->id }}')" class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                                        <i data-lucide="x" class="w-5 h-5"></i>
                                    </button>
                                </div>
                                <form action="{{ route('no_plz') }}" method="post">
                                    {{ method_field('delete') }}
                                    {{ csrf_field() }}
                                    <div class="p-6 space-y-4">
                                        <p class="text-sm text-gray-900 dark:text-white font-bold text-center">هل أنت متأكد من رفض وحذف هذا الطلب؟</p>
                                        <div class="bg-gray-50 dark:bg-[#111] p-4 rounded-xl border border-gray-100 dark:border-white/5 text-center">
                                            <input type="hidden" name="id" value="{{ $emp->id }}">
                                            <input type="hidden" name="employees_id" value="{{ $emp->employees_id }}">
                                            <span class="text-lg text-gray-900 dark:text-white font-bold">{{ $emp->name }}</span>
                                            <p class="text-xs text-gray-500 mt-1">طلب استئذان بتاريخ: {{ $emp->dateplz }}</p>
                                        </div>
                                    </div>
                                    <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3">
                                        <button type="button" onclick="hidePixelModal('delete-modal-{{ $emp->id }}')" class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                                        <button type="submit" class="px-5 py-2.5 bg-red-500 text-white hover:bg-red-600 rounded-xl text-xs font-bold shadow-lg shadow-red-500/10 transition-all active:scale-95">تأكيد الرفض</button>
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
@endsection

@section('js')
    <!-- Select2 -->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Table is automatically initialized by js.js
        });
        
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
    </script>
@endsection
