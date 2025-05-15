<x-app-layout dir="rtl">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('إبلاغ عن نفايات') }}
            </h2>
            <a href="{{ route('waste-reports.index') }}" 
               class="text-white font-bold py-3 px-6 rounded-lg shadow-md
                    @if(Auth::user()->role === 'admin') bg-purple-600 hover:bg-purple-700
                    @elseif(Auth::user()->role === 'worker') bg-blue-600 hover:bg-blue-700
                    @else bg-green-600 hover:bg-green-700 @endif 
                    transition-all duration-300 transform hover:scale-105">
                {{ __('العودة إلى القائمة') }}
                <i class="fas fa-arrow-left mr-2"></i>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success Alert -->
            @if (session('success'))
                <div x-data="{ show: true }" 
                     x-show="show"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-4"
                     x-init="setTimeout(() => show = false, 5000)"
                     class="mb-8 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-6 rounded-lg shadow-lg flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-emerald-500 text-xl mr-4"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-700 hover:text-emerald-900">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            <!-- Error Alert -->
            @if ($errors->any())
                <div x-data="{ show: true }" 
                     x-show="show"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-4"
                     class="mb-8 bg-red-100 border-l-4 border-red-500 text-red-700 p-6 rounded-lg shadow-lg">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl mr-4"></i>
                            <h3 class="font-bold text-lg">{{ __('عذراً!') }}</h3>
                        </div>
                        <button @click="show = false" class="text-red-700 hover:text-red-900">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <p class="mb-3">{{ __('هناك بعض المشاكل في المدخلات الخاصة بك.') }}</p>
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="overflow-hidden shadow-xl sm:rounded-2xl border-2
                @if(Auth::user()->role === 'admin') bg-gradient-to-br from-purple-50 to-white border-purple-200
                @elseif(Auth::user()->role === 'worker') bg-gradient-to-br from-blue-50 to-white border-blue-200
                @else bg-gradient-to-br from-green-50 to-white border-green-200 @endif
                transition-all duration-300 hover:shadow-2xl">
                <div class="p-8 md:p-10 text-gray-900">
                    <form action="{{ route('waste-reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Waste Type -->
                            <div class="space-y-2">
                                <label for="waste_type_id" class="block font-medium text-lg
                                    @if(Auth::user()->role === 'admin') text-purple-800
                                    @elseif(Auth::user()->role === 'worker') text-blue-800
                                    @else text-green-800 @endif">
                                    {{ __('نوع النفايات') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select id="waste_type_id" name="waste_type_id" 
                                        class="mt-2 block w-full rounded-xl shadow-sm text-lg py-3 px-4 pr-10 appearance-none
                                        @if(Auth::user()->role === 'admin') border-2 border-purple-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200
                                        @elseif(Auth::user()->role === 'worker') border-2 border-blue-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                                        @else border-2 border-green-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 @endif
                                        transition-all duration-300"
                                        required autofocus>
                                        <option value="">{{ __('اختر نوع النفايات') }}</option>
                                        @foreach($wasteTypes as $wasteType)
                                            <option value="{{ $wasteType->id }}" {{ old('waste_type_id') == $wasteType->id ? 'selected' : '' }}>
                                                {{ $wasteType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-2 text-gray-700">
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('waste_type_id')" class="mt-2 text-lg" />
                            </div>

                            <!-- Location -->
                            <div class="space-y-2">
                                <label for="location_id" class="block font-medium text-lg
                                    @if(Auth::user()->role === 'admin') text-purple-800
                                    @elseif(Auth::user()->role === 'worker') text-blue-800
                                    @else text-green-800 @endif">
                                    {{ __('الموقع') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select id="location_id" name="location_id" 
                                        class="mt-2 block w-full rounded-xl shadow-sm text-lg py-3 px-4 pr-10 appearance-none
                                        @if(Auth::user()->role === 'admin') border-2 border-purple-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200
                                        @elseif(Auth::user()->role === 'worker') border-2 border-blue-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                                        @else border-2 border-green-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 @endif
                                        transition-all duration-300"
                                        required>
                                        <option value="">{{ __('اختر الموقع') }}</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                                {{ $location->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-2 text-gray-700">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('location_id')" class="mt-2 text-lg" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Quantity -->
                            <div class="space-y-2">
                                <label for="quantity" class="block font-medium text-lg
                                    @if(Auth::user()->role === 'admin') text-purple-800
                                    @elseif(Auth::user()->role === 'worker') text-blue-800
                                    @else text-green-800 @endif">
                                    {{ __('الكمية') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" step="0.01" id="quantity" name="quantity"
                                        class="mt-2 block w-full rounded-xl shadow-sm text-lg py-3 px-4
                                        @if(Auth::user()->role === 'admin') border-2 border-purple-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200
                                        @elseif(Auth::user()->role === 'worker') border-2 border-blue-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                                        @else border-2 border-green-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 @endif
                                        transition-all duration-300"
                                        value="{{ old('quantity') }}" required />
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-2 text-gray-700">
                                        <i class="fas fa-weight-hanging"></i>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('quantity')" class="mt-2 text-lg" />
                            </div>

                            <!-- Unit -->
                            <div class="space-y-2">
                                <label for="unit" class="block font-medium text-lg
                                    @if(Auth::user()->role === 'admin') text-purple-800
                                    @elseif(Auth::user()->role === 'worker') text-blue-800
                                    @else text-green-800 @endif">
                                    {{ __('الوحدة') }} <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select id="unit" name="unit" 
                                        class="mt-2 block w-full rounded-xl shadow-sm text-lg py-3 px-4 pr-10 appearance-none
                                        @if(Auth::user()->role === 'admin') border-2 border-purple-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200
                                        @elseif(Auth::user()->role === 'worker') border-2 border-blue-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                                        @else border-2 border-green-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 @endif
                                        transition-all duration-300"
                                        required>
                                        <option value="">{{ __('اختر الوحدة') }}</option>
                                        <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>{{ __('كيلوغرام (كغ)') }}</option>
                                        <option value="tons" {{ old('unit') == 'tons' ? 'selected' : '' }}>{{ __('أطنان') }}</option>
                                        <option value="liters" {{ old('unit') == 'liters' ? 'selected' : '' }}>{{ __('لترات') }}</option>
                                        <option value="cubic_meters" {{ old('unit') == 'cubic_meters' ? 'selected' : '' }}>{{ __('أمتار مكعبة') }}</option>
                                        <option value="pieces" {{ old('unit') == 'pieces' ? 'selected' : '' }}>{{ __('قطع') }}</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-2 text-gray-700">
                                        <i class="fas fa-ruler-combined"></i>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('unit')" class="mt-2 text-lg" />
                            </div>
                        </div>

                        <!-- Urgency Level -->
                        <div class="space-y-2">
                            <label for="urgency_level" class="block font-medium text-lg
                                @if(Auth::user()->role === 'admin') text-purple-800
                                @elseif(Auth::user()->role === 'worker') text-blue-800
                                @else text-green-800 @endif">
                                {{ __('مستوى الخطورة') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select id="urgency_level" name="urgency_level" 
                                    class="mt-2 block w-full rounded-xl shadow-sm text-lg py-3 px-4 pr-10 appearance-none
                                    @if(Auth::user()->role === 'admin') border-2 border-purple-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200
                                    @elseif(Auth::user()->role === 'worker') border-2 border-blue-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                                    @else border-2 border-green-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 @endif
                                    transition-all duration-300"
                                    required>
                                    <option value="">{{ __('اختر مستوى الخطورة') }}</option>
                                    <option value="low" {{ old('urgency_level') == 'low' ? 'selected' : '' }}>{{ __('منخفض') }}</option>
                                    <option value="medium" {{ old('urgency_level') == 'medium' ? 'selected' : '' }}>{{ __('متوسط') }}</option>
                                    <option value="high" {{ old('urgency_level') == 'high' ? 'selected' : '' }}>{{ __('مرتفع') }}</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-2 text-gray-700">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('urgency_level')" class="mt-2 text-lg" />
                        </div>

                        <!-- Description -->
                        <div class="space-y-2">
                            <label for="description" class="block font-medium text-lg
                                @if(Auth::user()->role === 'admin') text-purple-800
                                @elseif(Auth::user()->role === 'worker') text-blue-800
                                @else text-green-800 @endif">
                                {{ __('الوصف') }}
                            </label>
                            <textarea id="description" name="description" rows="5"
                                class="mt-2 block w-full rounded-xl shadow-sm text-lg py-3 px-4
                                @if(Auth::user()->role === 'admin') border-2 border-purple-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200
                                @elseif(Auth::user()->role === 'worker') border-2 border-blue-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                                @else border-2 border-green-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 @endif
                                transition-all duration-300">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2 text-lg" />
                        </div>

                        <!-- Image Upload -->
                        <div class="space-y-2">
                            <label for="image" class="block font-medium text-lg
                                @if(Auth::user()->role === 'admin') text-purple-800
                                @elseif(Auth::user()->role === 'worker') text-blue-800
                                @else text-green-800 @endif">
                                {{ __('صورة (اختياري)') }}
                            </label>
                            <div class="mt-2">
                                <label for="image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-xl cursor-pointer
                                    @if(Auth::user()->role === 'admin') border-purple-300 hover:border-purple-500 bg-purple-50 hover:bg-purple-100
                                    @elseif(Auth::user()->role === 'worker') border-blue-300 hover:border-blue-500 bg-blue-50 hover:bg-blue-100
                                    @else border-green-300 hover:border-green-500 bg-green-50 hover:bg-green-100 @endif
                                    transition-all duration-300">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <i class="fas fa-cloud-upload-alt text-3xl mb-3
                                            @if(Auth::user()->role === 'admin') text-purple-500
                                            @elseif(Auth::user()->role === 'worker') text-blue-500
                                            @else text-green-500 @endif"></i>
                                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">{{ __('انقر لرفع صورة') }}</span></p>
                                        <p class="text-xs text-gray-500">{{ __('PNG, JPG, JPEG (الحد الأقصى: 5MB)') }}</p>
                                    </div>
                                    <input id="image" name="image" type="file" class="hidden" onchange="updateFileName(this)"/>
                                </label>
                                <div id="file-name-display" class="mt-2 text-sm text-gray-600 text-center"></div>
                            </div>
                            <x-input-error :messages="$errors->get('image')" class="mt-2 text-lg" />
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-start gap-6 pt-8">
                            <button type="submit" class="inline-flex items-center px-10 py-4 border border-transparent rounded-xl font-bold text-lg text-white uppercase tracking-wider focus:outline-none focus:ring-4 focus:ring-opacity-50 transition-all duration-300 transform hover:scale-105
                                @if(Auth::user()->role === 'admin') bg-purple-600 hover:bg-purple-700 focus:ring-purple-300 shadow-lg shadow-purple-200
                                @elseif(Auth::user()->role === 'worker') bg-blue-600 hover:bg-blue-700 focus:ring-blue-300 shadow-lg shadow-blue-200
                                @else bg-green-600 hover:bg-green-700 focus:ring-green-300 shadow-lg shadow-green-200 @endif">
                                <i class="fas fa-paper-plane ml-3"></i>
                                {{ __('إرسال التقرير') }}
                            </button>
                            <a href="{{ route('waste-reports.index') }}" class="text-lg text-gray-600 hover:text-gray-800 font-medium transition-all duration-300 transform hover:scale-105">
                                {{ __('إلغاء') }}
                                <i class="fas fa-times mr-2"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Display selected file name
        function updateFileName(input) {
            const fileNameDisplay = document.getElementById('file-name-display');
            if (input.files.length > 0) {
                fileNameDisplay.innerHTML = `
                    <span class="font-medium text-green-600">
                        <i class="fas fa-check-circle mr-2"></i>
                        ${input.files[0].name}
                    </span>
                    <button onclick="clearFileInput('${input.id}')" class="text-red-500 hover:text-red-700 ml-4">
                        <i class="fas fa-times"></i>
                    </button>
                `;
            } else {
                fileNameDisplay.textContent = '{{ __("لم يتم اختيار ملف") }}';
            }
        }

        function clearFileInput(inputId) {
            const input = document.getElementById(inputId);
            input.value = '';
            document.getElementById('file-name-display').textContent = '{{ __("لم يتم اختيار ملف") }}';
        }
    </script>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Alpine.js for animations -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-app-layout>