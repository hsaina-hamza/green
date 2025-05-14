<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <i class="fas fa-map-marker-alt ml-2 text-blue-500"></i>
                {{ __('اختيار الموقع') }}
            </h2>
            <a href="{{ route('waste-reports.create') }}" 
               class="flex items-center bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg">
                <i class="fas fa-arrow-right ml-2"></i>
                {{ __('رجوع') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4 bg-blue-50 p-4 rounded-lg border border-blue-100">
                        <h3 class="text-lg font-medium text-blue-800 flex items-center">
                            <i class="fas fa-info-circle ml-2"></i>
                            تعليمات
                        </h3>
                        <p class="mt-2 text-blue-700">
                            الرجاء اختيار موقع من الخريطة أدناه لتقديم بلاغ النفايات
                        </p>
                    </div>

                    <div id="map" class="w-full h-96 rounded-lg border border-gray-200 shadow"></div>

                    <div class="mt-6">
                        <form id="locationForm" action="{{ route('waste-reports.create') }}" method="GET">
                            <input type="hidden" name="selected_location" id="selected_location">
                            <div class="flex justify-end">
                                <button type="submit" 
                                        class="flex items-center bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg transition duration-150 ease-in-out"
                                        disabled
                                        id="confirmButton">
                                    <i class="fas fa-check-circle ml-2"></i>
                                    {{ __('تأكيد الموقع') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap&language=ar" async defer></script>
    <script>
        let map;
        let markers = [];
        let selectedMarker = null;

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: 31.7917, lng: -7.0926 }, // Morocco center
                zoom: 6,
                streetViewControl: false,
                fullscreenControl: true,
                mapTypeControl: true,
                gestureHandling: 'cooperative'
            });

            // Get all locations from the backend
            const locations = @json($locations);

            // Add markers for each location
            locations.forEach(location => {
                const marker = new google.maps.Marker({
                    position: { 
                        lat: parseFloat(location.latitude), 
                        lng: parseFloat(location.longitude) 
                    },
                    map: map,
                    title: location.name,
                    icon: {
                        url: `https://maps.google.com/mapfiles/ms/icons/red-dot.png`
                    }
                });

                // Add info window for each marker
                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div class="p-2">
                            <h3 class="font-bold text-lg">${location.name}</h3>
                            <p class="text-gray-600">${location.address || 'لا يوجد عنوان'}</p>
                            <button onclick="selectLocation(${location.id})" 
                                    class="mt-2 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                اختيار هذا الموقع
                            </button>
                        </div>
                    `
                });

                marker.addListener('click', () => {
                    infoWindow.open(map, marker);
                });

                markers.push(marker);
            });
        }

        function selectLocation(locationId) {
            // Reset all markers to red
            markers.forEach(marker => {
                marker.setIcon('https://maps.google.com/mapfiles/ms/icons/red-dot.png');
            });

            // Highlight selected marker
            const selected = markers.find(m => m.title === locationId.toString());
            if (selected) {
                selected.setIcon('https://maps.google.com/mapfiles/ms/icons/green-dot.png');
            }

            document.getElementById('selected_location').value = locationId;
            document.getElementById('confirmButton').disabled = false;
            
            // Close all info windows
            document.querySelectorAll('.gm-style-iw').forEach(el => {
                el.parentElement.style.display = 'none';
            });
        }
    </script>
    @endpush

    <style>
        [dir="rtl"] .gm-style-iw {
            text-align: right;
            direction: rtl;
        }
        .gm-style-iw button {
            display: none !important;
        }
    </style>
</x-app-layout>