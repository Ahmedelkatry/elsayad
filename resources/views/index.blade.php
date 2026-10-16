@extends('layouts_new.app')
@section('title')
    لوحة التحكم | نظام إدارة الموظفين
@endsection

@section('page-header')
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
                <a href="#" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">نظرة عامة</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">مرحباً، {{ Auth::user()->name }}</h1>
            </div>
        </div>
    </div>
@endsection

@section('content')
{{-- {{ date('Y-m-d h:i:s') }} --}}
    @php
        $today = date('Y-m-d');
        $user_num = Auth::user()->num;
        $zkt = App\Models\zkt::where('numid', $user_num)->where('date', $today)->first();
        $nozkt = App\Models\nozkt::where('numid', $user_num)->where('date', $today)->first();

        $has_in = ($zkt && $zkt->in) || ($nozkt && $nozkt->in);
        $has_out = ($zkt && $zkt->out) || ($nozkt && $nozkt->out);
        $has_break_out = ($zkt && $zkt->break_out) || ($nozkt && $nozkt->break_out);
        $has_break_in = ($zkt && $zkt->break_in) || ($nozkt && $nozkt->break_in);

        // Subscription & plan info (unused in single-tenant mode)
        $isTrial     = false;
        $currentPlan = null;
        $activePlans = collect();
        $subEndDate  = null;
        $daysLeft    = null;
        $subExpired  = false;
    @endphp

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 text-green-600 rounded-xl text-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-600 rounded-xl text-sm font-bold">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-600 rounded-xl text-sm font-bold">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <!--<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">-->
        <!-- تسجيل الدخول -->
    <!--    <div class="stat-card p-6 rounded-[2rem] flex flex-col items-center justify-center text-center gap-4 border border-gray-100 dark:border-white/5 bg-white dark:bg-[#181818] shadow-sm">-->
    <!--        <div class="w-16 h-16 rounded-2xl bg-green-100 dark:bg-green-500/10 flex items-center justify-center text-green-600 dark:text-green-500">-->
    <!--            <i data-lucide="log-in" class="w-8 h-8"></i>-->
    <!--        </div>-->
    <!--        <div>-->
    <!--            <h3 class="text-lg font-black text-gray-900 dark:text-white mb-1">تسجيل الدخول</h3>-->
    <!--            <p class="text-xs text-gray-500">إثبات الحضور لليوم</p>-->
    <!--        </div>-->
    <!--        @if (!$has_in)-->
    <!--            <form id="myForm" action="#" method="GET" onsubmit="saveLocation(event)" class="w-full">-->
    <!--                {{ csrf_field() }}-->
    <!--                <input type="hidden" name="in" id="in" value="{{ date('H:i:s') }}">-->
    <!--                <input type="hidden" name="date" id="date" value="{{ date('Y-m-d') }}">-->
    <!--                <input type="hidden" name="name" id="name" value="{{ Auth::user()->name }}">-->
    <!--                <input type="hidden" name="numid" id="numid" value="{{ Auth::user()->num }}">-->
    <!--                <button id="submitBtn" type="submit" class="w-full py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-green-500/20 transition-all active:scale-95">تسجيل الدخول الآن</button>-->
    <!--            </form>-->
    <!--        @else-->
    <!--            <button disabled class="w-full py-3 bg-gray-200 dark:bg-white/5 text-gray-500 dark:text-gray-400 rounded-xl text-sm font-bold cursor-not-allowed">تم تسجيل الدخول</button>-->
    <!--        @endif-->
    <!--    </div>-->

        <!-- تسجيل الخروج -->
    <!--    <div class="stat-card p-6 rounded-[2rem] flex flex-col items-center justify-center text-center gap-4 border border-gray-100 dark:border-white/5 bg-white dark:bg-[#181818] shadow-sm">-->
    <!--        <div class="w-16 h-16 rounded-2xl bg-red-100 dark:bg-red-500/10 flex items-center justify-center text-red-600 dark:text-red-500">-->
    <!--            <i data-lucide="log-out" class="w-8 h-8"></i>-->
    <!--        </div>-->
    <!--        <div>-->
    <!--            <h3 class="text-lg font-black text-gray-900 dark:text-white mb-1">تسجيل الخروج</h3>-->
    <!--            <p class="text-xs text-gray-500">إثبات الانصراف</p>-->
    <!--        </div>-->
    <!--        @if ($has_in && !$has_out)-->
    <!--            <form id="myFormout" action="#" method="GET" onsubmit="saveLocation_out(event)" class="w-full">-->
    <!--                {{ csrf_field() }}-->
    <!--                <input type="hidden" id="numido" name="numid" value="{{ Auth::user()->num }}">-->
    <!--                <input type="hidden" id="outo" name="out" value="{{ date('H:i:s') }}">-->
    <!--                <input type="hidden" id="dateo" name="date" value="{{ date('Y-m-d') }}">-->
    <!--                <button id="submitBtnout" type="submit" class="w-full py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-red-500/20 transition-all active:scale-95">تسجيل الخروج الآن</button>-->
    <!--            </form>-->
    <!--        @else-->
    <!--            <button disabled class="w-full py-3 bg-gray-200 dark:bg-white/5 text-gray-500 dark:text-gray-400 rounded-xl text-sm font-bold cursor-not-allowed">معطل</button>-->
    <!--        @endif-->
    <!--    </div>-->

        <!-- خروج استراحة -->
    <!--    <div class="stat-card p-6 rounded-[2rem] flex flex-col items-center justify-center text-center gap-4 border border-gray-100 dark:border-white/5 bg-white dark:bg-[#181818] shadow-sm">-->
    <!--        <div class="w-16 h-16 rounded-2xl bg-sky-100 dark:bg-sky-500/10 flex items-center justify-center text-sky-600 dark:text-sky-500">-->
    <!--            <i data-lucide="coffee" class="w-8 h-8"></i>-->
    <!--        </div>-->
    <!--        <div>-->
    <!--            <h3 class="text-lg font-black text-gray-900 dark:text-white mb-1">خروج استراحة</h3>-->
    <!--            <p class="text-xs text-gray-500">بدء الاستراحة</p>-->
    <!--        </div>-->
    <!--        <form id="myFormBreakOut" action="#" method="POST" onsubmit="saveLocation_breakOut(event)" class="w-full">-->
    <!--            {{ csrf_field() }}-->
    <!--            <input type="hidden" id="break_out_time" name="break_out" value="{{ date('H:i:s') }}">-->
    <!--            <input type="hidden" id="break_out_date" name="date" value="{{ date('Y-m-d') }}">-->
    <!--            <input type="hidden" id="break_out_numid" name="numid" value="{{ Auth::user()->num }}">-->
    <!--            <button id="submitBtnBreakOut" type="submit"-->
    <!--                @if ($has_in && !$has_out && !$has_break_out) class="w-full py-3 bg-sky-500 hover:bg-sky-600 text-black rounded-xl text-sm font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95"-->
    <!--                @else disabled class="w-full py-3 bg-gray-200 dark:bg-white/5 text-gray-500 dark:text-gray-400 rounded-xl text-sm font-bold cursor-not-allowed" @endif>-->
    <!--                خروج استراحة-->
    <!--            </button>-->
    <!--        </form>-->
    <!--    </div>-->

        <!-- دخول استراحة -->
    <!--    <div class="stat-card p-6 rounded-[2rem] flex flex-col items-center justify-center text-center gap-4 border border-gray-100 dark:border-white/5 bg-white dark:bg-[#181818] shadow-sm">-->
    <!--        <div class="w-16 h-16 rounded-2xl bg-blue-100 dark:bg-blue-500/10 flex items-center justify-center text-blue-600 dark:text-blue-500">-->
    <!--            <i data-lucide="arrow-down-circle" class="w-8 h-8"></i>-->
    <!--        </div>-->
    <!--        <div>-->
    <!--            <h3 class="text-lg font-black text-gray-900 dark:text-white mb-1">دخول استراحة</h3>-->
    <!--            <p class="text-xs text-gray-500">العودة من الاستراحة</p>-->
    <!--        </div>-->
    <!--        <form id="myFormBreakIn" action="#" method="POST" onsubmit="saveLocation_breakIn(event)" class="w-full">-->
    <!--            {{ csrf_field() }}-->
    <!--            <input type="hidden" id="break_in_numid" name="numid" value="{{ Auth::user()->num }}">-->
    <!--            <input type="hidden" id="break_in_time" name="break_in" value="{{ date('H:i:s') }}">-->
    <!--            <input type="hidden" id="break_in_date" name="date" value="{{ date('Y-m-d') }}">-->
    <!--            <button id="submitBtnBreakIn" type="submit"-->
    <!--                @if ($has_break_out && !$has_break_in && !$has_out) class="w-full py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-500/20 transition-all active:scale-95"-->
    <!--                @else disabled class="w-full py-3 bg-gray-200 dark:bg-white/5 text-gray-500 dark:text-gray-400 rounded-xl text-sm font-bold cursor-not-allowed" @endif>-->
    <!--                دخول استراحة-->
    <!--            </button>-->
    <!--        </form>-->
    <!--    </div>-->
    <!--</div>-->

    <!-- Location Request Modal (Tailwind/Pixel style) -->
    <!--<div id="locationRequestModal" class="pixel-modal fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">-->
    <!--    <div class="modal-backdrop absolute inset-0 bg-black/60 backdrop-blur-sm"></div>-->
    <!--    <div class="modal-content relative bg-white dark:bg-[#181818] w-full max-w-lg rounded-[2rem] shadow-2xl border border-gray-200 dark:border-white/10 transform scale-95 transition-transform duration-300 overflow-hidden flex flex-col">-->
    <!--        <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex justify-between items-center bg-gray-50 dark:bg-transparent transition-colors">-->
    <!--            <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">-->
    <!--                <i data-lucide="map-pin" class="w-5 h-5 text-sky-500"></i> طلب تحديد موقع لحظي-->
    <!--            </h3>-->
    <!--            <button class="close-modal-btn text-gray-400 hover:text-red-500 transition-colors outline-none" onclick="hideLocModal()">-->
    <!--                <i data-lucide="x" class="w-5 h-5"></i>-->
    <!--            </button>-->
    <!--        </div>-->
    <!--        <div class="p-6 space-y-5 text-center">-->
    <!--            <p class="font-bold text-lg text-gray-900 dark:text-white">تطلب الإدارة تحديد موقعك الحالي فوراً للتأكد من تواجدك.</p>-->
    <!--            <div id="locStatus" class="mt-2 text-sm text-gray-500"></div>-->
    <!--        </div>-->
    <!--        <div class="px-6 py-4 border-t border-gray-100 dark:border-white/5 bg-gray-50 dark:bg-transparent flex justify-center gap-3 transition-colors">-->
    <!--            <button onclick="respondToLocationRequest()" id="btnRespondLoc"-->
    <!--                class="w-full px-5 py-3 bg-sky-500 text-white hover:bg-sky-400 rounded-xl text-sm font-bold shadow-lg shadow-sky-500/20 transition-all active:scale-95 flex justify-center items-center gap-2">-->
    <!--                <i data-lucide="send" class="w-4 h-4 text-white"></i> إرسال موقعي الآن-->
    <!--            </button>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->

    <!-- Scripts -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        // Modal functions for Location Request
        function showLocModal() {
            const modal = document.getElementById('locationRequestModal');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.querySelector('.modal-content').classList.remove('scale-95');
        }
        
        function hideLocModal() {
            const modal = document.getElementById('locationRequestModal');
            modal.classList.add('opacity-0', 'pointer-events-none');
            modal.querySelector('.modal-content').classList.add('scale-95');
        }

        var currentRequestId = null;

        function playBeep() {
            try {
                var ctx = new (window.AudioContext || window.webkitAudioContext)();
                var oscillator = ctx.createOscillator();
                var gainNode = ctx.createGain();
                oscillator.connect(gainNode);
                gainNode.connect(ctx.destination);
                oscillator.frequency.value = 880;
                oscillator.type = 'sine';
                gainNode.gain.setValueAtTime(0.8, ctx.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.6);
                oscillator.start(ctx.currentTime);
                oscillator.stop(ctx.currentTime + 0.6);
            } catch(e) {}
        }

        function checkLocationRequests() {
            fetch('{{ route("location_request.check") }}')
                .then(res => res.json())
                .then(response => {
                    if (response.has_request) {
                        if (currentRequestId !== response.request_id) {
                            currentRequestId = response.request_id;
                            playBeep();
                            showLocModal();
                        }
                    } else {
                        currentRequestId = null;
                    }
                }).catch(err => console.log(err));
        }

        setInterval(checkLocationRequests, 5000);
        checkLocationRequests();

        function respondToLocationRequest() {
            const btn = document.getElementById('btnRespondLoc');
            const statusDiv = document.getElementById('locStatus');

            btn.disabled = true;
            statusDiv.innerHTML = "جاري تحديد موقعك...";

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    
                    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : "";

                    fetch('{{ route("location_request.respond") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            request_id: currentRequestId,
                            lat: lat,
                            lon: lon
                        })
                    })
                    .then(res => res.json())
                    .then(response => {
                        statusDiv.innerHTML = "تم إرسال الموقع بنجاح!";
                        setTimeout(() => {
                            hideLocModal();
                            btn.disabled = false;
                            statusDiv.innerHTML = "";
                        }, 2000);
                    })
                    .catch(err => {
                        alert('حدث خطأ في إرسال الموقع');
                        btn.disabled = false;
                        statusDiv.innerHTML = "";
                    });
                }, function(error) {
                    alert('فشل جلب الموقع. يرجى التأكد من تفعيل الـ GPS والسماح للمتصفح بالوصول للموقع.');
                    btn.disabled = false;
                    statusDiv.innerHTML = "";
                }, {
                    enableHighAccuracy: true
                });
            } else {
                alert('المتصفح لا يدعم تحديد الموقع');
                btn.disabled = false;
            }
        }

        function performAttendanceAction(actionType, data, btnId) {
            const btn = document.getElementById(btnId);
            if (btn) {
                btn.disabled = true;
                btn.innerText = "جاري الحفظ...";
            }

            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : "";

            const options = {
                enableHighAccuracy: false,
                timeout: 10000,
                maximumAge: 60000
            };

            const successCallback = (position) => {
                data.latitude = position.coords.latitude;
                data.longitude = position.coords.longitude;

                let route = "";
                switch(actionType) {
                    case 'in': route = "{{ route('zkt.store') }}"; break;
                    case 'out': route = "{{ route('updateout') }}"; break;
                    case 'break_out': route = "{{ route('breakout') }}"; break;
                    case 'break_in': route = "{{ route('breakin') }}"; break;
                }

                fetch(route, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(res => {
                    if (res.success) {
                        alert("تمت العملية بنجاح!");
                        location.reload();
                    } else {
                        alert("خطأ: " + (res.message || "فشل الطلب"));
                        if (btn) btn.disabled = false;
                        if (btn) btn.innerText = "حاول مرة أخرى";
                    }
                })
                .catch(err => {
                    alert("خطأ في الاتصال بالسيرفر");
                    if (btn) btn.disabled = false;
                });
            };

            const errorCallback = (error) => {
                let msg = "فشل تحديد الموقع: ";
                if (error.code == 1) msg += "الإذن مرفوض. يرجى تفعيل الموقع.";
                else if (error.code == 3) msg += "انتهت المهلة. يرجى المحاولة في مكان مفتوح.";
                else msg += "الموقع غير متاح حالياً.";
                
                alert(msg + " (سيتم المحاولة بدون إحداثيات)");
                successCallback({ coords: { latitude: null, longitude: null } });
            };

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(successCallback, errorCallback, options);
            } else {
                alert("المتصفح لا يدعم تحديد الموقع.");
                successCallback({ coords: { latitude: null, longitude: null } });
            }
        }

        function saveLocation(event) {
            if (event) event.preventDefault();
            const data = {
                date: document.getElementById("date").value,
                name: document.getElementById("name").value,
                numid: document.getElementById("numid").value,
                inn: document.getElementById("in").value
            };
            performAttendanceAction('in', data, "submitBtn");
        }

        function saveLocation_out(event) {
            if (event) event.preventDefault();
            const data = {
                date: document.getElementById("dateo").value,
                numid: document.getElementById("numido").value,
                out: document.getElementById("outo").value
            };
            performAttendanceAction('out', data, "submitBtnout");
        }

        function saveLocation_breakOut(event) {
            if (event) event.preventDefault();
            const data = {
                date: document.getElementById("break_out_date").value,
                numid: document.getElementById("break_out_numid").value,
                break_out: document.getElementById("break_out_time").value
            };
            performAttendanceAction('break_out', data, "submitBtnBreakOut");
        }

        function saveLocation_breakIn(event) {
            if (event) event.preventDefault();
            const data = {
                date: document.getElementById("break_in_date").value,
                numid: document.getElementById("break_in_numid").value,
                break_in: document.getElementById("break_in_time").value
            };
            performAttendanceAction('break_in', data, "submitBtnBreakIn");
        }
    </script>
@endsection
