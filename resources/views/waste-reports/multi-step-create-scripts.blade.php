@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="anonymous" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin="anonymous"></script>

<script>
    // Initialize the map
    const map = L.map('map').setView([51.505, -0.09], 13);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    let marker;

    // Handle map clicks
    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;

        // Update hidden form fields
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;

        // Update or add marker
        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }
    });

    // If editing and coordinates exist, show marker
    const initialLat = document.getElementById('latitude').value;
    const initialLng = document.getElementById('longitude').value;
    if (initialLat && initialLng) {
        const latlng = [parseFloat(initialLat), parseFloat(initialLng)];
        map.setView(latlng, 13);
        marker = L.marker(latlng).addTo(map);
    }

    // Try to get user's location
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;
            map.setView([userLat, userLng], 13);
        });
    }
</script>
@endpush
