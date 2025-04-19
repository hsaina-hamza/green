@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-xl font-semibold mb-4">Waste Map</h2>

    <!-- Map container -->
    <div id="map" style="height: 500px;"></div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    // Initialize the map
    var map = L.map('map').setView([36.7783, -119.4179], 6); // Default to a central location

    // Set up the tile layer (this is a free open-source option)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Loop through all waste reports and add markers
    @foreach($wasteReports as $report)
        var marker = L.marker([{{ $report->latitude }}, {{ $report->longitude }}]).addTo(map);

        // Set a popup with report details
        marker.bindPopup(`
            <strong>Waste Type:</strong> {{ $report->type }} <br>
            <strong>Status:</strong> {{ $report->status }} <br>
            <strong>Location:</strong> ({{ $report->latitude }}, {{ $report->longitude }})
        `);
    @endforeach
</script>
@endpush
@endsection
