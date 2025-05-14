@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" 
      integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" 
      crossorigin="anonymous" />
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" 
        integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" 
        crossorigin="anonymous"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Default to Riyadh coordinates for Arabic users
        const defaultCoords = [24.7136, 46.6753];
        const map = L.map('map', {
            zoomControl: false // We'll add our custom RTL control
        }).setView(defaultCoords, 13);

        // Add OpenStreetMap tiles with Arabic attribution
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> مساهمون'
        }).addTo(map);

        // Add custom RTL zoom control
        L.control.zoom({
            position: 'topright'
        }).addTo(map);

        let marker;
        const latitudeField = document.getElementById('latitude');
        const longitudeField = document.getElementById('longitude');

        // Handle map clicks
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            // Update hidden form fields
            latitudeField.value = lat;
            longitudeField.value = lng;

            // Update or add marker
            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng, {
                    draggable: true,
                    icon: L.divIcon({
                        className: 'custom-marker',
                        html: '<i class="fas fa-map-marker-alt text-red-500 text-2xl"></i>',
                        iconSize: [30, 30],
                        iconAnchor: [15, 30]
                    })
                }).addTo(map);
                
                // Handle marker dragging
                marker.on('dragend', function(e) {
                    const position = marker.getLatLng();
                    latitudeField.value = position.lat;
                    longitudeField.value = position.lng;
                });
            }
        });

        // If editing and coordinates exist, show marker
        if (latitudeField.value && longitudeField.value) {
            const latlng = [
                parseFloat(latitudeField.value), 
                parseFloat(longitudeField.value)
            ];
            map.setView(latlng, 15);
            marker = L.marker(latlng, {
                draggable: true,
                icon: L.divIcon({
                    className: 'custom-marker',
                    html: '<i class="fas fa-map-marker-alt text-red-500 text-2xl"></i>',
                    iconSize: [30, 30],
                    iconAnchor: [15, 30]
                })
            }).addTo(map);
            
            marker.on('dragend', function(e) {
                const position = marker.getLatLng();
                latitudeField.value = position.lat;
                longitudeField.value = position.lng;
            });
        }

        // Try to get user's location with Arabic messages
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;
                    map.setView([userLat, userLng], 15);
                    
                    // Add marker at user's location
                    L.marker([userLat, userLng], {
                        icon: L.divIcon({
                            className: 'user-marker',
                            html: '<i class="fas fa-user-circle text-blue-500 text-2xl"></i>',
                            iconSize: [30, 30],
                            iconAnchor: [15, 30]
                        })
                    }).addTo(map)
                    .bindPopup("موقعك الحالي").openPopup();
                },
                function(error) {
                    console.error("خطأ في الحصول على الموقع:", error.message);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        } else {
            console.log("Geolocation غير مدعوم في هذا المتصفح");
        }

        // Add custom button to clear marker
        const clearButton = L.control({position: 'topright'});
        clearButton.onAdd = function(map) {
            const div = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
            div.innerHTML = '<a href="#" title="إزالة العلامة" role="button" class="bg-white p-2 block"><i class="fas fa-trash text-red-500"></i></a>';
            div.onclick = function() {
                if (marker) {
                    map.removeLayer(marker);
                    marker = null;
                    latitudeField.value = '';
                    longitudeField.value = '';
                }
                return false;
            };
            return div;
        };
        clearButton.addTo(map);
    });
</script>

<style>
    .custom-marker {
        background: transparent;
        border: none;
    }
    .user-marker {
        background: transparent;
        border: none;
    }
    .leaflet-control a {
        font-family: 'Tajawal', sans-serif !important;
    }
    #map {
        direction: ltr; /* Keep map controls LTR */
    }
</style>

@if(config('app.locale') === 'ar')
<!-- Load Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
<!-- Load Arabic font -->
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
@endif
@endpush