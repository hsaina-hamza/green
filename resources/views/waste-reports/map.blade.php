<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Waste Reports Map') }}
            </h2>
            <a href="{{ route('waste-reports.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="map" class="h-[600px] w-full rounded-lg"></div>
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

        // Add markers for each report
        const bounds = L.latLngBounds();
        reports.forEach(report => {
            const marker = L.marker([report.latitude, report.longitude])
                .bindPopup(`
                    <div class="text-sm">
                        <h3 class="font-semibold mb-1">${report.title}</h3>
                        <p class="mb-1">Type: ${report.type}</p>
                        <p class="mb-1">Status: ${report.status}</p>
                        <p class="mb-2">Location: ${report.location}</p>
                        <a href="${report.url}" class="text-blue-600 hover:text-blue-800">View Details</a>
                    </div>
                `);
            marker.addTo(map);
            bounds.extend([report.latitude, report.longitude]);
        });

        // Fit the map to show all markers
        if (!bounds.isEmpty()) {
            map.fitBounds(bounds, { padding: [50, 50] });
        } else {
            // Default view if no markers
            map.setView([0, 0], 2);
        }
    </script>
    @endpush
</x-app-layout>
