@extends('layouts.master')
@section('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #map {
            height: 600px;
            width: 400%;
        }
    </style>
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الخريطه</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ الخريطه</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row" id="app">





        <h3>موقع دخول وخروج الموظف {{ $date }}</h3>
        <div id="map"></div>


    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const {
            createApp
        } = Vue;

        createApp({
            data() {
                return {
                    lat_in: {{ $lat_in }},
                    lon_in: {{ $lon_in }},
                    lat_out: {{ $lat_out }},
                    lon_out: {{ $lon_out }},
                    id: 1,
                };
            },
            mounted() {
                // تخزين المتغيرات في نطاق Vue
                const lat_in = this.lat_in;
                const lon_in = this.lon_in;
                const lat_out = this.lat_out;
                const lon_out = this.lon_out;


                // إنشاء الخريطة
                var map = L.map('map').setView([lat_in, lon_in], 13);

                // إضافة طبقة OpenStreetMap
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);


                var marker1 = L.marker([lat_in, lon_in]).addTo(map);
                marker1.bindTooltip("الموقع الدخول {{ $in }}", {permanent: true, direction: 'top'});

                // العلامة الثانية
                var marker2 = L.marker([lat_out, lon_out]).addTo(map);
                marker2.bindTooltip("الموقع الخروج {{ $out }} ", {permanent: true, direction: 'top'});


                // إضافة علامة (Marker) على الموقع
                // L.marker([lat_in, lon_in]).addTo(map)
                //     .bindPopup("موقع دخول الموظف").openPopup();


                // var map2 = L.map('map2').setView([lat_out, lat_out], 18);

                // // إضافة طبقة OpenStreetMap
                // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                //     attribution: '© OpenStreetMap contributors'
                // }).addTo(map2);

                // إضافة علامة (Marker) على الموقع
                // L.marker([lat_out, lat_out]).addTo(map)
                //     .bindPopup("موقع خروج الموظف").openPopup();




                // الحصول على الموقع من المتصفح
                // if (navigator.geolocation) {
                //     navigator.geolocation.getCurrentPosition(function(position) {
                //         // استخدام القيم المحفوظة
                //         var lat = lat_in;
                //         var lon = lon_in;

                //         // تحديث مركز الخريطة
                //         map.setView([lat, lon], 16);

                //         // إضافة علامة (Marker) على الموقع
                //         L.marker([lat, lon]).addTo(map)
                //             .bindPopup("موقع الموظف الحالي")
                //             .openPopup();
                //     }, function() {
                //         alert("فشل في الحصول على الموقع");
                //     });
                // } else {
                //     alert("المتصفح لا يدعم تحديد الموقع");
                // }


            },
        }).mount('#app');
    </script>
@endsection
