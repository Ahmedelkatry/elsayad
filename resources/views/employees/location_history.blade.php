@extends('layouts_new.app')
@section('title')
    سجل تتبع الموظف | {{ $employee->name }}
@endsection

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map {
            height: 600px;
            width: 100%;
            border-radius: 1.5rem;
        }
        .pixel-card {
            background-color: #ffffff;
            border: 1px solid #f3f4f6;
        }
        .dark .pixel-card {
            background-color: #181818;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
@endsection

@section('page-header')
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-2">
                <a href="{{ route('dashboard') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">الرئيسية</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <a href="{{ route('check_attendance_location') }}" class="hover:text-sky-600 dark:hover:text-sky-500 transition-colors">المواقع المباشرة</a>
                <i data-lucide="chevron-left" class="w-3 h-3 text-gray-400 dark:text-gray-700"></i>
                <span class="text-sky-600 dark:text-sky-500">سجل التتبع</span>
            </nav>
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-8 bg-blue-500 rounded-full shadow-[0_0_15px_rgba(59,130,246,0.3)]"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">سجل تحركات: {{ $employee->name }}</h1>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <form action="{{ route('employee.location_history') }}" method="GET" class="flex items-center gap-2">
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()"
                    class="bg-white dark:bg-[#111] border border-gray-200 dark:border-white/10 text-gray-900 dark:text-white px-4 py-2 rounded-xl outline-none focus:border-blue-500/50 text-xs transition-colors font-mono">
            </form>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- الخريطة -->
        <div class="lg:col-span-3 pixel-card rounded-[2rem] p-4 shadow-sm">
            <div id="map"></div>
        </div>

        <!-- سجل النقاط -->
        <div class="pixel-card rounded-[2rem] p-5 shadow-sm flex flex-col" style="height: 640px;">
            <h3 class="text-sm font-bold mb-4 flex items-center gap-2">
                <i data-lucide="list" class="w-4 h-4 text-blue-500"></i>
                نقاط التتبع ({{ $locations->count() }})
            </h3>

            <div class="flex-1 overflow-y-auto space-y-3 pr-2">
                @forelse($locations as $loc)
                    <div class="p-3 bg-gray-50 dark:bg-white/5 rounded-xl border border-gray-100 dark:border-white/5 cursor-pointer hover:border-blue-500/50 transition-all"
                        onclick="focusPoint([{{ $loc->latitude }}, {{ $loc->longitude }}], '{{ $loc->recorded_at }}')">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-bold text-blue-500">{{ $loc->recorded_at->format('H:i:s') }}</span>
                            <i data-lucide="map-pin" class="w-3 h-3 text-gray-400"></i>
                        </div>
                        <p class="text-[9px] text-gray-500 dark:text-gray-400 font-mono">
                            {{ round($loc->latitude, 6) }}, {{ round($loc->longitude, 6) }}
                        </p>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <i data-lucide="map-pin-off" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                        <p class="text-xs text-gray-400">لا توجد بيانات تتبع لهذا اليوم</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let map;
        const locations = @json($locations);

        document.addEventListener('DOMContentLoaded', function () {
            map = L.map('map').setView([24.7136, 46.6753], 6);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            if (locations.length > 0) {
                const points = locations.map(loc => [parseFloat(loc.latitude), parseFloat(loc.longitude)]);

                // رسم الخط (المسار)
                const polyline = L.polyline(points, {
                    color: '#3b82f6',
                    weight: 4,
                    opacity: 0.6,
                    smoothFactor: 1
                }).addTo(map);

                // إضافة علامات البداية والنهاية
                L.marker(points[0], { icon: createLabelIcon('البداية', '#22c55e') }).addTo(map);
                L.marker(points[points.length - 1], { icon: createLabelIcon('النهاية', '#ef4444') }).addTo(map);

                // إضافة نقاط صغيرة على المسار
                locations.forEach((loc, i) => {
                    L.circleMarker([loc.latitude, loc.longitude], {
                        radius: 4,
                        fillColor: "#3b82f6",
                        color: "#fff",
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.8
                    }).addTo(map).bindPopup(`توقيت: ${new Date(loc.recorded_at).toLocaleTimeString('ar-EG')}`);
                });

                map.fitBounds(polyline.getBounds(), { padding: [50, 50] });
            }
        });

        function createLabelIcon(label, color) {
            return L.divIcon({
                className: '',
                html: `<div style="background:${color}; color:white; padding:2px 8px; border-radius:10px; font-size:10px; font-weight:bold; white-space:nowrap; border:2px solid white; box-shadow:0 2px 4px rgba(0,0,0,0.2)">${label}</div>`,
                iconAnchor: [20, 10]
            });
        }

        function focusPoint(coords, time) {
            map.flyTo(coords, 17);
            L.popup()
                .setLatLng(coords)
                .setContent(`التوقيت: ${new Date(time).toLocaleTimeString('ar-EG')}`)
                .openOn(map);
        }
    </script>
@endsection
