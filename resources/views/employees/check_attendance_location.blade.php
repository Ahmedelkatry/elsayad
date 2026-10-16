@extends('layouts_new.app')
@section('title')
    مواقع الموظفين المباشرة | نظام إدارة الموظفين
@endsection

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map {
            height: 620px;
            width: 100%;
            border-radius: 1.5rem;
            z-index: 1;
        }
        .pixel-card {
            background-color: #ffffff;
            border: 1px solid #f3f4f6;
        }
        .dark .pixel-card {
            background-color: #181818;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* مؤشر LIVE النابض */
        .live-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.4);
            color: #22c55e;
            font-size: 10px;
            font-weight: 800;
            padding: 3px 8px;
            border-radius: 100px;
            letter-spacing: 1px;
        }
        .live-dot {
            width: 7px;
            height: 7px;
            background: #22c55e;
            border-radius: 50%;
            animation: pulse-green 1.2s infinite;
        }
        @keyframes pulse-green {
            0%, 100% { box-shadow: 0 0 0 0 rgba(34,197,94,0.7); }
            50% { box-shadow: 0 0 0 6px rgba(34,197,94,0); }
        }

        /* حالة الانقطاع - LIVE أحمر */
        .live-badge--offline {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #ef4444;
        }
        .live-dot--offline {
            background: #ef4444;
            animation: pulse-red 1.5s infinite;
        }
        @keyframes pulse-red {
            0%, 100% { box-shadow: 0 0 0 0 rgba(239,68,68,0.8); }
            50% { box-shadow: 0 0 0 6px rgba(239,68,68,0); }
        }
        @keyframes pulse-orange {
            0%, 100% { box-shadow: 0 0 0 0 rgba(249,115,22,0.7); }
            50% { box-shadow: 0 0 0 6px rgba(249,115,22,0); }
        }

        /* أيقونات الخريطة الملونة */
        .marker-in-icon { background: #3b82f6; }
        .marker-out-icon { background: #ef4444; }
        .marker-live-icon { background: #22c55e; animation: pulse-green 1.2s infinite; }

        .custom-marker {
            width: 32px; height: 32px;
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            border: 3px solid white;
            box-shadow: 0 3px 12px rgba(0,0,0,0.3);
        }
        .custom-marker-inner {
            width: 100%; height: 100%;
            border-radius: 50% 50% 50% 0;
            display: flex; align-items: center; justify-content: center;
        }

        /* بطاقة الموظف */
        .emp-card {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .emp-card:hover {
            transform: translateX(-3px);
            border-color: rgba(250, 204, 21, 0.4) !important;
        }
        .emp-card.active-card {
            border-color: rgba(250, 204, 21, 0.7) !important;
            background: rgba(250, 204, 21, 0.05) !important;
        }

        /* شريط الإحصاء */
        .stat-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 700;
        }
        .stat-dot {
            width: 9px; height: 9px; border-radius: 50%;
            flex-shrink: 0;
        }
    </style>
@endsection

@section('page-header')
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">المواقع المباشرة</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-sky-500 rounded-full shadow-[0_0_15px_rgba(56,189,248,0.4)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">تتبع مواقع الموظفين</h1>
                <span class="live-badge"><span class="live-dot"></span>LIVE</span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <!-- إحصائيات سريعة -->
            <div class="flex items-center gap-4 px-4 py-2 pixel-card rounded-xl text-xs">
                <div class="stat-item">
                    <span class="stat-dot bg-blue-500"></span>
                    <span class="text-gray-500 dark:text-gray-400">بصمة دخول</span>
                </div>
                <div class="stat-item">
                    <span class="stat-dot bg-red-500"></span>
                    <span class="text-gray-500 dark:text-gray-400">بصمة خروج</span>
                </div>
                <div class="stat-item">
                    <span class="stat-dot bg-green-500"></span>
                    <span class="text-gray-500 dark:text-gray-400">موقع مباشر</span>
                </div>
            </div>
            <!-- عداد التحديث -->
            <div class="px-4 py-2 pixel-card rounded-xl text-xs flex items-center gap-2">
                <div class="live-dot"></div>
                <span class="text-gray-500 dark:text-gray-400">تحديث كل <span id="countdown" class="font-bold text-green-500">10</span>ث</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        <!-- الخريطة -->
        <div class="lg:col-span-3 pixel-card rounded-[2rem] p-4 shadow-sm">
            <!-- شريط الحالة -->
            <div class="flex items-center justify-between mb-3 px-2">
                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-sky-500"></i>
                    <span>آخر تحديث: <span id="last-update-time" class="font-bold text-gray-700 dark:text-gray-300">---</span></span>
                </div>
                <div class="flex items-center gap-2">
                    <span id="total-count" class="text-xs font-bold text-gray-500 dark:text-gray-400"></span>
                    <button onclick="refreshNow()" class="px-3 py-1.5 bg-sky-500/10 hover:bg-sky-500/20 text-sky-600 dark:text-sky-400 rounded-lg text-xs font-bold transition-all flex items-center gap-1.5">
                        <i data-lucide="refresh-cw" class="w-3 h-3" id="refresh-icon"></i>
                        تحديث الآن
                    </button>
                </div>
            </div>
            <div id="map"></div>
        </div>

        <!-- قائمة الموظفين -->
        <div class="pixel-card rounded-[2rem] p-5 shadow-sm flex flex-col" style="height: 680px;">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold flex items-center gap-2">
                    <i data-lucide="users" class="w-4 h-4 text-sky-500"></i>
                    الموظفون اليوم
                </h3>
                <span id="emp-count-badge" class="text-[10px] bg-sky-500/10 text-sky-600 dark:text-sky-400 font-bold px-2 py-0.5 rounded-full">0</span>
            </div>

            <!-- بحث -->
            <div class="relative mb-3">
                <i data-lucide="search" class="absolute right-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400"></i>
                <input type="text" id="emp-search" placeholder="بحث عن موظف..."
                    class="w-full pr-9 pl-3 py-2 text-xs bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl focus:outline-none focus:border-sky-500/50 transition-colors"
                    oninput="filterEmployees(this.value)">
            </div>

            <div class="flex-1 overflow-y-auto space-y-2 pr-0.5" id="employees-list">
                <div class="text-center py-12">
                    <div class="live-dot mx-auto mb-3" style="width:16px;height:16px;"></div>
                    <p class="text-xs text-gray-400">جاري تحميل البيانات...</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // ========================================================
        // إعدادات عامة
        // ========================================================
        const LIVE_API_URL = "{{ route('location_request.live') }}";
        const REFRESH_INTERVAL = 10; // ثوانٍ
        let map, allEmployeeData = [], allMarkers = [], activeCardIndex = null;
        let countdownVal = REFRESH_INTERVAL;
        let searchQuery = '';

        // ========================================================
        // تهيئة الخريطة
        // ========================================================
        document.addEventListener('DOMContentLoaded', function () {
            map = L.map('map').setView([24.7136, 46.6753], 6);

            // طبقة الخريطة
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // جلب البيانات أول مرة
            fetchLiveData();

            // Polling كل 10 ثوانٍ
            setInterval(fetchLiveData, REFRESH_INTERVAL * 1000);

            // عداد تنازلي
            setInterval(function () {
                countdownVal--;
                if (countdownVal < 0) countdownVal = REFRESH_INTERVAL;
                const el = document.getElementById('countdown');
                if (el) el.textContent = countdownVal;
            }, 1000);
        });

        // ========================================================
        // جلب البيانات من API
        // ========================================================
        async function fetchLiveData() {
            try {
                const res = await fetch(LIVE_API_URL, {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                if (!res.ok) throw new Error('HTTP error ' + res.status);
                const data = await res.json();
                allEmployeeData = data;
                countdownVal = REFRESH_INTERVAL;
                updateMap(data);
                renderEmployeeList(data);
                updateStats(data);
                document.getElementById('last-update-time').textContent = new Date().toLocaleTimeString('ar-EG');
            } catch (err) {
                console.error('Live location fetch error:', err);
            }
        }

        // ========================================================
        // رسم العلامات على الخريطة
        // ========================================================
        function updateMap(data) {
            // إزالة العلامات القديمة
            allMarkers.forEach(m => map.removeLayer(m.marker));
            allMarkers = [];

            const bounds = [];

            data.forEach((emp, idx) => {
                // علامة بصمة الدخول (زرقاء)
                if (emp.lat_in && emp.lon_in) {
                    const ll = [parseFloat(emp.lat_in), parseFloat(emp.lon_in)];
                    bounds.push(ll);
                    const m = L.marker(ll, { icon: createIcon('#3b82f6', '↓') })
                        .addTo(map)
                        .bindPopup(buildPopup(emp, 'in'));
                    allMarkers.push({ idx, type: 'in', marker: m, ll });
                }

                // علامة بصمة الخروج (حمراء)
                if (emp.lat_out && emp.lon_out) {
                    const ll = [parseFloat(emp.lat_out), parseFloat(emp.lon_out)];
                    bounds.push(ll);
                    const m = L.marker(ll, { icon: createIcon('#ef4444', '↑') })
                        .addTo(map)
                        .bindPopup(buildPopup(emp, 'out'));
                    allMarkers.push({ idx, type: 'out', marker: m, ll });
                }

                // علامة الموقع المباشر — أخضر إذا متصل، برتقالي إذا انقطع
                if (emp.live_lat && emp.live_lon) {
                    const ll = [parseFloat(emp.live_lat), parseFloat(emp.live_lon)];
                    bounds.push(ll);
                    const online = isLiveOnline(emp);
                    const liveColor = online ? '#22c55e' : '#f97316'; // برتقالي = منقطع
                    const m = L.marker(ll, { icon: createIcon(liveColor, '●', true, !online) })
                        .addTo(map)
                        .bindPopup(buildPopup(emp, 'live'));
                    allMarkers.push({ idx, type: 'live', marker: m, ll });
                }
            });

            // ضبط نطاق الرؤية
            if (bounds.length > 0 && !map._loaded) {
                map.fitBounds(bounds, { padding: [40, 40] });
            } else if (bounds.length > 0 && !activeCardIndex) {
                // إعادة ضبط النطاق فقط إذا لم يكن المستخدم ينظر لموظف محدد
            }
        }

        // ========================================================
        // إنشاء أيقونة مخصصة
        // ========================================================
        // هل الموظف متصل؟ (آخر تحديث أقل من دقيقتين)
        function isLiveOnline(emp) {
            return emp && !!emp.is_online;
        }

        function createIcon(color, label, animate = false, useOrangePulse = false) {
            let pulse = '';
            if (animate && useOrangePulse) pulse = 'animation: pulse-orange 1.5s infinite;';
            else if (animate) pulse = 'animation: pulse-green 1.2s infinite;';
            return L.divIcon({
                className: '',
                html: `<div style="
                    width:36px;height:36px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);
                    background:${color};border:3px solid white;
                    box-shadow:0 3px 12px rgba(0,0,0,0.3);${pulse}
                    display:flex;align-items:center;justify-content:center;">
                    <span style="transform:rotate(45deg);color:white;font-size:13px;font-weight:900">${label}</span>
                </div>`,
                iconSize: [36, 36],
                iconAnchor: [18, 36],
                popupAnchor: [0, -36]
            });
        }

        // ========================================================
        // محتوى Popup
        // ========================================================
        function buildPopup(emp, type) {
            const online = type === 'live' ? isLiveOnline(emp) : true;
            const liveIcon  = online ? '🟢' : '🟠';
            const liveLabel = online ? 'موقع مباشر' : 'انقطع الاتصال';
            const typeLabel = { in: '🔵 بصمة دخول', out: '🔴 بصمة خروج', live: `${liveIcon} ${liveLabel}` };
            const timeField = type === 'in' ? emp.in_time : type === 'out' ? emp.out_time : emp.live_time;
            const offlineNote = (type === 'live' && !online)
                ? `<br><span style="font-size:10px;color:#f97316">⚠ آخر ظهور: ${timeField ?? '-'}</span>`
                : '';
            return `<div style="font-family:sans-serif;direction:rtl;min-width:160px">
                <b style="font-size:13px">${emp.name}</b><br>
                <span style="font-size:11px;color:#666">${typeLabel[type] ?? ''}</span><br>
                ${(timeField && !offlineNote) ? `<span style="font-size:11px">⏱ ${timeField}</span>` : ''}
                ${offlineNote}
            </div>`;
        }

        // ========================================================
        // قائمة الموظفين
        // ========================================================
        function renderEmployeeList(data) {
            const list = document.getElementById('employees-list');
            const badge = document.getElementById('emp-count-badge');
            badge.textContent = data.length;

            const filtered = data.filter(e =>
                !searchQuery || e.name.includes(searchQuery)
            );

            if (filtered.length === 0) {
                list.innerHTML = `<p class="text-xs text-gray-400 text-center py-8">لا توجد بيانات مواقع اليوم.</p>`;
                return;
            }

            list.innerHTML = filtered.map((emp, i) => {
                const hasLive    = emp.live_lat && emp.live_lon;
                const hasIn      = emp.lat_in && emp.lon_in;
                const hasOut     = emp.lat_out && emp.lon_out;
                const realIdx    = data.indexOf(emp);

                // هل الموظف لا يزال متصلاً؟
                const online = hasLive && isLiveOnline(emp);
                const offlineClass = hasLive && !online ? 'live-badge--offline' : '';
                const dotClass     = hasLive && !online ? 'live-dot--offline' : '';
                const liveLabel    = hasLive ? (online ? 'LIVE' : 'LIVE') : '';
                // وقت عرض الحالة
                const liveTimeColor  = online ? 'text-green-500 bg-green-500/10' : 'text-orange-400 bg-orange-500/10';
                const liveTimePrefix = online ? 'مباشر' : 'آخر ظهور';

                return `<div class="emp-card p-3 bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-100 dark:border-white/5"
                         id="emp-card-${realIdx}"
                         onclick="focusEmployee(${realIdx})">
                    <div class="flex justify-between items-start mb-1.5">
                        <span class="text-xs font-bold text-gray-900 dark:text-white">${emp.name}</span>
                        <div class="flex gap-1">
                            ${hasLive ? `<span class="live-badge ${offlineClass}" style="font-size:8px;padding:2px 5px"><span class="live-dot ${dotClass}" style="width:5px;height:5px"></span>${liveLabel}</span>` : ''}
                            <a href="{{ route('employee.location_history') }}?employee_id=${emp.employee_id}" class="p-1 hover:bg-blue-500/10 text-blue-500 rounded transition-colors" title="سجل التحركات" onclick="event.stopPropagation()">
                                <i data-lucide="history" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    </div>
                    <div class="flex gap-2 flex-wrap mt-1">
                        ${hasIn  ? `<span class="text-[9px] px-1.5 py-0.5 bg-blue-500/10 text-blue-500 font-bold rounded-full">دخول ${emp.in_time ?? ''}</span>` : ''}
                        ${hasOut ? `<span class="text-[9px] px-1.5 py-0.5 bg-red-500/10 text-red-500 font-bold rounded-full">خروج ${emp.out_time ?? ''}</span>` : ''}
                        ${hasLive? `<span class="text-[9px] px-1.5 py-0.5 ${liveTimeColor} font-bold rounded-full">${liveTimePrefix} ${emp.live_time ?? ''}</span>` : ''}
                    </div>
                </div>`;
            }).join('');
        }

        // ========================================================
        // التركيز على موظف في الخريطة
        // ========================================================
        function focusEmployee(idx) {
            // إزالة التحديد السابق
            if (activeCardIndex !== null) {
                const prev = document.getElementById(`emp-card-${activeCardIndex}`);
                if (prev) prev.classList.remove('active-card');
            }
            activeCardIndex = idx;
            const card = document.getElementById(`emp-card-${idx}`);
            if (card) card.classList.add('active-card');

            // الأفضلية: موقع مباشر > بصمة دخول > بصمة خروج
            const empMarkers = allMarkers.filter(m => m.idx === idx);
            const live   = empMarkers.find(m => m.type === 'live');
            const inMark = empMarkers.find(m => m.type === 'in');
            const target = live || inMark || empMarkers[0];

            if (target) {
                map.flyTo(target.ll, 16, { animate: true, duration: 1 });
                target.marker.openPopup();
            }
        }

        // ========================================================
        // تحديث الإحصاء
        // ========================================================
        function updateStats(data) {
            const total = document.getElementById('total-count');
            const live  = data.filter(e => e.live_lat && e.live_lon).length;
            const att   = data.filter(e => e.lat_in && e.lon_in).length;
            total.textContent = `${att} حضور · ${live} مباشر`;
        }

        // ========================================================
        // بحث
        // ========================================================
        function filterEmployees(q) {
            searchQuery = q.trim();
            renderEmployeeList(allEmployeeData);
        }

        // ========================================================
        // تحديث يدوي
        // ========================================================
        function refreshNow() {
            const icon = document.getElementById('refresh-icon');
            icon.style.animation = 'spin 0.8s linear';
            setTimeout(() => icon.style.animation = '', 800);
            fetchLiveData();
        }
    </script>
    <style>
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
@endsection
