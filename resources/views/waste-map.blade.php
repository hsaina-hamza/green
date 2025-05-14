<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>خريطة النفايات - المغرب الأخضر</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
        }
        .custom-div-icon {
            background: transparent;
            border: none;
        }
        .leaflet-popup-content {
            text-align: right;
            direction: rtl;
        }
        .leaflet-popup-content h3 {
            color: #1f2937;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 0.5rem;
            margin-bottom: 0.5rem;
        }
        .leaflet-popup-content strong {
            color: #4b5563;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('images/report-waste.svg') }}" class="h-8 w-8" alt="شعار المغرب الأخضر">
                            </a>
                            <span class="mr-2 text-lg font-semibold text-gray-800">المغرب الأخضر</span>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:mr-10 sm:flex">
                            <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-b-2 hover:border-green-500">
                                الرئيسية
                            </a>
                            <a href="{{ route('waste-map') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-green-600 text-sm font-medium text-gray-900">
                                خريطة النفايات
                            </a>
                        </div>
                    </div>
                    
                    <!-- User Links -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 hover:text-gray-900">
                                لوحة التحكم
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900">
                                تسجيل الدخول
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            <div class="py-8">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
                        <div class="p-6">
                            <h1 class="text-2xl font-bold text-gray-800 mb-6">خريطة النفايات في المغرب</h1>
                            
                            <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center space-x-6 space-x-reverse">
                                    <div class="flex items-center">
                                        <div class="w-5 h-5 bg-red-500 rounded-full ml-2"></div>
                                        <span>بلاغات النفايات</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-5 h-5 bg-blue-500 rounded-full ml-2"></div>
                                        <span>المواقع الإدارية</span>
                                    </div>
                                    <div class="flex-1 text-left">
                                        <a href="{{ route('waste-reports.create') }}" class="text-sm bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                                            <i class="fas fa-plus ml-2"></i>إضافة بلاغ جديد
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="rounded-xl border border-gray-200 overflow-hidden shadow">
                                <!-- Map container -->
                                <div id="map" class="w-full h-[600px]"></div>
                            </div>
                            
                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="font-semibold text-lg mb-3 text-gray-800 border-b pb-2">إحصائيات البلاغات</h3>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">إجمالي البلاغات:</span>
                                            <span class="font-medium">{{ $wasteReports->count() }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">بلاغات جديدة:</span>
                                            <span class="font-medium">{{ $wasteReports->where('status', 'new')->count() }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">قيد المعالجة:</span>
                                            <span class="font-medium">{{ $wasteReports->where('status', 'in_progress')->count() }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">تم التنظيف:</span>
                                            <span class="font-medium">{{ $wasteReports->where('status', 'cleaned')->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="font-semibold text-lg mb-3 text-gray-800 border-b pb-2">المواقع الإدارية</h3>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">إجمالي المواقع:</span>
                                            <span class="font-medium">{{ $locations->count() }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">متوسط البلاغات لكل موقع:</span>
                                            <span class="font-medium">{{ round($locations->avg(function($loc) { return $loc->wasteReports->count(); }), 1) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Initialize the map
        var map = L.map('map').setView([31.7917, -7.0926], 6); // Centered on Morocco

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Custom icons for different marker types
        var wasteIcon = L.divIcon({
            className: 'custom-div-icon',
            html: "<div style='background-color: #EF4444; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white;'></div>",
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });

        var locationIcon = L.divIcon({
            className: 'custom-div-icon',
            html: "<div style='background-color: #3B82F6; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white;'></div>",
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });

        // Cluster group for waste reports
        var wasteMarkers = L.markerClusterGroup({
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false,
            zoomToBoundsOnClick: true
        });

        // Cluster group for admin locations
        var locationMarkers = L.markerClusterGroup({
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false,
            zoomToBoundsOnClick: true
        });

        // Add markers for waste reports
        @foreach($wasteReports as $report)
            @if($report->location && $report->wasteType && $report->reporter)
                var marker = L.marker(
                    [{{ $report->location->latitude }}, {{ $report->location->longitude }}],
                    {icon: wasteIcon}
                );
                
                var statusText = '';
                var statusClass = '';
                @if($report->status == 'new')
                    statusText = 'جديد';
                    statusClass = 'bg-red-100 text-red-800';
                @elseif($report->status == 'in_progress')
                    statusText = 'قيد المعالجة';
                    statusClass = 'bg-yellow-100 text-yellow-800';
                @elseif($report->status == 'cleaned')
                    statusText = 'تم التنظيف';
                    statusClass = 'bg-green-100 text-green-800';
                @endif
                
                marker.bindPopup(`
                    <div class="popup-content">
                        <h3 class="font-bold mb-2 text-lg">تفاصيل البلاغ</h3>
                        <div class="mb-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium ${statusClass}">${statusText}</span>
                        </div>
                        <div class="space-y-1">
                            <div><strong>الموقع:</strong> {{ $report->location->name }}</div>
                            <div><strong>نوع النفايات:</strong> {{ $report->wasteType->name }}</div>
                            <div><strong>الكمية:</strong> {{ $report->quantity }} {{ $report->unit }}</div>
                            @if($report->description)
                                <div><strong>وصف إضافي:</strong> {{ $report->description }}</div>
                            @endif
                            <div><strong>المبلغ:</strong> {{ $report->reporter->name }}</div>
                            <div><strong>تاريخ البلاغ:</strong> {{ $report->created_at->format('Y-m-d') }}</div>
                        </div>
                    </div>
                `);
                wasteMarkers.addLayer(marker);
            @endif
        @endforeach

        // Add markers for admin locations
        @foreach($locations as $location)
            var marker = L.marker(
                [{{ $location->latitude }}, {{ $location->longitude }}],
                {icon: locationIcon}
            );
            
            var reportCount = {{ $location->wasteReports->count() }};
            var countClass = reportCount > 0 ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800';
            
            marker.bindPopup(`
                <div class="popup-content">
                    <h3 class="font-bold mb-2 text-lg">الموقع الإداري</h3>
                    <div class="space-y-1">
                        <div><strong>الاسم:</strong> {{ $location->name }}</div>
                        <div><strong>العنوان:</strong> {{ $location->address }}</div>
                        <div><strong>عدد البلاغات:</strong> <span class="px-2 py-1 rounded-full text-xs font-medium ${countClass}">${reportCount}</span></div>
                        <div><strong>الإحداثيات:</strong> ({{ $location->latitude }}, {{ $location->longitude }})</div>
                    </div>
                </div>
            `);
            locationMarkers.addLayer(marker);
        @endforeach

        // Add clusters to map
        map.addLayer(wasteMarkers);
        map.addLayer(locationMarkers);

        // Add layer control
        var overlayMaps = {
            "بلاغات النفايات": wasteMarkers,
            "المواقع الإدارية": locationMarkers
        };
        
        L.control.layers(null, overlayMaps, {
            position: 'topright',
            collapsed: false
        }).addTo(map);
    </script>
</body>
</html>