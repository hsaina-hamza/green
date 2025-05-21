@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-green-700">إنشاء بلاغ جديد</h1>
            <p class="text-gray-600">يرجى تعبئة المعلومات التالية للإبلاغ عن النفايات</p>
        </div>

        <!-- Form -->
        <form action="{{ route('waste-reports.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow p-6">
            @csrf

            <!-- Waste Type -->
            <div class="mb-4">
                <label for="type" class="block text-gray-700 font-bold mb-2">نوع النفايات</label>
                <select name="type" id="type" class="w-full rounded-lg border-gray-300 @error('type') border-red-500 @enderror" required>
                    <option value="">اختر نوع النفايات</option>
                    @foreach($wasteTypes ?? [] as $type)
                        <option value="{{ $type->id }}" {{ old('type') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
                @error('type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Location -->
            <div class="mb-4">
                <label for="location" class="block text-gray-700 font-bold mb-2">الموقع</label>
                <div id="map" class="h-64 mb-2 rounded-lg"></div>
                <input type="text" name="location" id="location" 
                       class="w-full rounded-lg border-gray-300 @error('location') border-red-500 @enderror"
                       value="{{ old('location') }}" required>
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                @error('location')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-bold mb-2">الوصف</label>
                <textarea name="description" id="description" rows="4" 
                          class="w-full rounded-lg border-gray-300 @error('description') border-red-500 @enderror"
                          >{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image Upload -->
            <div class="mb-6">
                <label for="image" class="block text-gray-700 font-bold mb-2">صورة (اختياري)</label>
                <input type="file" name="image" id="image" accept="image/*" 
                       class="w-full border border-gray-300 rounded-lg p-2 @error('image') border-red-500 @enderror">
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-xl hover:bg-green-700">
                    إرسال البلاغ
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Map Script -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('map').setView([35.6895, 10.0979], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        var marker;

        map.on('click', function(e) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker(e.latlng).addTo(map);
            
            // Update form fields
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
            
            // Reverse geocoding to get address
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${e.latlng.lat}&lon=${e.latlng.lng}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('location').value = data.display_name;
                });
        });
    });
</script>
@endsection
