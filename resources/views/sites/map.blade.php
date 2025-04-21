<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('🗺️ Waste Sites Map') }}
            </h2>
            <a href="{{ route('sites.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="map" class="h-[500px] w-full rounded-lg"></div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        // Initialize map with a default center (will be adjusted based on sites)
        const map = L.map('map').setView([0, 0], 12);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Get sites data from PHP
        const sites = @json($sites);

        // Bounds for auto-centering
        const bounds = L.latLngBounds();

        // Add markers for each site
        sites.forEach(site => {
            const marker = L.marker([site.latitude, site.longitude])
                .addTo(map)
                .bindPopup(`
                    <strong>${site.name}</strong><br>
                    ${site.address}<br>
                    <hr class="my-2">
                    <strong>Reports:</strong> ${site.waste_reports_count || 0}<br>
                    <strong>Active Schedules:</strong> ${site.garbage_schedules_count || 0}<br>
                    <a href="${site.url}" class="text-blue-600 hover:text-blue-800">View Details</a>
                `);

            // Extend bounds to include this marker
            bounds.extend([site.latitude, site.longitude]);
        });

        // Fit map to bounds if we have sites
        if (sites.length > 0) {
            map.fitBounds(bounds, {
                padding: [50, 50]
            });
        }

        // Custom marker colors based on site status or type could be added here
    </script>
    @endpush
</x-app-layout>
