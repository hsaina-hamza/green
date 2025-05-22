<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                </svg>
                {{ __('🗺️ خريطة مواقع النفايات') }}
            </h2>
            <a href="{{ route('sites.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                {{ __('العودة للقائمة') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-700 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                            </svg>
                            {{ __('توزيع مواقع النفايات') }}
                        </h3>
                        <div class="flex items-center space-x-2 rtl:space-x-reverse">
                            <span class="bg-green-500 w-3 h-3 rounded-full"></span>
                            <span class="text-sm text-gray-600">{{ __('مواقع نشطة') }}</span>
                            <span class="bg-red-500 w-3 h-3 rounded-full ml-2"></span>
                            <span class="text-sm text-gray-600">{{ __('مواقع بحاجة لاهتمام') }}</span>
                        </div>
                    </div>
                    <div id="map" class="h-[600px] w-full rounded-lg border border-gray-300"></div>
                    <div class="mt-4 text-sm text-gray-500 flex justify-between items-center">
                        <div>
                            {{ __('إجمالي المواقع:') }} <span class="font-bold">{{ count($sites) }}</span>
                        </div>
                        <div class="text-blue-600 hover:text-blue-800 cursor-pointer" id="reset-map">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                            </svg>
                            {{ __('إعادة ضبط الخريطة') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
    <style>
        .leaflet-popup-content {
            min-width: 200px;
        }
        .leaflet-popup-content-wrapper {
            border-radius: 8px;
        }
        .map-legend {
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
    <script>
        // Initialize map with a default center
        const map = L.map('map').setView([24.7136, 46.6753], 10); // Default to Riyadh coordinates

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Create marker cluster group
        const markers = L.markerClusterGroup({
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: false,
            zoomToBoundsOnClick: true
        });

        // Get sites data from PHP
        const sites = @json($sites);

        // Bounds for auto-centering
        const bounds = L.latLngBounds();

        // Custom icons
        const greenIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        const redIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        // Add markers for each site
        sites.forEach(site => {
            // Determine icon based on reports count
            const icon = (site.waste_reports_count > 3) ? redIcon : greenIcon;
            
            const marker = L.marker([site.latitude, site.longitude], { icon })
                .bindPopup(`
                    <div class="font-bold text-lg mb-1">${site.name}</div>
                    <div class="text-gray-600 mb-2">${site.address}</div>
                    <div class="grid grid-cols-2 gap-2 my-2">
                        <div class="bg-gray-100 p-2 rounded">
                            <div class="text-xs text-gray-500">البلاغات</div>
                            <div class="font-bold">${site.waste_reports_count || 0}</div>
                        </div>
                        <div class="bg-gray-100 p-2 rounded">
                            <div class="text-xs text-gray-500">المواعيد</div>
                            <div class="font-bold">${site.garbage_schedules_count || 0}</div>
                        </div>
                    </div>
                    <a href="${site.url}" class="block mt-2 text-center bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded transition duration-200">
                        عرض التفاصيل
                    </a>
                `);

            markers.addLayer(marker);
            bounds.extend([site.latitude, site.longitude]);
        });

        // Add markers to map
        map.addLayer(markers);

        // Fit map to bounds if we have sites
        if (sites.length > 0) {
            map.fitBounds(bounds, {
                padding: [50, 50],
                maxZoom: 12
            });
        }

        // Add legend
        const legend = L.control({position: 'bottomright'});
        legend.onAdd = function(map) {
            const div = L.DomUtil.create('div', 'map-legend');
            div.innerHTML = `
                <h4 class="font-bold mb-2">مفتاح الخريطة</h4>
                <div class="flex items-center mb-1">
                    <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png" width="20" class="mr-2">
                    <span>مواقع عادية</span>
                </div>
                <div class="flex items-center">
                    <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png" width="20" class="mr-2">
                    <span>مواقع بحاجة لاهتمام</span>
                </div>
            `;
            return div;
        };
        legend.addTo(map);

        // Reset map view
        document.getElementById('reset-map').addEventListener('click', function() {
            if (sites.length > 0) {
                map.fitBounds(bounds, {
                    padding: [50, 50],
                    maxZoom: 12
                });
            } else {
                map.setView([24.7136, 46.6753], 10);
            }
        });
    </script>
    @endpush
</x-app-layout>