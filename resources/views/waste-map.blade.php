<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>خريطة النفايات - المغرب الأخضر</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-color: #10b981;
            --primary-dark: #059669;
            --secondary-color: #3b82f6;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --success-color: #10b981;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8fafc;
        }

        /* Modern Navigation */
        .navbar {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            transform: translateY(-2px);
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -8px;
            right: 0;
            width: 100%;
            height: 3px;
            background-color: white;
            border-radius: 3px;
        }

        /* Map Container */
        .map-container {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border: none;
        }

        /* Stats Cards */
        .stat-card {
            transition: all 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            z-index: 1;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
            z-index: -1;
            border-radius: 10px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Custom Icons */
        .custom-div-icon {
            background: transparent;
            border: none;
        }

        /* Popup Styles */
        .leaflet-popup-content {
            text-align: right;
            direction: rtl;
            min-width: 250px;
            margin: 16px !important;
        }

        .leaflet-popup-content h3 {
            color: #1f2937;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .leaflet-popup-content strong {
            color: #4b5563;
            font-weight: 600;
        }

        /* Floating Action Button */
        .fab {
            position: fixed;
            bottom: 2rem;
            left: 2rem;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .fab:hover {
            transform: scale(1.1);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Alert Notifications */
        .alert {
            position: fixed;
            top: 1rem;
            right: 1rem;
            width: 350px;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: flex-start;
            z-index: 1000;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .alert.show {
            opacity: 1;
            transform: translateX(0);
        }

        .alert-success {
            background-color: #ecfdf5;
            border-left: 4px solid var(--success-color);
            color: #065f46;
        }

        .alert-error {
            background-color: #fef2f2;
            border-left: 4px solid var(--danger-color);
            color: #991b1b;
        }

        .alert-warning {
            background-color: #fffbeb;
            border-left: 4px solid var(--warning-color);
            color: #92400e;
        }

        .alert-info {
            background-color: #eff6ff;
            border-left: 4px solid var(--secondary-color);
            color: #1e40af;
        }

        .alert-icon {
            margin-left: 0.75rem;
            font-size: 1.25rem;
        }

        .alert-close {
            margin-right: auto;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .alert-close:hover {
            opacity: 1;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .leaflet-popup-content {
                min-width: 200px;
            }

            .alert {
                width: 90%;
                right: 5%;
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Alert Container -->
    <div id="alertContainer"></div>

    <!-- Navigation -->
    <nav class="navbar text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        {{-- <img src="{{ asset('images/ozen.png') }}" class="h-8 w-8" alt="شعار المغرب الأخضر"> --}}
                        <span class="mr-2 text-lg font-semibold text-white">GM</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="hidden md:block">
                    <ul class="flex space-x-1 space-x-reverse">
                        <li>
                            <a href="{{ route('home') }}"
                                class="nav-link px-4 py-2 {{ request()->routeIs('home') ? 'active' : '' }}">
                                <i class="fas fa-home ml-2"></i>الرئيسية
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('conservationTips.tips') }}"
                                class="nav-link px-4 py-2 {{ request()->routeIs('conservationTips.tips') ? 'active' : '' }}">
                                <i class="fas fa-lightbulb ml-2"></i>نصائح بيئية
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('waste-map') }}"
                                class="nav-link px-4 py-2 {{ request()->routeIs('waste-map') ? 'active' : '' }}">
                                <i class="fas fa-map ml-2"></i>خريطة النفايات
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('bus-times.index') }}"
                                class="nav-link px-4 py-2 {{ request()->routeIs('bus-times.index') ? 'active' : '' }}">
                                <i class="fas fa-bus ml-2"></i>مواعيد الحافلات
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('waste-reports.create') }}"
                                class="nav-link px-4 py-2 {{ request()->routeIs('waste-reports.create') ? 'active' : '' }}">
                                <i class="fas fa-trash ml-2"></i>تبليغ عن نفايات
                            </a>
                        </li>
                        @auth
                            <li>
                                <a href="{{ route('dashboard') }}"
                                    class="nav-link px-4 py-2 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-chart-line ml-2"></i>لوحة التحكم
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('login') }}"
                                    class="nav-link px-4 py-2 {{ request()->routeIs('login') ? 'active' : '' }}">
                                    <i class="fas fa-sign-in-alt ml-2"></i>تسجيل الدخول
                                </a>
                            </li>
                        @endauth
                    </ul>
                </nav>

                <!-- User Links -->
                <div class="hidden sm:flex sm:items-center">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-white hover:text-white/90 text-sm font-medium">
                            لوحة التحكم
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-white/90 text-sm font-medium">
                            تسجيل الدخول
                        </a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="sm:hidden">
                    <button type="button" class="text-white focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Page Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">خريطة النفايات في المغرب</h1>
                        <p class="text-gray-600 mt-1">تصور تفاعلي لمواقع البلاغات والمواقع الإدارية</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('waste-reports.create') }}"
                            class="btn bg-gradient-to-r from-green-500 to-green-600 to-green-600 px-6 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-plus ml-2"></i>إضافة بلاغ جديد
                        </a>
                    </div>
                </div>

                <!-- Map Legend -->
                <div class="bg-white p-4 rounded-xl shadow-sm mb-6">
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center">
                            <div class="w-5 h-5 bg-red-500 rounded-full ml-2"></div>
                            <span class="text-sm font-medium">بلاغات النفايات</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-5 h-5 bg-blue-500 rounded-full ml-2"></div>
                            <span class="text-sm font-medium">المواقع الإدارية</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-5 h-5 bg-yellow-500 rounded-full ml-2"></div>
                            <span class="text-sm font-medium">قيد المعالجة</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-5 h-5 bg-green-500 rounded-full ml-2"></div>
                            <span class="text-sm font-medium">تم التنظيف</span>
                        </div>
                    </div>
                </div>

                <!-- Map Container -->
                <div class="map-container bg-white overflow-hidden shadow-lg sm:rounded-xl mb-6">
                    <div id="map" class="w-full h-[600px]"></div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Waste Reports Stats -->
                    <div class="stat-card bg-white p-6 shadow-sm hover:shadow-md">
                        <h3 class="font-semibold text-lg mb-4 text-gray-800 border-b pb-2 flex items-center">
                            <i class="fas fa-trash-alt text-green-500 ml-2"></i>
                            إحصائيات البلاغات
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">إجمالي البلاغات:</span>
                                <span
                                    class="font-medium bg-gray-100 px-3 py-1 rounded-full">{{ $wasteReports->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">بلاغات جديدة:</span>
                                <span
                                    class="font-medium bg-red-100 text-red-800 px-3 py-1 rounded-full">{{ $wasteReports->where('status', 'new')->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">قيد المعالجة:</span>
                                <span
                                    class="font-medium bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full">{{ $wasteReports->where('status', 'in_progress')->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">تم التنظيف:</span>
                                <span
                                    class="font-medium bg-green-100 text-green-800 px-3 py-1 rounded-full">{{ $wasteReports->where('status', 'cleaned')->count() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Locations Stats -->
                    <div class="stat-card bg-white p-6 shadow-sm hover:shadow-md">
                        <h3 class="font-semibold text-lg mb-4 text-gray-800 border-b pb-2 flex items-center">
                            <i class="fas fa-building text-blue-500 ml-2"></i>
                            المواقع الإدارية
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">إجمالي المواقع:</span>
                                <span
                                    class="font-medium bg-gray-100 px-3 py-1 rounded-full">{{ $locations->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">متوسط البلاغات لكل موقع:</span>
                                <span
                                    class="font-medium bg-blue-100 text-blue-800 px-3 py-1 rounded-full">{{ round($locations->avg(function ($loc) {return $loc->wasteReports->count();}),1) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">أكثر موقع بلاغات:</span>
                                @php
                                    $mostReports = $locations
                                        ->sortByDesc(function ($loc) {
                                            return $loc->wasteReports->count();
                                        })
                                        ->first();
                                @endphp
                                <span class="font-medium bg-red-100 text-red-800 px-3 py-1 rounded-full">
                                    {{ $mostReports ? $mostReports->name : 'N/A' }}
                                    ({{ $mostReports ? $mostReports->wasteReports->count() : 0 }})
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Floating Action Button for Mobile -->
    <a href="{{ route('waste-reports.create') }}" class="fab sm:hidden">
        <i class="fas fa-plus"></i>
    </a>

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
    <script>
        // Initialize the map centered on Guelmim
        // إنشاء الخريطة وتحديد المركز على Bloc D
        var map = L.map('map').setView([28.9878, -10.0569], 16); // إحداثيات حي الوحدة Bloc D

        // طبقة الخريطة
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© خرائط OpenStreetMap'
        }).addTo(map);

        // إضافة رمز للموقع
        var marker = L.marker([28.9878, -10.0569]).addTo(map);
        marker.bindPopup("<b>حي الوحدة Bloc D</b><br>مدينة كلميم، المغرب").openPopup();
        // هنا تضيف باقي عناصر الخريطة مثل العلامات (markers) أو الطبقات (layers)


        // Custom icons with better visual hierarchy
        var wasteIcon = L.divIcon({
            className: 'custom-div-icon',
            html: "<div style='background-color: #EF4444; width: 24px; height: 24px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center;'><i class='fas fa-trash text-white' style='font-size: 10px;'></i></div>",
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        var locationIcon = L.divIcon({
            className: 'custom-div-icon',
            html: "<div style='background-color: #3B82F6; width: 24px; height: 24px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center;'><i class='fas fa-building text-white' style='font-size: 10px;'></i></div>",
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        var inProgressIcon = L.divIcon({
            className: 'custom-div-icon',
            html: "<div style='background-color: #F59E0B; width: 24px; height: 24px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center;'><i class='fas fa-clock text-white' style='font-size: 10px;'></i></div>",
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        var cleanedIcon = L.divIcon({
            className: 'custom-div-icon',
            html: "<div style='background-color: #10B981; width: 24px; height: 24px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center;'><i class='fas fa-check text-white' style='font-size: 10px;'></i></div>",
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        // Cluster groups for different marker types
        var wasteMarkers = L.markerClusterGroup({
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false,
            zoomToBoundsOnClick: true,
            maxClusterRadius: 40
        });

        var locationMarkers = L.markerClusterGroup({
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false,
            zoomToBoundsOnClick: true,
            maxClusterRadius: 40
        });

        // Function to show alert
        function showAlert(type, message) {
            const alertContainer = document.getElementById('alertContainer');
            const alertId = 'alert-' + Date.now();
            let icon, colorClass;

            switch (type) {
                case 'success':
                    icon = 'fa-check-circle';
                    colorClass = 'alert-success';
                    break;
                case 'error':
                    icon = 'fa-exclamation-circle';
                    colorClass = 'alert-error';
                    break;
                case 'warning':
                    icon = 'fa-exclamation-triangle';
                    colorClass = 'alert-warning';
                    break;
                case 'info':
                    icon = 'fa-info-circle';
                    colorClass = 'alert-info';
                    break;
                default:
                    icon = 'fa-info-circle';
                    colorClass = 'alert-info';
            }

            const alertElement = document.createElement('div');
            alertElement.id = alertId;
            alertElement.className = `alert ${colorClass}`;
            alertElement.innerHTML = `
                <div class="alert-icon">
                    <i class="fas ${icon}"></i>
                </div>
                <div class="flex-1">
                    <div class="font-medium">${message}</div>
                </div>
                <div class="alert-close" onclick="closeAlert('${alertId}')">
                    <i class="fas fa-times"></i>
                </div>
            `;

            alertContainer.appendChild(alertElement);

            // Trigger animation
            setTimeout(() => {
                alertElement.classList.add('show');
            }, 10);

            // Auto-close after 5 seconds
            setTimeout(() => {
                closeAlert(alertId);
            }, 5000);
        }

        function closeAlert(id) {
            const alert = document.getElementById(id);
            if (alert) {
                alert.classList.remove('show');
                setTimeout(() => {
                    alert.remove();
                }, 500);
            }
        }

        // Add markers for waste reports
        @foreach ($wasteReports as $report)
            @if ($report->location && $report->wasteType && $report->reporter)
                let icon;
                let statusText = '';
                let statusClass = '';

                @if ($report->status == 'new')
                    icon = wasteIcon;
                    statusText = 'جديد';
                    statusClass = 'bg-red-100 text-red-800';
                @elseif ($report->status == 'in_progress')
                    icon = inProgressIcon;
                    statusText = 'قيد المعالجة';
                    statusClass = 'bg-yellow-100 text-yellow-800';
                @elseif ($report->status == 'cleaned')
                    icon = cleanedIcon;
                    statusText = 'تم التنظيف';
                    statusClass = 'bg-green-100 text-green-800';
                @endif

                var marker = L.marker(
                    [{{ $report->location->latitude }}, {{ $report->location->longitude }}], {
                        icon: icon
                    }
                );

                marker.bindPopup(`
                    <div class="popup-content">
                        <h3 class="font-bold mb-2 text-lg">تفاصيل البلاغ</h3>
                        <div class="mb-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium ${statusClass}">${statusText}</span>
                        </div>
                        <div class="space-y-2">
                            <div><strong>الموقع:</strong> {{ $report->location->name }}</div>
                            <div><strong>نوع النفايات:</strong> {{ $report->wasteType->name }}</div>
                            <div><strong>الكمية:</strong> {{ $report->quantity }} {{ $report->unit }}</div>
                            @if ($report->description)
                                <div><strong>وصف إضافي:</strong> {{ $report->description }}</div>
                            @endif
                            <div><strong>المبلغ:</strong> {{ $report->reporter->name }}</div>
                            <div><strong>تاريخ البلاغ:</strong> {{ $report->created_at->format('Y-m-d') }}</div>
                        </div>
                        <div class="mt-3 text-left">
<a href="{{ route('waste-reports.show', $report->id) }}" class="text-sm bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded inline-block">
                                <i class="fas fa-eye ml-1"></i> عرض التفاصيل
                            </a>
                        </div>
                    </div>
                `);

                marker.on('click', function() {
                    showAlert('info', 'تم تحديد بلاغ في {{ $report->location->name }}');
                });

                wasteMarkers.addLayer(marker);
            @endif
        @endforeach

        // Add markers for admin locations
        @foreach ($locations as $location)
            var marker = L.marker(
                [{{ $location->latitude }}, {{ $location->longitude }}], {
                    icon: locationIcon
                }
            );

            var reportCount = {{ $location->wasteReports->count() }};
            var countClass = reportCount > 0 ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800';

            marker.bindPopup(`
                <div class="popup-content">
                    <h3 class="font-bold mb-2 text-lg">الموقع الإداري</h3>
                    <div class="space-y-2">
                        <div><strong>الاسم:</strong> {{ $location->name }}</div>
                        <div><strong>العنوان:</strong> {{ $location->address }}</div>
                        <div><strong>عدد البلاغات:</strong> <span class="px-2 py-1 rounded-full text-xs font-medium ${countClass}">${reportCount}</span></div>
                        <div><strong>الإحداثيات:</strong> ({{ $location->latitude }}, {{ $location->longitude }})</div>
                    </div>
                    @if ($location->wasteReports->count() > 0)
                        <div class="mt-3 text-left">
                            <a href="{{ route('location.reports', $location->id) }}" class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded inline-block">
                                <i class="fas fa-list ml-1"></i> عرض البلاغات
                            </a>
                        </div>
                    @endif
                </div>
            `);

            marker.on('click', function() {
                showAlert('info', 'تم تحديد الموقع الإداري {{ $location->name }}');
            });

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

        // Fit bounds to show all markers
        function fitMapToMarkers() {
            if (wasteMarkers.getLayers().length > 0 || locationMarkers.getLayers().length > 0) {
                const bounds = L.latLngBounds(
                    wasteMarkers.getBounds().isValid() ? wasteMarkers.getBounds() : null,
                    locationMarkers.getBounds().isValid() ? locationMarkers.getBounds() : null
                );
                map.fitBounds(bounds, {
                    padding: [50, 50]
                });
            }
        }

        // Initial fit to markers
        fitMapToMarkers();

        // Example of showing a welcome alert
        setTimeout(() => {
            showAlert('success', 'مرحباً بك في خريطة النفايات التفاعلية!');
        }, 1000);
    </script>

</body>

</html>
