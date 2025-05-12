@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Waste Reports Map</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('reports.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> New Report
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div id="map" style="height: 600px;"></div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the map
    const map = L.map('map').setView([35.6895, 10.0979], 13);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add markers for each report
    const reports = @json($reports);
    reports.forEach(report => {
        L.marker([report.latitude, report.longitude])
            .addTo(map)
            .bindPopup(`
                <strong>${report.title}</strong><br>
                Type: ${report.waste_type}<br>
                Status: ${report.status}<br>
                <a href="${route('reports.show', report.id)}">View Details</a>
            `);
    });

    // Fit map bounds to show all markers
    if (reports.length > 0) {
        const bounds = reports.map(report => [report.latitude, report.longitude]);
        map.fitBounds(bounds);
    }
});
</script>
@endpush
@endsection
