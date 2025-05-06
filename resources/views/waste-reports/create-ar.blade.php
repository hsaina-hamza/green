<x-app-layout>
    <div class="py-12" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6 text-right">إبلاغ عن النفايات بشكل غير شرعي</h1>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Map Section -->
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-900 text-right">انقر على الخريطة لتحديد موقع المستودع</label>
                    <div id="map" style="height: 400px; width: 100%; border-radius: 0.5rem; border: 1px solid #e5e7eb;"></div>
                </div>

                <form action="{{ route('waste-reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}" required>
                    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}" required>

                    <!-- Waste Type -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 text-right">نوع النفايات</label>
                        <select name="waste_type_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 text-right" required>
                            <option value="">حدد النوع</option>
                            @foreach($wasteTypes as $type)
                                <option value="{{ $type->id }}" {{ old('waste_type_id') == $type->id ? 'selected' : '' }}>
                                    @switch($type->name)
                                        @case('Household Waste')
                                            النفايات المنزلية
                                            @break
                                        @case('Recyclable Materials')
                                            المواد القابلة لإعادة التدوير
                                            @break
                                        @case('Organic Waste')
                                            النفايات العضوية
                                            @break
                                        @case('Construction Debris')
                                            مخلفات البناء
                                            @break
                                        @case('Hazardous Waste')
                                            النفايات الخطرة
                                            @break
                                        @default
                                            {{ $type->name }}
                                    @endswitch
                                </option>
                            @endforeach
                        </select>
                        @error('waste_type_id')
                            <p class="mt-1 text-sm text-red-600 text-right">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 text-right">وصف</label>
                        <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 text-right" placeholder="اكتب وصفاً للنفايات هنا...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 text-right">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Urgency Level -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 text-right">مستوى الأولوية</label>
                        <select name="urgency_level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 text-right">
                            <option value="normal" {{ old('urgency_level') == 'normal' ? 'selected' : '' }}>عادي</option>
                            <option value="urgent" {{ old('urgency_level') == 'urgent' ? 'selected' : '' }}>عاجل</option>
                            <option value="critical" {{ old('urgency_level') == 'critical' ? 'selected' : '' }}>حرج</option>
                        </select>
                        @error('urgency_level')
                            <p class="mt-1 text-sm text-red-600 text-right">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Images -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 text-right">الصور</label>
                        <input type="file" name="image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                        @error('image')
                            <p class="mt-1 text-sm text-red-600 text-right">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location Validation -->
                    @error('latitude')
                        <p class="mt-1 text-sm text-red-600 text-right">{{ $message }}</p>
                    @enderror
                    @error('longitude')
                        <p class="mt-1 text-sm text-red-600 text-right">{{ $message }}</p>
                    @enderror

                    <!-- Submit Button -->
                    <div class="flex justify-center">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-8 rounded-lg text-lg">
                            إرسال التقرير
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize map centered on Morocco
            const map = L.map('map').setView([31.7917, -7.0926], 6);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            let marker;

            // Handle map clicks
            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;

                // Update hidden inputs
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                // Update or create marker
                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng).addTo(map);
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
