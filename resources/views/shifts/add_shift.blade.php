@extends('layouts_new.app')
@section('title')
    إضافة شيفت | نظام إدارة الموظفين
@endsection

@section('css')
    <style>
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
                <a href="{{ route('shifts.index') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الشيفتات</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">إضافة شيفت</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">إضافة شيفت جديد</h1>
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

    @if (session()->has('Add'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong>{{ session()->get('Add') }}</strong>
        </div>
    @endif
@endsection

@section('content')
    <div id="app" class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-6 shadow-sm">
        <form action="{{ route('shifts.store') }}" method="post" autocomplete="off">
            {{ csrf_field() }}

            <!-- نوع الشيفت -->
            <div class="mb-8">
                <h4 class="text-sm font-bold text-sky-600 dark:text-sky-500 uppercase tracking-wider border-b border-gray-100 dark:border-white/5 pb-3 mb-6 flex items-center gap-2">
                    <i data-lucide="settings" class="w-4 h-4"></i> نوع الشيفت والاسم
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- شيفت بتحديد الوقت Card -->
                    <label class="relative flex flex-col p-5 bg-gray-50 dark:bg-[#111] border rounded-2xl cursor-pointer transition-all hover:border-sky-500/50" :class="sh == 1 ? 'border-sky-500 bg-sky-500/5 dark:bg-sky-500/5 dark:border-sky-500' : 'border-gray-200 dark:border-white/5'">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm font-extrabold text-gray-900 dark:text-white">شيفت بتحديد الوقت</span>
                            <input type="radio" name="shift_time" value="1" v-model="sh" class="custom-radio text-sky-500 focus:ring-sky-500">
                        </div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 leading-relaxed">يتطلب تحديد موعد دقيق لبداية الشيفت ونهايته (مثال: من 09:00 ص إلى 05:00 م).</p>
                    </label>

                    <!-- شيفت بساعات العمل Card -->
                    <label class="relative flex flex-col p-5 bg-gray-50 dark:bg-[#111] border rounded-2xl cursor-pointer transition-all hover:border-sky-500/50" :class="sh == 0 ? 'border-sky-500 bg-sky-500/5 dark:bg-sky-500/5 dark:border-sky-500' : 'border-gray-200 dark:border-white/5'">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm font-extrabold text-gray-900 dark:text-white">شيفت بساعات العمل</span>
                            <input type="radio" name="shift_time" value="0" v-model="sh" class="custom-radio text-sky-500 focus:ring-sky-500">
                        </div>
                        <p class="text-xs text-gray-400 dark:text-gray-500 leading-relaxed">يعتمد فقط على مجموع ساعات الحضور دون التقيد بموعد بداية أو نهاية محدد.</p>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">اسم الشيفت</label>
                        <input type="text" name="name" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>
                    
                    <div v-if="sh == 1">
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">من الساعة</label>
                        <input type="time" name="from" :required="sh == 1" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>
                    
                    <div v-if="sh == 1">
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">إلى الساعة</label>
                        <input type="time" name="to" :required="sh == 1" class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>
                </div>
            </div>

            <!-- إعدادات التأخير والخصم -->
            <div class="mb-8">
                <h4 class="text-sm font-bold text-sky-600 dark:text-sky-500 uppercase tracking-wider border-b border-gray-100 dark:border-white/5 pb-3 mb-4 flex items-center gap-2">
                    <i data-lucide="clock" class="w-4 h-4"></i> إعدادات التأخير والخصم
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-blue-50 dark:bg-blue-500/5 p-4 rounded-xl border border-blue-100 dark:border-blue-500/10">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-blue-600 dark:text-blue-400 mb-2">تأخير بالدقيقة</label>
                                <input type="number" name="late" value="0" min="0" required class="w-full bg-white dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-lg outline-none focus:border-blue-500 text-xs">
                            </div>
                            <div>
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-blue-600 dark:text-blue-400 mb-2">الخصم بالدقيقة</label>
                                <input type="number" name="dism" value="0" min="0" required class="w-full bg-white dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-lg outline-none focus:border-blue-500 text-xs">
                            </div>
                        </div>
                    </div>

                    <div class="bg-indigo-50 dark:bg-indigo-500/5 p-4 rounded-xl border border-indigo-100 dark:border-indigo-500/10">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-indigo-600 dark:text-indigo-400 mb-2">(( 1 )) تأخير بالدقيقة</label>
                                <input type="number" name="late1" value="0" min="0" required class="w-full bg-white dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-lg outline-none focus:border-indigo-500 text-xs">
                            </div>
                            <div>
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-indigo-600 dark:text-indigo-400 mb-2">(( 1 )) الخصم بالدقيقة</label>
                                <input type="number" name="dism1" value="0" min="0" required class="w-full bg-white dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-lg outline-none focus:border-indigo-500 text-xs">
                            </div>
                        </div>
                    </div>

                    <div class="bg-orange-50 dark:bg-orange-500/5 p-4 rounded-xl border border-orange-100 dark:border-orange-500/10">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-orange-600 dark:text-orange-400 mb-2">(( 2 )) تأخير بالدقيقة</label>
                                <input type="number" name="late2" value="0" min="0" required class="w-full bg-white dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-lg outline-none focus:border-orange-500 text-xs">
                            </div>
                            <div>
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-orange-600 dark:text-orange-400 mb-2">(( 2 )) الخصم بالدقيقة</label>
                                <input type="number" name="dism2" value="0" min="0" required class="w-full bg-white dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-lg outline-none focus:border-orange-500 text-xs">
                            </div>
                        </div>
                    </div>

                    <div class="bg-red-50 dark:bg-red-500/5 p-4 rounded-xl border border-red-100 dark:border-red-500/10">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-red-600 dark:text-red-400 mb-2">(( 3 )) تأخير بالدقيقة</label>
                                <input type="number" name="late3" value="0" min="0" required class="w-full bg-white dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-lg outline-none focus:border-red-500 text-xs">
                            </div>
                            <div>
                                <label class="block text-[11px] uppercase tracking-widest font-bold text-red-600 dark:text-red-400 mb-2">(( 3 )) الخصم بالدقيقة</label>
                                <input type="number" name="dism3" value="0" min="0" required class="w-full bg-white dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2.5 rounded-lg outline-none focus:border-red-500 text-xs">
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="sh == 1" class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 dark:bg-[#111] p-4 rounded-xl border border-gray-100 dark:border-white/5">
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">خروج بدري (بالدقيقة)</label>
                        <input type="number" name="bdry" value="0" min="0" required class="w-full bg-white dark:bg-black border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الخصم خروج بالدقيقة</label>
                        <input type="number" name="disout" value="0" min="0" required class="w-full bg-white dark:bg-black border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>
                </div>
            </div>

            <!-- ساعات العمل والإضافي والغياب -->
            <div class="mb-8">
                <h4 class="text-sm font-bold text-sky-600 dark:text-sky-500 uppercase tracking-wider border-b border-gray-100 dark:border-white/5 pb-3 mb-4 flex items-center gap-2">
                    <i data-lucide="briefcase" class="w-4 h-4"></i> ساعات العمل والإضافي
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">عدد ساعات العمل</label>
                        <input type="number" name="workh" min="0" step="0.01" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>

                    <div v-if="sh == 1">
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">بداية الوقت الإضافي (OT)</label>
                        <input type="time" name="start_ot" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الإضافي بالساعة (الضعف)</label>
                        <input type="number" name="ot" value="1" step="0.01" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">خصم الغياب باليوم</label>
                        <input type="number" name="absent" value="1" step="0.01" required class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>
                </div>
            </div>

            <div class="flex justify-end border-t border-gray-100 dark:border-white/5 pt-6 mt-6">
                <button type="submit" class="px-8 py-3 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-sm font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    حفظ بيانات الشيفت
                </button>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script>
        const { createApp } = Vue;
        createApp({
            data() {
                return {
                    sh: "1",
                };
            }
        }).mount('#app')
    </script>
@endsection
