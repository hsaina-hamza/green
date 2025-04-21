<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Waste Map') }} 🗺️
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="rounded-xl border border-gray-200 overflow-hidden">
                        <!-- Map container -->
                        <div id="map" class="w-full h-[500px]"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<style>
    #map {
        width: 100%;
        height: 500px;
        border-radius: 12px;
    }

    .leaflet-popup-content {
        font-size: 14px;
        line-height: 1.4;
    }
</style>
@endpush

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<script>
    // Initialize the map
    var map = L.map('map').setView([36.7783, -119.4179], 6);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Add markers for each report
    @foreach($wasteReports as $report)
        var marker = L.marker([{{ $report->latitude }}, {{ $report->longitude }}]).addTo(map);
        marker.bindPopup(`
            <div class="popup-content">
                <strong>Waste Type:</strong> {{ $report->type }} <br>
                <strong>Status:</strong> {{ $report->status }} <br>
                <strong>Coordinates:</strong> ({{ $report->latitude }}, {{ $report->longitude }})
            </div>
        `);
    @endforeach
</script>
@endpush
