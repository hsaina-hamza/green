<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Select Location') }}
            </h2>
            <a href="{{ route('waste-reports.create') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="map" class="w-full h-96"></div>

                    <div class="mt-4">
                        <form id="locationForm" action="{{ route('waste-reports.create') }}" method="GET">
                            <input type="hidden" name="selected_location" id="selected_location">
                            <button type="submit" 
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded"
                                    disabled
                                    id="confirmButton">
                                {{ __('Confirm Location') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap" async defer></script>
    <script>
        let map;
        let marker;

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: 31.7917, lng: -7.0926 }, // Morocco center
                zoom: 6
            });

            // Get all locations from the backend
            const locations = @json($locations);

            locations.forEach(location => {
                const marker = new google.maps.Marker({
                    position: { lat: parseFloat(location.latitude), lng: parseFloat(location.longitude) },
                    map: map,
                    title: location.name
                });

                marker.addListener('click', () => {
                    document.getElementById('selected_location').value = location.id;
                    document.getElementById('confirmButton').disabled = false;
                });
            });
        }
    </script>
    @endpush
</x-app-layout>
