<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('إبلاغ عن نفايات') }}
            </h2>
            <a href="{{ route('waste-reports.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition duration-300 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                {{ __('العودة إلى القائمة') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">{{ __('عفواً!') }}</strong>
                            <span class="block sm:inline">{{ __('هناك بعض المشاكل في المدخلات الخاصة بك.') }}</span>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('waste-reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="waste_type_id" :value="__('نوع النفايات')" />
                                <select id="waste_type_id" name="waste_type_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm py-2 px-3 border" required autofocus>
                                    <option value="">{{ __('اختر نوع النفايات') }}</option>
                                    @foreach($wasteTypes as $wasteType)
                                        <option value="{{ $wasteType->id }}" {{ old('waste_type_id') == $wasteType->id ? 'selected' : '' }}>
                                            {{ $wasteType->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('waste_type_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="location_id" :value="__('الموقع')" />
                                <select id="location_id" name="location_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm py-2 px-3 border" required>
                                    <option value="">{{ __('اختر موقع') }}</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('location_id')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="quantity" :value="__('الكمية')" />
                                <x-text-input id="quantity" name="quantity" type="number" step="0.01" class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md" :value="old('quantity')" required />
                                <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="unit" :value="__('الوحدة')" />
                                <select id="unit" name="unit" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm py-2 px-3 border" required>
                                    <option value="">{{ __('اختر وحدة') }}</option>
                                    <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>{{ __('كيلوجرام (كجم)') }}</option>
                                    <option value="tons" {{ old('unit') == 'tons' ? 'selected' : '' }}>{{ __('أطنان') }}</option>
                                    <option value="liters" {{ old('unit') == 'liters' ? 'selected' : '' }}>{{ __('لترات') }}</option>
                                    <option value="cubic_meters" {{ old('unit') == 'cubic_meters' ? 'selected' : '' }}>{{ __('متر مكعب') }}</option>
                                    <option value="pieces" {{ old('unit') == 'pieces' ? 'selected' : '' }}>{{ __('قطع') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('الوصف')" />
                            <textarea id="description" name="description" rows="3" 
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm py-2 px-3 border">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="image" :value="__('صورة (اختياري)')" />
                            <div class="mt-1 flex items-center">
                                <label for="image" class="cursor-pointer bg-indigo-50 text-indigo-700 px-4 py-2 rounded-md font-semibold text-sm hover:bg-indigo-100 transition duration-300 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                    </svg>
                                    {{ __('اختر صورة') }}
                                </label>
                                <span id="file-name" class="mr-3 text-sm text-gray-600"></span>
                                <input type="file" id="image" name="image" class="hidden" />
                            </div>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6">
                            <a href="{{ route('waste-reports.index') }}" class="text-gray-600 hover:text-gray-900 transition duration-300">
                                {{ __('إلغاء') }}
                            </a>
                            <x-primary-button class="bg-green-600 hover:bg-green-700 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                {{ __('إرسال التقرير') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Show selected file name
        document.getElementById('image').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || '{{ __("لم يتم اختيار ملف") }}';
            document.getElementById('file-name').textContent = fileName;
        });
    </script>
</x-app-layout>