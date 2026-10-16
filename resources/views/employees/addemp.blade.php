@extends('layouts_new.app')
@section('title')
    إضافة موظف | نظام إدارة الموظفين
@endsection

@section('css')
    <!--- Internal Select2 css-->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
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

        .custom-checkbox {
            appearance: none;
            background-color: #fff;
            margin: 0;
            font: inherit;
            color: currentColor;
            width: 1.15em;
            height: 1.15em;
            border: 0.15em solid currentColor;
            border-radius: 0.2em;
            display: grid;
            place-content: center;
        }

        .dark .custom-checkbox {
            background-color: #111;
        }

        .custom-checkbox::before {
            content: "";
            width: 0.65em;
            height: 0.65em;
            transform: scale(0);
            transition: 120ms transform ease-in-out;
            box-shadow: inset 1em 1em var(--tw-ring-color);
            background-color: CanvasText;
            transform-origin: bottom left;
            clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
        }

        .custom-checkbox:checked::before {
            transform: scale(1);
        }
    </style>
@endsection

@section('page-header')
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}"
                    class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <a href="{{ route('employees.index') }}"
                    class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الموظفين</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">إضافة موظف</span>
            </nav>
            {{-- <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">إضافة موظف جديد
                </h1>
            </div> --}}
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
    <div id="app"
        class="bg-white dark:bg-[#181818] border border-gray-100 dark:border-white/5 rounded-[2rem] p-6 shadow-sm">
        <form action="{{ route('store_addzkt') }}" method="post">
            {{ csrf_field() }}

            <!-- إعدادات أجهزة البصمة -->
            <div class="mb-8">
                <h4
                    class="text-sm font-bold text-sky-600 dark:text-sky-500 uppercase tracking-wider border-b border-gray-100 dark:border-white/5 pb-3 mb-4 flex items-center gap-2">
                    <i data-lucide="fingerprint" class="w-4 h-4"></i> أجهزة البصمة
                </h4>

                <div class="bg-gray-50 dark:bg-[#111] p-4 rounded-xl border border-gray-200 dark:border-white/10 mb-6">
                    <div class="mb-4 pb-4 border-b border-gray-200 dark:border-white/10">
                        <label class="flex items-center gap-2 cursor-pointer w-max">
                            <input type="checkbox" onchange="toggleAddEmpZkt(this)"
                                class="custom-checkbox text-sky-500 focus:ring-sky-500">
                            <span class="text-sm font-bold text-gray-900 dark:text-white">تحديد الكل</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach ($zkts as $zk)
                            <div class="flex items-center gap-2">
                                <label
                                    class="flex items-center gap-2 cursor-pointer w-full p-2 hover:bg-white dark:hover:bg-white/5 rounded-lg transition-colors">
                                    <input type="checkbox" name="ipz[]" value="{{ $zk->ip ?? $zk->sn }}"
                                        class="custom-checkbox text-sky-500 focus:ring-sky-500 ipz-checkbox"
                                        id="zk-{{ $zk->id }}" checked>
                                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $zk->name }}
                                        ({{ $zk->ip ?? $zk->sn }})</span>
                                    <span class="zkt-status-badge mr-auto" data-ip="{{ $zk->ip ?? $zk->sn }}">
                                        <i data-lucide="loader" class="w-3 h-3 text-warning animate-spin"></i>
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- البيانات الأساسية للموظف -->
            <div class="mb-8">
                <h4
                    class="text-sm font-bold text-sky-600 dark:text-sky-500 uppercase tracking-wider border-b border-gray-100 dark:border-white/5 pb-3 mb-4 flex items-center gap-2">
                    <i data-lucide="user" class="w-4 h-4"></i> البيانات الأساسية
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">رقم
                            البصمة</label>
                        <input type="number"
                            class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors num_emp"
                            v-model="num_id" name="num" required>
                    </div>
                    <div class="lg:col-span-3">
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">اسم
                            الموظف</label>
                        <input type="text" name="name" required
                            class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
                    <div>
                        <label
                            class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">العمر</label>
                        <input type="number" name="age"
                            class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>
                    <div>
                        <label
                            class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الموبايل</label>
                        <input type="text" name="mobile"
                            class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>
                    <div>
                        <label
                            class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">القسم</label>
                        <select name="section_id" required
                            class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                            <option value="" disabled selected>اختر القسم</option>
                            @foreach ($sections as $sec)
                                <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">الشيفت</label>
                        <select name="shift_id" required
                            class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                            <option value="" disabled selected>اختر الشيفت</option>
                            @foreach ($shifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- إعدادات النظام المالي والإجازات -->
            <div class="mb-8">
                <h4
                    class="text-sm font-bold text-sky-600 dark:text-sky-500 uppercase tracking-wider border-b border-gray-100 dark:border-white/5 pb-3 mb-4 flex items-center gap-2">
                    <i data-lucide="calendar" class="w-4 h-4"></i> نظام العمل والإجازات
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="lg:col-span-2">
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">أيام
                            الإجازة</label>
                        <div
                            class="bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 px-4 py-3 rounded-xl flex flex-wrap gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" value="لا يوجد" name="free_d[]"
                                    class="custom-checkbox text-sky-500 focus:ring-sky-500">
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">لايوجد</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" value="الجمعة" name="free_d[]" checked
                                    class="custom-checkbox text-sky-500 focus:ring-sky-500">
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">الجمعة</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" value="السبت" name="free_d[]"
                                    class="custom-checkbox text-sky-500 focus:ring-sky-500">
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">السبت</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" value="الأحد" name="free_d[]"
                                    class="custom-checkbox text-sky-500 focus:ring-sky-500">
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">الأحد</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" value="الاثنين" name="free_d[]"
                                    class="custom-checkbox text-sky-500 focus:ring-sky-500">
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">الاثنين</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" value="الثلاثاء" name="free_d[]"
                                    class="custom-checkbox text-sky-500 focus:ring-sky-500">
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">الثلاثاء</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" value="الأربعاء" name="free_d[]"
                                    class="custom-checkbox text-sky-500 focus:ring-sky-500">
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">الأربعاء</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" value="الخميس" name="free_d[]"
                                    class="custom-checkbox text-sky-500 focus:ring-sky-500">
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">الخميس</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">نظام
                            الموظف</label>
                        <select v-model="shahry" name="choosesys" required
                            class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                            <option value="" disabled selected>اختر نظام الموظف</option>
                            <option value="1">شهري ثابت</option>
                            <option value="2">شهري متغير</option>
                            <option value="3">غير شهري</option>
                        </select>
                    </div>

                    <div>
                        <label
                            class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">المرتب</label>
                        <input type="number" name="kabd" required
                            class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">

                    <input type="hidden" v-if="shahry == '1'" value="1" name="sysemp" required>
                    <input type="hidden" v-if="shahry == '2'" value="2" name="sysemp" required>

                    <div v-if="shahry == '3'">
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">تحديد الموظف
                            باليوم</label>
                        <input type="number" name="sysemp" min="0" required
                            class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">يوم الإجازة
                            OT</label>
                        <input type="number" name="friday" value="0" min="0" required
                            class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>

                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">خصم
                            الإجازات</label>
                        <select name="check" required
                            class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                            <option value="" disabled selected>اختر</option>
                            <option value="1">نعم</option>
                            <option value="0">لا</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- صلاحيات البصمة والعنوان -->
            <div class="mb-8">
                <h4
                    class="text-sm font-bold text-sky-600 dark:text-sky-500 uppercase tracking-wider border-b border-gray-100 dark:border-white/5 pb-3 mb-4 flex items-center gap-2">
                    <i data-lucide="shield" class="w-4 h-4"></i> صلاحيات البصمة وتفاصيل إضافية
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">صلاحية
                            الموظف في البصمة</label>
                        <select v-model="inp" name="role_zkt"
                            class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                            <option value="0" selected>موظف</option>
                            <option value="14">مشرف</option>
                        </select>
                    </div>

                    <div v-if="inp == '14'">
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">باسورد
                            للموظف في البصمة</label>
                        <input type="text" name="password" required
                            class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">العنوان</label>
                    <textarea name="title" rows="2"
                        class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors"></textarea>
                </div>
            </div>

            <!-- بيانات الدخول (الحساب برقم التليفون) -->
            <div class="mb-8">
                <h4
                    class="text-sm font-bold text-sky-600 dark:text-sky-500 uppercase tracking-wider border-b border-gray-100 dark:border-white/5 pb-3 mb-4 flex items-center gap-2">
                    <i data-lucide="key" class="w-4 h-4"></i> حساب الدخول للنظام برقم الهاتف (اختياري)
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">باسورد
                            الموبايل</label>
                        <input type="password" name="password_email"
                            class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase tracking-widest font-bold text-gray-500 mb-2">تأكيد باسورد
                            الموبايل</label>
                        <input type="password" name="confirm-password"
                            class="w-full bg-gray-50 dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-3 rounded-xl outline-none focus:border-sky-500/50 text-xs transition-colors">
                    </div>
                </div>
            </div>

            <div class="flex justify-end border-t border-gray-100 dark:border-white/5 pt-6 mt-6">
                <button type="submit"
                    class="px-8 py-3 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-sm font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95 flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    حفظ بيانات الموظف
                </button>
            </div>

        </form>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            // Removed the select[name="section_id"] change event that was overriding the fingerprint number
        });

        function toggleAddEmpZkt(source) {
            let checkboxes = document.querySelectorAll('.ipz-checkbox');
            checkboxes.forEach(cb => cb.checked = source.checked);
        }

        document.addEventListener("DOMContentLoaded", function () {
            let uniqueIps = [];
            let badges = document.querySelectorAll('.zkt-status-badge');

            badges.forEach(badge => {
                let ip = badge.getAttribute('data-ip');
                if (ip && !uniqueIps.includes(ip)) {
                    uniqueIps.push(ip);
                }
            });

            const checkStatusBaseUrl = "{{ route('check_zkt_status', ['ip' => '___IP___']) }}";

            uniqueIps.forEach(ip => {
                const url = checkStatusBaseUrl.replace('___IP___', encodeURIComponent(ip));
                fetch(url)
                    .then(response => {
                        if (!response.ok) throw new Error('HTTP ' + response.status);
                        return response.json();
                    })
                    .then(data => {
                        let matchingBadges = document.querySelectorAll(`.zkt-status-badge[data-ip="${ip}"]`);
                        matchingBadges.forEach(badge => {
                            if (data.status === 'online') {
                                badge.innerHTML = '<i data-lucide="check-circle" class="w-4 h-4 text-green-500" title="متصل بالشبكة"></i>';
                            } else {
                                badge.innerHTML = '<i data-lucide="x-circle" class="w-4 h-4 text-red-500" title="غير متصل"></i>';
                            }
                            if (typeof lucide !== 'undefined') lucide.createIcons();
                        });
                    })
                    .catch(error => {
                        let matchingBadges = document.querySelectorAll(`.zkt-status-badge[data-ip="${ip}"]`);
                        matchingBadges.forEach(badge => {
                            badge.innerHTML = '<i data-lucide="alert-triangle" class="w-4 h-4 text-red-500" title="خطأ"></i>';
                            if (typeof lucide !== 'undefined') lucide.createIcons();
                        });
                    });
            });
        });
    </script>

    <script>
        const { createApp } = Vue;
        createApp({
            data() {
                return {
                    inp: "0",
                    zk: "aa",
                    num_id: "{{ $next_num }}",
                    shahry: "", // Empty so placeholder option is selected
                };
            },
            methods: {
                num() {
                    if (this.zk === "aa") return;

                    fetch('http://127.0.0.1:81/api/get_id', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            id: this.zk
                        })
                    })
                        .then(res => res.json())
                        .then(data => {
                            this.num_id = data.id;
                        })
                        .catch(err => console.error(err));
                }
            }
        }).mount('#app')
    </script>
@endsection