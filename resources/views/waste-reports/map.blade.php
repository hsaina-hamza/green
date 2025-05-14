<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <i class="fas fa-map-marked-alt ml-2 text-green-500"></i>
                {{ __('خريطة تقارير النفايات') }}
            </h2>
            <a href="{{ route('waste-reports.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded flex items-center">
                <i class="fas fa-list ml-2"></i>
                {{ __('العودة للقائمة') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flex items-center">
                    <i class="fas fa-check-circle ml-2"></i>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 flex flex-wrap gap-2">
                        <button id="filter-all" class="filter-btn active bg-blue-600 text-white px-3 py-1 rounded">
                            {{ __('الكل') }}
                        </button>
                        <button id="filter-pending" class="filter-btn bg-yellow-500 text-white px-3 py-1 rounded">
                            {{ __('معلق') }}
                        </button>
                        <button id="filter-in_progress" class="filter-btn bg-orange-500 text-white px-3 py-1 rounded">
                            {{ __('قيد المعالجة') }}
                        </button>
                        <button id="filter-resolved" class="filter-btn bg-green-600 text-white px-3 py-1 rounded">
                            {{ __('تم الحل') }}
                        </button>
                    </div>
                    
                    <div id="map" class="h-[600px] w-full rounded-lg border border-gray-200"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        const reports = @json($reports);
        
        // Initialize the map
        const map = L.map('map');

        // Add the OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Create layer groups for each status
        const markers = {
            all: L.layerGroup(),
            pending: L.layerGroup(),
            in_progress: L.layerGroup(),
            resolved: L.layerGroup()
        };

        // Custom icons
        const pendingIcon = L.divIcon({
            className: 'pending-marker',
            html: '<i class="fas fa-exclamation-circle text-yellow-500 text-2xl"></i>',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        const inProgressIcon = L.divIcon({
            className: 'in-progress-marker',
            html: '<i class="fas fa-spinner text-orange-500 text-2xl"></i>',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        const resolvedIcon = L.divIcon({
            className: 'resolved-marker',
            html: '<i class="fas fa-check-circle text-green-500 text-2xl"></i>',
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        // Add markers for each report
        const bounds = L.latLngBounds();
        reports.forEach(report => {
            let icon;
            switch(report.status) {
                case 'pending':
                    icon = pendingIcon;
                    break;
                case 'in_progress':
                    icon = inProgressIcon;
                    break;
                case 'resolved':
                    icon = resolvedIcon;
                    break;
                default:
                    icon = pendingIcon;
            }

            const marker = L.marker([report.latitude, report.longitude], { icon })
                .bindPopup(`
                    <div class="text-sm" dir="rtl">
                        <h3 class="font-semibold mb-1">${report.title}</h3>
                        <p class="mb-1"><strong>النوع:</strong> ${report.type}</p>
                        <p class="mb-1"><strong>الحالة:</strong> 
                            <span class="px-2 py-1 text-xs rounded-full ${
                                report.status === 'resolved' ? 'bg-green-100 text-green-800' :
                                report.status === 'in_progress' ? 'bg-orange-100 text-orange-800' :
                                'bg-yellow-100 text-yellow-800'
                            }">
                                ${
                                    report.status === 'resolved' ? 'تم الحل' :
                                    report.status === 'in_progress' ? 'قيد المعالجة' :
                                    'معلق'
                                }
                            </span>
                        </p>
                        <p class="mb-1"><strong>الموقع:</strong> ${report.location}</p>
                        <p class="mb-1"><strong>التاريخ:</strong> ${new Date(report.created_at).toLocaleDateString('ar-EG')}</p>
                        <a href="${report.url}" class="text-blue-600 hover:text-blue-800 block mt-2 text-center">
                            <i class="fas fa-info-circle ml-1"></i>
                            عرض التفاصيل
                        </a>
                    </div>
                `);

            markers.all.addLayer(marker);
            
            if (report.status === 'pending') {
                markers.pending.addLayer(marker);
            } else if (report.status === 'in_progress') {
                markers.in_progress.addLayer(marker);
            } else if (report.status === 'resolved') {
                markers.resolved.addLayer(marker);
            }

            bounds.extend([report.latitude, report.longitude]);
        });

        // Add all markers by default
        markers.all.addTo(map);

        // Fit the map to show all markers
        if (!bounds.isEmpty()) {
            map.fitBounds(bounds, { padding: [50, 50] });
        } else {
            // Default view if no markers (centered on Saudi Arabia)
            map.setView([23.8859, 45.0792], 5);
        }

        // Filter buttons functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active button
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Remove all layers
                Object.values(markers).forEach(layer => map.removeLayer(layer));
                
                // Add the selected layer
                const filter = this.id.replace('filter-', '');
                markers[filter].addTo(map);
                
                // Fit bounds to visible markers
                if (!bounds.isEmpty()) {
                    map.fitBounds(bounds, { padding: [50, 50] });
                }
            });
        });
    </script>
    @endpush

    <style>
        .leaflet-popup-content {
            margin: 13px 19px;
        }
        .filter-btn {
            transition: all 0.2s ease;
        }
        .filter-btn:hover {
            transform: translateY(-1px);
        }
        .filter-btn.active {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>
</x-app-layout>