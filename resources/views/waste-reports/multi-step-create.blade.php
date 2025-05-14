<x-app-layout dir="rtl">
    <x-slot name="header">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('الإبلاغ عن النفايات - الخطوة 1') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('waste-reports.store') }}" class="bg-white p-6 rounded-xl shadow-md space-y-6" dir="rtl">
            @csrf

            <!-- Step 1: Basic Info -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-green-700 border-b pb-2">المعلومات الأساسية</h3>
                
                <div>
                    <x-input-label for="title" :value="__('عنوان التقرير')" />
                    <x-text-input 
                        id="title" 
                        name="title" 
                        type="text" 
                        class="mt-1 block w-full" 
                        placeholder="عنوان مختصر" 
                        required 
                        :value="old('title')"
                    />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" :value="__('الوصف')" />
                    <x-text-area 
                        id="description" 
                        name="description" 
                        class="mt-1 block w-full" 
                        rows="4"
                        placeholder="وصف النفايات (النوع، الكمية، أي مخاطر محتملة)"
                        required
                    >{{ old('description') }}</x-text-area>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>
            </div>

            <!-- Step 2: Site, Urgency, Type -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-green-700 border-b pb-2">تفاصيل النفايات</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <x-input-label for="site_id" :value="__('الموقع')" />
                        <x-select 
                            id="site_id" 
                            name="site_id" 
                            class="mt-1 block w-full" 
                            required
                        >
                            <option value="">-- اختر موقع --</option>
                            @foreach ($sites as $site)
                                <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error :messages="$errors->get('site_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="urgency_level" :value="__('مستوى الأهمية')" />
                        <x-select 
                            id="urgency_level" 
                            name="urgency_level" 
                            class="mt-1 block w-full" 
                            required
                        >
                            <option value="">-- اختر مستوى --</option>
                            <option value="low" {{ old('urgency_level') == 'low' ? 'selected' : '' }}>منخفض</option>
                            <option value="medium" {{ old('urgency_level') == 'medium' ? 'selected' : '' }}>متوسط</option>
                            <option value="high" {{ old('urgency_level') == 'high' ? 'selected' : '' }}>عالي</option>
                        </x-select>
                        <x-input-error :messages="$errors->get('urgency_level')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="type" :value="__('نوع النفايات')" />
                        <x-select 
                            id="type" 
                            name="type" 
                            class="mt-1 block w-full" 
                            required
                        >
                            <option value="">-- اختر نوع --</option>
                            <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>عامة</option>
                            <option value="recyclable" {{ old('type') == 'recyclable' ? 'selected' : '' }}>قابلة لإعادة التدوير</option>
                            <option value="hazardous" {{ old('type') == 'hazardous' ? 'selected' : '' }}>خطرة</option>
                            <option value="organic" {{ old('type') == 'organic' ? 'selected' : '' }}>عضوية</option>
                        </x-select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Step 3: Location, Optional Image -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-green-700 border-b pb-2">الموقع والصورة</h3>
                
                <div>
                    <x-input-label :value="__('تحديد الموقع على الخريطة')" />
                    <div id="map" class="w-full h-64 rounded-lg border border-gray-300 mt-1"></div>
                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                    <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                    <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="location_details" :value="__('تفاصيل الموقع')" />
                    <x-text-input 
                        id="location_details" 
                        name="location_details" 
                        type="text" 
                        class="mt-1 block w-full" 
                        placeholder="اتجاهات أو ملاحظات إضافية (اختياري)"
                        :value="old('location_details')"
                    />
                    <x-input-error :messages="$errors->get('location_details')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="image" :value="__('صورة النفايات')" />
                    <x-file-input 
                        id="image" 
                        name="image" 
                        class="mt-1 block w-full" 
                        accept="image/*"
                    />
                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    <p class="mt-1 text-sm text-gray-500">يمكنك رفع صورة توضح النفايات (اختياري)</p>
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <x-primary-button class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('إرسال التقرير') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    @push('scripts')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const map = L.map('map').setView([24.7136, 46.6753], 13); // Default to Riyadh coordinates
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                
                let marker;
                
                map.on('click', function(e) {
                    if (marker) {
                        map.removeLayer(marker);
                    }
                    marker = L.marker(e.latlng).addTo(map);
                    document.getElementById('latitude').value = e.latlng.lat;
                    document.getElementById('longitude').value = e.latlng.lng;
                });
                
                // Initialize with old values if exists
                @if(old('latitude') && old('longitude'))
                    marker = L.marker([{{ old('latitude') }}, {{ old('longitude') }}]).addTo(map);
                    map.setView([{{ old('latitude') }}, {{ old('longitude') }}], 15);
                @endif
            });
        </script>
    @endpush
</x-app-layout>