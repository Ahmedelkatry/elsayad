@extends('layouts_new.app')

@section('title')
    تعديل جدول الشيفتات | نظام إدارة الموظفين
@endsection

@section('css')
    <style>
        .shift-day-card {
            transition: all 0.3s ease;
        }
        .shift-day-card:hover {
            border-color: rgba(234, 179, 8, 0.4);
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.1);
        }
    </style>
@endsection

@section('page-header')
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">الشيفتات</span>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">تعديل جدول الشيفتات</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">تعديل جدول الشيفتات</h1>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="space-y-8">
        <!-- Search Bar -->
        <div class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-3xl p-6 shadow-sm">
            <form action="{{ route('shift_w.edit') }}" method="GET" class="flex flex-col md:flex-row items-end gap-4">
                <div class="flex-1 space-y-2">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest px-2">من تاريخ</label>
                    <input type="date" name="start" value="{{ request('start') }}" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-100 dark:border-white/10 text-gray-900 dark:text-white px-5 py-3 rounded-2xl outline-none focus:border-sky-500/50 transition-all">
                </div>
                <div class="flex-1 space-y-2">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest px-2">إلى تاريخ</label>
                    <input type="date" name="end" value="{{ request('end') }}" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-100 dark:border-white/10 text-gray-900 dark:text-white px-5 py-3 rounded-2xl outline-none focus:border-sky-500/50 transition-all">
                </div>
                <button type="submit" class="bg-sky-500 text-white font-bold py-3.5 px-8 rounded-2xl shadow-lg shadow-sky-500/20 hover:bg-sky-400 transition-all flex items-center gap-2 active:scale-95">
                    <i data-lucide="search" class="w-5 h-5"></i>
                    بحث
                </button>
            </form>
        </div>

        <!-- Main Grid -->
        <form action="{{ route('shift_w.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($days as $index => $day)
                    @php
                        $dateStr = $day->format('Y-m-d');
                        $savedDayShifts = isset($existingShifts[$dateStr]) ? $existingShifts[$dateStr] : collect();
                        $firstShiftId = $savedDayShifts->first()->shift_id ?? null;
                    @endphp

                    <div class="shift-day-card bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-6 shadow-sm flex flex-col relative overflow-hidden" data-index="{{ $index }}">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-sky-500/5 rounded-full -mr-12 -mt-12"></div>
                        
                        <div class="text-center mb-6">
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-sky-600 dark:text-sky-500 block mb-1">
                                {{ $day->translatedFormat('l') }}
                            </span>
                            <h5 class="text-xl font-black text-gray-900 dark:text-white">{{ $dateStr }}</h5>
                        </div>

                        <div class="space-y-4 flex-1">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">نوع الشيفت</label>
                                <select name="days[{{ $index }}][shift_id]" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-100 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors appearance-none">
                                    @foreach ($shifts as $shift)
                                        <option value="{{ $shift->id }}" {{ $firstShiftId == $shift->id ? 'selected' : '' }}>
                                            {{ $shift->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">الأقسام</label>
                                <div class="flex flex-wrap gap-1.5 max-h-32 overflow-y-auto p-1 custom-scrollbar">
                                    @foreach ($secs as $sec)
                                        <button type="button" class="sectionItem text-[10px] font-bold px-3 py-1.5 rounded-lg border border-gray-100 dark:border-white/5 text-gray-600 dark:text-gray-400 hover:border-sky-500/50 hover:text-sky-600 dark:hover:text-sky-500 transition-all bg-gray-50/50 dark:bg-white/5" 
                                            data-employees='@json($sec->employees)'>
                                            {{ $sec->name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Employee Selection List -->
                            <div class="empList mt-4 space-y-1.5 max-h-48 overflow-y-auto p-2 bg-gray-50 dark:bg-[#111] rounded-2xl border border-gray-100 dark:border-white/5">
                                <p class="text-[9px] text-gray-400 text-center py-4">اختر قسماً لعرض الموظفين</p>
                            </div>

                            <!-- Selected Employees Preview -->
                            <div class="previewDiv mt-4 space-y-2 {{ $savedDayShifts->count() > 0 ? '' : 'hidden' }}">
                                <label class="block text-[10px] font-bold text-sky-600 dark:text-sky-500 uppercase tracking-widest px-1 mb-2">المختارين</label>
                                <div class="space-y-2">
                                    @foreach ($savedDayShifts as $savedShift)
                                        @if ($savedShift->employees)
                                            <div class="flex items-center justify-between gap-2 p-3 bg-sky-500/5 border border-sky-500/20 rounded-xl group/selected" data-id="{{ $savedShift->employees_id }}">
                                                <span class="text-[10px] font-bold text-gray-900 dark:text-white">{{ $savedShift->employees->name }}</span>
                                                <button type="button" class="removeEmp p-1.5 text-red-500 hover:bg-red-500/10 rounded-lg transition-colors">
                                                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                                </button>
                                                <input type="hidden" name="days[{{ $index }}][employee_ids][]" value="{{ $savedShift->employees_id }}">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="days[{{ $index }}][date]" value="{{ $dateStr }}">
                    </div>
                @endforeach
            </div>

            <div class="sticky bottom-8 left-0 right-0 px-4 md:px-0">
                <button type="submit" class="w-full max-w-2xl mx-auto bg-sky-500 text-white font-black py-5 rounded-[2rem] shadow-2xl shadow-sky-500/40 hover:bg-sky-400 hover:-translate-y-1 transition-all active:scale-95 flex items-center justify-center gap-3 text-lg border-4 border-white dark:border-[#111]">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                    تحديث جدول الأسبوع
                </button>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            document.querySelectorAll('.shift-day-card').forEach(card => {
                const empListDiv = card.querySelector('.empList');
                const previewDiv = card.querySelector('.previewDiv');
                const previewContainer = previewDiv.querySelector('div');
                const index = card.dataset.index;

                card.querySelectorAll('.sectionItem').forEach(section => {
                    section.addEventListener('click', function() {
                        const employees = JSON.parse(this.dataset.employees);

                        card.querySelectorAll('.sectionItem').forEach(btn => btn.classList.remove('bg-sky-500/10', 'border-sky-500/30', 'text-sky-600'));
                        
                        if (empListDiv.dataset.currentSection === this.textContent) {
                            empListDiv.innerHTML = '<p class="text-[9px] text-gray-400 text-center py-4">اختر قسماً لعرض الموظفين</p>';
                            empListDiv.dataset.currentSection = '';
                            return;
                        }

                        this.classList.add('bg-sky-500/10', 'border-sky-500/30', 'text-sky-600');
                        empListDiv.innerHTML = '';
                        empListDiv.dataset.currentSection = this.textContent;

                        if (employees.length === 0) {
                            empListDiv.innerHTML = '<p class="text-[9px] text-gray-400 text-center py-4">لا يوجد موظفين في هذا القسم</p>';
                        }

                        employees.forEach(emp => {
                            const btn = document.createElement('button');
                            btn.type = 'button';
                            btn.classList.add('empItem', 'w-full', 'text-right', 'px-4', 'py-2', 'text-[10px]', 'font-bold', 'rounded-xl', 'bg-white', 'dark:bg-[#181818]', 'border', 'border-gray-100', 'dark:border-white/5', 'text-gray-700', 'dark:text-gray-300', 'hover:border-sky-500/50', 'transition-all', 'mb-1');
                            btn.dataset.id = emp.id;
                            btn.textContent = emp.name;
                            empListDiv.appendChild(btn);
                        });
                    });
                });

                empListDiv.addEventListener('click', function(e) {
                    const target = e.target.closest('.empItem');
                    if (target) {
                        const id = target.dataset.id;
                        const name = target.textContent;

                        if (previewContainer.querySelector(`[data-id='${id}']`)) {
                            return;
                        }

                        const item = document.createElement('div');
                        item.classList.add('flex', 'items-center', 'justify-between', 'gap-2', 'p-3', 'bg-sky-500/5', 'border', 'border-sky-500/20', 'rounded-xl');
                        item.dataset.id = id;

                        item.innerHTML = `
                            <span class="text-[10px] font-bold text-gray-900 dark:text-white">${name}</span>
                            <button type="button" class="removeEmp p-1.5 text-red-500 hover:bg-red-500/10 rounded-lg transition-colors">
                                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                            </button>
                            <input type="hidden" name="days[${index}][employee_ids][]" value="${id}">
                        `;

                        previewContainer.appendChild(item);
                        previewDiv.classList.remove('hidden');
                        lucide.createIcons();
                    }
                });

                previewDiv.addEventListener('click', function(e) {
                    const btn = e.target.closest('.removeEmp');
                    if (btn) {
                        btn.closest('[data-id]').remove();
                        if (previewContainer.children.length === 0) {
                            previewDiv.classList.add('hidden');
                        }
                    }
                });
            });
        });
    </script>
@endsection
