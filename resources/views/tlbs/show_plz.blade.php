@extends('layouts_new.app')

@section('title')
    طلبات الاستئذان | Pixel Security
@endsection

@section('css')
    <!-- Datatables -->
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}">
@endsection

@section('page-header')
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}"
                    class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">طلبات الموظفين</span>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">الاستئذان</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">طلبات الاستئذان
                    المعلقة</h1>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div
        class="bg-white dark:bg-[#111] rounded-[2.5rem] border border-gray-100 dark:border-white/5 shadow-sm overflow-hidden transition-all">
        <div
            class="p-8 border-b border-gray-100 dark:border-white/5 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gray-50/50 dark:bg-transparent">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-sky-500/10 flex items-center justify-center text-sky-600 dark:text-sky-500">
                    <i data-lucide="clock-alert" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-gray-900 dark:text-white">قائمة الطلبات</h3>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">إدارة طلبات الاستئذان المرسلة من
                        الموظفين</p>
                </div>
            </div>
        </div>

        <div class="p-0">
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse pixel-table-modern">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-white/5">
                            <th
                                class="py-5 px-8 text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                #</th>
                            <th
                                class="py-5 px-8 text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                اسم الموظف</th>
                            <th
                                class="py-5 px-8 text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                التاريخ</th>
                            <th
                                class="py-5 px-8 text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                                نوع الإذن</th>
                            <th
                                class="py-5 px-8 text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest text-center">
                                المدة</th>
                            <th
                                class="py-5 px-8 text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest text-center">
                                السبب</th>
                            <th
                                class="py-5 px-8 text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest text-left">
                                العمليات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                        @php $i = 0; @endphp
                        @foreach ($tlbs as $emp)
                            @if ($emp->dateplz && $emp->textplz && $emp->employees_id && $emp->name && $emp->status == 3)
                                @php $i++; @endphp
                                <tr class="group hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-all">
                                    <td class="py-5 px-8 text-xs font-bold text-gray-400">#{{ $i }}</td>
                                    <td class="py-5 px-8 text-sm font-black text-gray-900 dark:text-white">
                                        {{ $emp->name }}</td>
                                    <td class="py-5 px-8">
                                        <div
                                            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 text-[11px] font-bold">
                                            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                            {{ $emp->dateplz }}
                                        </div>
                                    </td>
                                    <td class="py-5 px-8">
                                        @if ($emp->in_out == 0)
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-500/10 text-green-600 dark:text-green-500 text-[10px] font-black uppercase tracking-widest">
                                                <i data-lucide="arrow-right-circle" class="w-3 h-3"></i> دخول
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-500/10 text-orange-600 dark:text-orange-500 text-[10px] font-black uppercase tracking-widest">
                                                <i data-lucide="arrow-left-circle" class="w-3 h-3"></i> خروج
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-5 px-8 text-center">
                                        <span
                                            class="text-xs font-bold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-white/5 px-3 py-1 rounded-lg">{{ $emp->min }}
                                            دقيقة</span>
                                    </td>
                                    <td class="py-5 px-8 text-center max-w-xs">
                                        <div
                                            class="text-xs text-gray-500 dark:text-gray-400 font-medium bg-gray-50 dark:bg-[#181818] p-3 rounded-2xl border border-gray-100 dark:border-white/5">
                                            {{ $emp->textplz }}
                                        </div>
                                    </td>
                                    <td class="py-5 px-8 text-left">
                                        <div class="flex items-center justify-end gap-2">
                                            <button onclick="showPixelModal('ok{{ $emp->id }}')"
                                                class="w-10 h-10 rounded-xl bg-green-500/10 text-green-600 dark:text-green-500 flex items-center justify-center hover:bg-green-500 hover:text-white transition-all shadow-lg shadow-green-500/5 active:scale-90"
                                                title="قبول">
                                                <i data-lucide="check" class="w-5 h-5"></i>
                                            </button>
                                            <button onclick="showPixelModal('delete{{ $emp->id }}')"
                                                class="w-10 h-10 rounded-xl bg-red-500/10 text-red-600 dark:text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-lg shadow-red-500/5 active:scale-90"
                                                title="رفض">
                                                <i data-lucide="x" class="w-5 h-5"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Accept Modal -->
                                <div id="ok{{ $emp->id }}"
                                    class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
                                    <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                                    <div
                                        class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-lg rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">
                                        <div
                                            class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent transition-colors">
                                            <h3
                                                class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                                <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i> قبول طلب
                                                الاستئذان
                                            </h3>
                                            <button onclick="hidePixelModal('ok{{ $emp->id }}')"
                                                class="text-gray-400 hover:text-red-500 transition-colors outline-none">
                                                <i data-lucide="x" class="w-5 h-5"></i>
                                            </button>
                                        </div>
                                        <form action="{{ route('ok_plz') }}" method="post">
                                            @csrf
                                            <div class="p-6 space-y-4">
                                                <input type="hidden" name="employees_id" value="{{ $emp->employees_id }}">
                                                <input type="hidden" name="id" value="{{ $emp->id }}">

                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label
                                                            class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">التاريخ</label>
                                                        <input required
                                                            class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors"
                                                            type="date" name="date" value="{{ $emp->dateplz }}">
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">المدة
                                                            (بالدقيقة)</label>
                                                        <input required
                                                            class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors"
                                                            type="number" name="min" value="{{ $emp->min }}">
                                                    </div>
                                                </div>

                                                <div
                                                    class="flex items-center gap-6 p-4 bg-gray-50 dark:bg-white/[0.02] rounded-2xl border border-gray-100 dark:border-white/5">
                                                    <label class="flex items-center gap-2 cursor-pointer group">
                                                        <input name="in_out" type="radio" value="0"
                                                            {{ $emp->in_out == 0 ? 'checked' : '' }} required
                                                            class="w-4 h-4 text-sky-500 border-gray-300 focus:ring-sky-500 dark:bg-[#111] dark:border-white/10">
                                                        <span
                                                            class="text-xs font-bold text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">إذن
                                                            دخول</span>
                                                    </label>
                                                    <label class="flex items-center gap-2 cursor-pointer group">
                                                        <input name="in_out" type="radio" value="1"
                                                            {{ $emp->in_out == 1 ? 'checked' : '' }} required
                                                            class="w-4 h-4 text-sky-500 border-gray-300 focus:ring-sky-500 dark:bg-[#111] dark:border-white/10">
                                                        <span
                                                            class="text-xs font-bold text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">إذن
                                                            خروج</span>
                                                    </label>
                                                </div>

                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label
                                                            class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">خصم
                                                            بالدقيقة</label>
                                                        <input required
                                                            class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors"
                                                            type="number" name="dis" value="0">
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">خصم
                                                            قيمة مباشرة</label>
                                                        <input required
                                                            class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors"
                                                            type="number" name="dis_value" value="0">
                                                    </div>
                                                </div>

                                                <div>
                                                    <label
                                                        class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">سبب
                                                        الإذن (للتأكيد)</label>
                                                    <textarea required name="dec" rows="2"
                                                        class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">{{ $emp->textplz }}</textarea>
                                                </div>
                                            </div>
                                            <div
                                                class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-end gap-3 transition-colors">
                                                <button type="button" onclick="hidePixelModal('ok{{ $emp->id }}')"
                                                    class="px-5 py-2.5 bg-gray-200 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-white/10 rounded-xl text-xs font-bold transition-all">إلغاء</button>
                                                <button type="submit"
                                                    class="px-5 py-2.5 bg-green-500 text-white hover:bg-green-600 rounded-xl text-xs font-bold shadow-lg shadow-green-500/10 transition-all active:scale-95">تأكيد
                                                    القبول</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Reject/Delete Modal -->
                                <div id="delete{{ $emp->id }}"
                                    class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
                                    <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                                    <div
                                        class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-md rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col text-center">
                                        <div class="p-8">
                                            <div
                                                class="w-20 h-20 bg-red-100 dark:bg-red-500/10 rounded-3xl flex items-center justify-center text-red-600 dark:text-red-500 mx-auto mb-6 shadow-xl shadow-red-500/5">
                                                <i data-lucide="alert-triangle" class="w-10 h-10"></i>
                                            </div>
                                            <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">رفض الطلب؟
                                            </h3>
                                            <p class="text-sm text-gray-500 font-bold leading-relaxed mb-8">هل أنت متأكد من
                                                رفض وحذف طلب الاستئذان هذا؟<br>لا يمكن التراجع عن هذه العملية.</p>

                                            <form action="{{ route('no_plz') }}" method="post"
                                                class="flex flex-col gap-3">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="id" value="{{ $emp->id }}">
                                                <input type="hidden" name="employees_id"
                                                    value="{{ $emp->employees_id }}">

                                                <button type="submit"
                                                    class="w-full py-4 bg-red-500 hover:bg-red-600 text-white rounded-2xl text-sm font-black shadow-xl shadow-red-500/20 transition-all active:scale-95">نعم،
                                                    ارفض الطلب</button>
                                                <button type="button"
                                                    onclick="hidePixelModal('delete{{ $emp->id }}')"
                                                    class="w-full py-4 bg-gray-100 dark:bg-white/5 text-gray-500 hover:text-gray-900 dark:hover:text-white rounded-2xl text-sm font-bold transition-all">إلغاء
                                                    التراجع</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Datatables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Table is automatically initialized by js.js
        });

        function showPixelModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modal.querySelector('.modal-content').classList.remove('scale-95');
                document.body.style.overflow = 'hidden';
            }
        }

        function hidePixelModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('opacity-0', 'pointer-events-none');
                modal.querySelector('.modal-content').classList.add('scale-95');
                document.body.style.overflow = '';
            }
        }
    </script>
@endsection
