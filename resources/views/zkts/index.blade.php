@extends('layouts_new.app')
@section('title')
    تقرير الموظفين | نظام إدارة الموظفين
@endsection

@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        @media print {
            body * { visibility: hidden; }
            #printt, #printt * { visibility: visible; }
            #printt { position: absolute; left: 0; top: 0; width: 100%; background: white; }
            .no-print { display: none !important; }
        }
        .status-present {
            background: rgba(34,197,94,0.12);
            color: #16a34a;
            border: 1px solid rgba(34,197,94,0.2);
        }
        .status-absent {
            background: rgba(239,68,68,0.12);
            color: #dc2626;
            border: 1px solid rgba(239,68,68,0.2);
        }
        .status-other {
            background: rgba(14,165,233,0.12);
            color: #0284c7;
            border: 1px solid rgba(14,165,233,0.2);
        }
        .table-row-hover:hover {
            background: rgba(56,189,248,0.06);
            transition: background 0.15s;
        }
        .time-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-weight: 700;
            font-size: 0.75rem;
            padding: 2px 8px;
            border-radius: 8px;
        }
        .time-in { background: rgba(34,197,94,0.1); color: #16a34a; }
        .time-out { background: rgba(239,68,68,0.1); color: #dc2626; }
        .time-break { background: rgba(249,115,22,0.1); color: #ea580c; }
        .time-empty { background: rgba(156,163,175,0.1); color: #9ca3af; }
    </style>
@endsection

@section('page-header')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
            <a href="{{ route('dashboard') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
            <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
            <span class="text-sky-600 dark:text-sky-500">تقرير الموظفين</span>
        </nav>
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">تقرير الموظفين</h1>
        </div>
        <p class="text-sm text-gray-500 mt-1 mr-5">عرض وإدارة سجلات حضور وغياب الموظفين من جهاز البصمة</p>
    </div>
    <div class="flex items-center gap-3 no-print">
        <button onclick="window.print()" class="flex items-center gap-2 px-4 py-2.5 bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-white/10 rounded-xl text-sm font-bold transition-all border border-gray-200 dark:border-white/5">
            <i data-lucide="printer" class="w-4 h-4"></i>
            طباعة
        </button>
        {{-- <a href="{{ route('zkts.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-sm font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95">
            <i data-lucide="plus" class="w-4 h-4"></i>
            إضافة سجل
        </a> --}}
    </div>
</div>

@if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 text-green-700 dark:text-green-400 px-5 py-4 rounded-2xl mb-6 text-sm font-bold">
        <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="flex items-start gap-3 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-700 dark:text-red-400 px-5 py-4 rounded-2xl mb-6 text-sm font-bold">
        <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
        <ul class="space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@endsection

@section('content')
<div class="space-y-6" id="printt">

    {{-- ===== بطاقات الإحصاء ===== --}}
    @php
        $totalRecords = $zkts->total();
    @endphp

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 no-print">
        <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-11 h-11 bg-sky-100 dark:bg-sky-500/10 rounded-xl flex items-center justify-center shrink-0">
                <i data-lucide="users" class="w-5 h-5 text-sky-600 dark:text-sky-500"></i>
            </div>
            <div>
                <p class="text-[10px] uppercase tracking-widest font-bold text-gray-500 mb-0.5">إجمالي السجلات</p>
                <p class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($totalRecords) }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-11 h-11 bg-green-100 dark:bg-green-500/10 rounded-xl flex items-center justify-center shrink-0">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-600 dark:text-green-500"></i>
            </div>
            <div>
                <p class="text-[10px] uppercase tracking-widest font-bold text-gray-500 mb-0.5">حاضر</p>
                <p class="text-2xl font-black text-green-600 dark:text-green-400">{{ number_format($presentCount) }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-2xl p-5 flex items-center gap-4">
            <div class="w-11 h-11 bg-red-100 dark:bg-red-500/10 rounded-xl flex items-center justify-center shrink-0">
                <i data-lucide="x-circle" class="w-5 h-5 text-red-600 dark:text-red-500"></i>
            </div>
            <div>
                <p class="text-[10px] uppercase tracking-widest font-bold text-gray-500 mb-0.5">غائب</p>
                <p class="text-2xl font-black text-red-600 dark:text-red-400">{{ number_format($absentCount) }}</p>
            </div>
        </div>
    </div>

    {{-- ===== فلتر البحث ===== --}}
    <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-6 shadow-sm no-print">
        <form action="{{ route('zkts.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الموظف</label>
                <select name="employee_id" class="form-control form-control-sm select2 w-full">
                    <option value="">-- الكل --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                            {{ $emp->num }} - {{ $emp->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">من تاريخ</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2 rounded-xl outline-none focus:border-sky-500/50 text-sm transition-colors">
            </div>

            <div>
                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">إلى تاريخ</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2 rounded-xl outline-none focus:border-sky-500/50 text-sm transition-colors">
            </div>

            <div>
                <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الحالة</label>
                <select name="absent" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2 rounded-xl outline-none focus:border-sky-500/50 text-sm transition-colors">
                    <option value="">-- الكل --</option>
                    <option value="حاضر" {{ request('absent') == 'حاضر' ? 'selected' : '' }}>حاضر</option>
                    <option value="غائب" {{ request('absent') == 'غائب' ? 'selected' : '' }}>غائب</option>
                    <option value="نهاية وردية" {{ request('absent') == 'نهاية وردية' ? 'selected' : '' }}>نهاية وردية</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-sm font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    بحث
                </button>
                <a href="{{ route('zkts.index') }}" class="flex items-center justify-center px-3 py-2.5 bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-white/10 rounded-xl transition-all border border-gray-200 dark:border-white/5" title="إعادة تعيين">
                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- ===== جدول البيانات ===== --}}
    <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 dark:border-white/5 no-print">
            <div class="flex items-center gap-3">
                <div class="w-1 h-5 bg-sky-500 rounded-full"></div>
                <h3 class="text-sm font-black text-gray-900 dark:text-white">سجلات الحضور والغياب</h3>
                <span class="text-[10px] bg-sky-500/10 text-sky-600 dark:text-sky-500 px-2 py-0.5 rounded-full font-bold">{{ $zkts->total() }} سجل</span>
            </div>
            <form action="{{ route('zkts.bulk_delete') }}" method="POST" id="bulk-delete-form" onsubmit="return confirm('هل أنت متأكد من حذف السجلات المحددة؟')">
                @csrf
                @method('DELETE')
                <div id="bulk-ids-container"></div>
                <button type="submit" id="bulk-delete-btn"
                    class="hidden flex items-center gap-2 px-4 py-2 bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-500/20 rounded-xl text-xs font-bold transition-all border border-red-500/20">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    حذف المحدد
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right text-sm">
                <thead>
                    <tr class="bg-gray-50 dark:bg-[#111] border-b border-gray-200 dark:border-white/10">
                        <th class="py-3 px-3 text-center no-print">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-sky-500 cursor-pointer">
                        </th>
                        <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">#</th>
                        <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">الموظف</th>
                        <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">التاريخ</th>
                        <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">دخول</th>
                        <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">خروج</th>
                        <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">خروج استراحة</th>
                        <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">دخول استراحة</th>
                        <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th class="py-3 px-4 text-[11px] font-bold text-gray-500 uppercase tracking-wider no-print">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                    @forelse($zkts as $index => $row)
                        <tr class="table-row-hover {{ ($row->in || $row->out) ? '' : 'bg-red-50/30 dark:bg-red-900/5' }}">
                            <td class="py-3 px-3 text-center no-print">
                                <input type="checkbox" name="ids[]" value="{{ $row->id }}" class="row-checkbox rounded border-gray-300 text-sky-500 cursor-pointer">
                            </td>
                            <td class="py-3 px-4 text-xs text-gray-400 font-bold">{{ $zkts->firstItem() + $index }}</td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-sky-100 dark:bg-sky-500/10 flex items-center justify-center text-sky-600 dark:text-sky-500 text-xs font-black shrink-0">
                                        {{ mb_substr($row->name ?? '؟', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-900 dark:text-white">{{ $row->name }}</p>
                                        <p class="text-[10px] text-gray-400">رقم: {{ $row->numid }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <p class="text-xs font-bold text-gray-900 dark:text-white">{{ $row->date }}</p>
                                <p class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($row->date)->locale('ar')->dayName }}</p>
                            </td>
                            <td class="py-3 px-4">
                                @if($row->in)
                                    <span class="time-badge time-in">
                                        <i data-lucide="log-in" style="width:11px;height:11px"></i>
                                        {{ \Carbon\Carbon::parse($row->in)->format('H:i') }}
                                    </span>
                                @else
                                    <span class="time-badge time-empty">--</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($row->out)
                                    <span class="time-badge time-out">
                                        <i data-lucide="log-out" style="width:11px;height:11px"></i>
                                        {{ \Carbon\Carbon::parse($row->out)->format('H:i') }}
                                    </span>
                                @else
                                    <span class="time-badge time-empty">--</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($row->break_out)
                                    <span class="time-badge time-break">{{ \Carbon\Carbon::parse($row->break_out)->format('H:i') }}</span>
                                @else
                                    <span class="time-badge time-empty">--</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($row->break_in)
                                    <span class="time-badge time-break">{{ \Carbon\Carbon::parse($row->break_in)->format('H:i') }}</span>
                                @else
                                    <span class="time-badge time-empty">--</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @php
                                    if ($row->in || $row->out) {
                                        $statusClass = 'status-present';
                                        $statusLabel = 'حاضر';
                                    } elseif ($row->absent === 'نهاية وردية') {
                                        $statusClass = 'status-other';
                                        $statusLabel = 'نهاية وردية';
                                    } else {
                                        $statusClass = 'status-absent';
                                        $statusLabel = 'غائب';
                                    }
                                @endphp
                                <span class="{{ $statusClass }} px-3 py-1 rounded-full text-[11px] font-bold inline-block">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="py-3 px-4 no-print">
                                <div class="flex items-center gap-1.5 justify-end">
                                    <a href="{{ route('zkts.show', $row->id) }}"
                                        class="p-1.5 rounded-lg bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-colors"
                                        title="عرض">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    </a>
                                    {{-- <a href="{{ route('zkts.edit', $row->id) }}"
                                        class="p-1.5 rounded-lg bg-sky-50 dark:bg-sky-500/10 text-sky-600 dark:text-sky-400 hover:bg-sky-100 dark:hover:bg-sky-500/20 transition-colors"
                                        title="تعديل">
                                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                    </a> --}}
                                    {{-- <form action="{{ route('zkts.destroy', $row->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 transition-colors"
                                            title="حذف">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form> --}}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-gray-400">
                                    <i data-lucide="inbox" class="w-12 h-12 opacity-30"></i>
                                    <p class="text-sm font-bold">لا توجد سجلات حضور</p>
                                    <p class="text-xs">جرب تغيير معايير البحث أو أضف سجلاً جديداً</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($zkts->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 no-print">
                {{ $zkts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({ placeholder: 'اختر...', allowClear: true, width: '100%' });

            // تحديد الكل
            document.getElementById('select-all')?.addEventListener('change', function () {
                document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = this.checked);
                updateBulkBtn();
            });

            document.querySelectorAll('.row-checkbox').forEach(cb => {
                cb.addEventListener('change', updateBulkBtn);
            });

            function updateBulkBtn() {
                const checked = document.querySelectorAll('.row-checkbox:checked');
                const btn = document.getElementById('bulk-delete-btn');
                const container = document.getElementById('bulk-ids-container');
                if (checked.length > 0) {
                    btn.classList.remove('hidden');
                    btn.classList.add('flex');
                    container.innerHTML = '';
                    checked.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = cb.value;
                        container.appendChild(input);
                    });
                } else {
                    btn.classList.add('hidden');
                    btn.classList.remove('flex');
                }
            }
        });
    </script>
@endsection
