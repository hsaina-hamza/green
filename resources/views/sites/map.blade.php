<x-app-layout dir="rtl">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="fas fa-map-marked-alt ml-2"></i> {{ __('خريطة مواقع النفايات') }}
            </h2>
            <a href="{{ route('admin.sites.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300 flex items-center">
                <i class="fas fa-list mr-2"></i> {{ __('العودة للقائمة') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Map Card with Enhanced Design -->
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-100">
                <div class="p-6">
                    <!-- Map Filter Controls -->
                    <div class="flex flex-wrap justify-between items-center mb-4 gap-4">
                        <div class="flex items-center space-x-4 space-x-reverse">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-red-500 rounded-full mr-2"></div>
                                <span class="text-sm font-medium">حالات طارئة</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-yellow-500 rounded-full mr-2"></div>
                                <span class="text-sm font-medium">قيد المعالجة</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-green-500 rounded-full mr-2"></div>
                                <span class="text-sm font-medium">تم التنظيف</span>
                            </div>
                        </div>
                        
                        <div class="relative">
                            <select id="filter-status" class="block w-full rounded-lg border-gray-300 text-sm focus:border-green-500 focus:ring-green-500">
                                <option value="all">جميع الحالات</option>
                                <option value="emergency">حالات طارئة</option>
                                <option value="processing">قيد المعالجة</option>
                                <option value="cleaned">تم التنظيف</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Map Container -->
                    <div id="map" class="h-[600px] w-full rounded-lg border border-gray-200 shadow-sm"></div>
                </div>
            </div>
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">إجمالي المواقع</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $totalSites }}</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="fas fa-map-marker-alt text-green-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">بلاغات اليوم</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $todayReports }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="fas fa-exclamation-triangle text-blue-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">عمليات التنظيف النشطة</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $activeSchedules }}</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-full">
                            <i class="fas fa-truck text-purple-600"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/MarkerCluster.Default.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
    <style>
        /* RTL Map Styling */
        .leaflet-popup-content {
            text-align: right;
            direction: rtl;
            min-width: 250px;
        }
        .leaflet-popup-content-wrapper {
            border-radius: 8px;
        }
        .info-panel {
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.4);
            text-align: right;
            direction: rtl;
        }
        .map-legend {
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.4);
        }
        .legend-icon {
            width: 12px;
            height: 12px;
            display: inline-block;
            margin-left: 5px;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.5.3/leaflet.markercluster.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize map centered on Saudi Arabia
            const map = L.map('map', {
                zoomControl: false,
                preferCanvas: true
            }).setView([23.8859, 45.0792], 6);
            
            // Add zoom control with RTL position
            L.control.zoom({
                position: 'topright'
            }).addTo(map);

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Create marker cluster group
            const markers = L.markerClusterGroup({
                spiderfyOnMaxZoom: true,
                showCoverageOnHover: false,
                zoomToBoundsOnClick: true,
                maxClusterRadius: 60
            });

            // Custom icons
            const emergencyIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34]
            });
            
            const processingIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-yellow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34]
            });
            
            const cleanedIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34]
            });

            // Get sites data from PHP
            const sites = @json($sites);
            const bounds = L.latLngBounds();

            // Add markers to cluster group
            sites.forEach(site => {
                let icon;
                if (site.waste_reports_count > 5) {
                    icon = emergencyIcon;
                } else if (site.waste_reports_count > 0) {
                    icon = processingIcon;
                } else {
                    icon = cleanedIcon;
                }

                const marker = L.marker([site.latitude, site.longitude], { icon })
                    .bindPopup(`
                        <div class="rtl-text">
                            <h3 class="font-bold text-lg text-gray-800">${site.name}</h3>
                            <p class="text-gray-600 text-sm">${site.address}</p>
                            <hr class="my-2 border-gray-200">
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div>
                                    <span class="font-medium">عدد البلاغات:</span>
                                    <span class="text-red-500 font-bold">${site.waste_reports_count || 0}</span>
                                </div>
                                <div>
                                    <span class="font-medium">العمليات النشطة:</span>
                                    <span class="text-blue-500 font-bold">${site.garbage_schedules_count || 0}</span>
                                </div>
                                <div>
                                    <span class="font-medium">آخر تحديث:</span>
                                    <span>${site.updated_at || 'غير متوفر'}</span>
                                </div>
                            </div>
                            <div class="mt-3 text-center">
                                <a href="${site.url}" class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-1 rounded-lg text-sm transition duration-200">
                                    <i class="fas fa-info-circle ml-1"></i> تفاصيل الموقع
                                </a>
                            </div>
                        </div>
                    `);
                
                markers.addLayer(marker);
                bounds.extend([site.latitude, site.longitude]);
            });

            // Add clustered markers to map
            map.addLayer(markers);

            // Fit map to bounds if we have sites
            if (sites.length > 0) {
                map.fitBounds(bounds, { padding: [50, 50] });
            }

            // Add geolocation control
            map.locate({setView: false, maxZoom: 16});
            map.on('locationfound', function(e) {
                L.marker([e.latlng.lat, e.latlng.lng], {
                    icon: L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
                        iconSize: [25, 41]
                    })
                }).addTo(map)
                .bindPopup("موقعك الحالي").openPopup();
            });

            // Filter functionality
            document.getElementById('filter-status').addEventListener('change', function() {
                const filterValue = this.value;
                markers.clearLayers();
                
                sites.forEach(site => {
                    if (filterValue === 'all' || 
                        (filterValue === 'emergency' && site.waste_reports_count > 5) ||
                        (filterValue === 'processing' && site.waste_reports_count > 0 && site.waste_reports_count <= 5) ||
                        (filterValue === 'cleaned' && site.waste_reports_count === 0)) {
                        
                        let icon;
                        if (site.waste_reports_count > 5) {
                            icon = emergencyIcon;
                        } else if (site.waste_reports_count > 0) {
                            icon = processingIcon;
                        } else {
                            icon = cleanedIcon;
                        }

                        const marker = L.marker([site.latitude, site.longitude], { icon })
                            .bindPopup(/* same popup content as before */);
                        
                        markers.addLayer(marker);
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>