<x-app-layout dir="rtl">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('إبلاغ عن نفايات') }}
            </h2>
            <a href="{{ route('waste-reports.index') }}" 
               class="text-white font-bold py-2 px-4 rounded
                    @if(Auth::user()->role === 'admin') bg-purple-600 hover:bg-purple-700
                    @elseif(Auth::user()->role === 'worker') bg-blue-600 hover:bg-blue-700
                    @else bg-green-600 hover:bg-green-700 @endif transition-colors duration-300">
                {{ __('العودة إلى القائمة') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-lg sm:rounded-xl border-2
                @if(Auth::user()->role === 'admin') bg-purple-50 border-purple-300
                @elseif(Auth::user()->role === 'worker') bg-blue-50 border-blue-300
                @else bg-green-50 border-green-300 @endif">
                <div class="p-8 text-gray-900">
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-2 border-red-200 text-red-700 px-6 py-4 rounded-lg relative" role="alert">
                            <strong class="font-bold">{{ __('عذراً!') }}</strong>
                            <span class="block sm:inline">{{ __('هناك بعض المشاكل في المدخلات الخاصة بك.') }}</span>
                            <ul class="mt-2 list-disc list-inside text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('waste-reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label for="waste_type_id" class="block font-medium text-lg
                                    @if(Auth::user()->role === 'admin') text-purple-800
                                    @elseif(Auth::user()->role === 'worker') text-blue-800
                                    @else text-green-800 @endif">
                                    {{ __('نوع النفايات') }} <span class="text-red-500">*</span>
                                </label>
                                <select id="waste_type_id" name="waste_type_id" 
                                    class="mt-2 block w-full rounded-lg shadow-sm text-lg py-3 px-4
                                    @if(Auth::user()->role === 'admin') border-2 border-purple-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200
                                    @elseif(Auth::user()->role === 'worker') border-2 border-blue-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                                    @else border-2 border-green-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 @endif"
                                    required autofocus>
                                    <option value="">{{ __('اختر نوع النفايات') }}</option>
                                    @foreach($wasteTypes as $wasteType)
                                        <option value="{{ $wasteType->id }}" {{ old('waste_type_id') == $wasteType->id ? 'selected' : '' }}>
                                            {{ $wasteType->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('waste_type_id')" class="mt-2 text-lg" />
                            </div>

                            <div>
                                <label for="location_id" class="block font-medium text-lg
                                    @if(Auth::user()->role === 'admin') text-purple-800
                                    @elseif(Auth::user()->role === 'worker') text-blue-800
                                    @else text-green-800 @endif">
                                    {{ __('الموقع') }} <span class="text-red-500">*</span>
                                </label>
                                <select id="location_id" name="location_id" 
                                    class="mt-2 block w-full rounded-lg shadow-sm text-lg py-3 px-4
                                    @if(Auth::user()->role === 'admin') border-2 border-purple-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200
                                    @elseif(Auth::user()->role === 'worker') border-2 border-blue-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                                    @else border-2 border-green-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 @endif"
                                    required>
                                    <option value="">{{ __('اختر الموقع') }}</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('location_id')" class="mt-2 text-lg" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label for="quantity" class="block font-medium text-lg
                                    @if(Auth::user()->role === 'admin') text-purple-800
                                    @elseif(Auth::user()->role === 'worker') text-blue-800
                                    @else text-green-800 @endif">
                                    {{ __('الكمية') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="number" step="0.01" id="quantity" name="quantity"
                                    class="mt-2 block w-full rounded-lg shadow-sm text-lg py-3 px-4
                                    @if(Auth::user()->role === 'admin') border-2 border-purple-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200
                                    @elseif(Auth::user()->role === 'worker') border-2 border-blue-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                                    @else border-2 border-green-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 @endif"
                                    value="{{ old('quantity') }}" required />
                                <x-input-error :messages="$errors->get('quantity')" class="mt-2 text-lg" />
                            </div>

                            <div>
                                <label for="unit" class="block font-medium text-lg
                                    @if(Auth::user()->role === 'admin') text-purple-800
                                    @elseif(Auth::user()->role === 'worker') text-blue-800
                                    @else text-green-800 @endif">
                                    {{ __('الوحدة') }} <span class="text-red-500">*</span>
                                </label>
                                <select id="unit" name="unit" 
                                    class="mt-2 block w-full rounded-lg shadow-sm text-lg py-3 px-4
                                    @if(Auth::user()->role === 'admin') border-2 border-purple-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200
                                    @elseif(Auth::user()->role === 'worker') border-2 border-blue-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                                    @else border-2 border-green-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 @endif"
                                    required>
                                    <option value="">{{ __('اختر الوحدة') }}</option>
                                    <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>{{ __('كيلوغرام (كغ)') }}</option>
                                    <option value="tons" {{ old('unit') == 'tons' ? 'selected' : '' }}>{{ __('أطنان') }}</option>
                                    <option value="liters" {{ old('unit') == 'liters' ? 'selected' : '' }}>{{ __('لترات') }}</option>
                                    <option value="cubic_meters" {{ old('unit') == 'cubic_meters' ? 'selected' : '' }}>{{ __('أمتار مكعبة') }}</option>
                                    <option value="pieces" {{ old('unit') == 'pieces' ? 'selected' : '' }}>{{ __('قطع') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('unit')" class="mt-2 text-lg" />
                            </div>
                        </div>

                        <div>
                            <label for="urgency_level" class="block font-medium text-lg
                                @if(Auth::user()->role === 'admin') text-purple-800
                                @elseif(Auth::user()->role === 'worker') text-blue-800
                                @else text-green-800 @endif">
                                {{ __('مستوى الخطورة') }} <span class="text-red-500">*</span>
                            </label>
                            <select id="urgency_level" name="urgency_level" 
                                class="mt-2 block w-full rounded-lg shadow-sm text-lg py-3 px-4
                                @if(Auth::user()->role === 'admin') border-2 border-purple-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200
                                @elseif(Auth::user()->role === 'worker') border-2 border-blue-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                                @else border-2 border-green-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 @endif"
                                required>
                                <option value="">{{ __('اختر مستوى الخطورة') }}</option>
                                <option value="low" {{ old('urgency_level') == 'low' ? 'selected' : '' }}>{{ __('منخفض') }}</option>
                                <option value="medium" {{ old('urgency_level') == 'medium' ? 'selected' : '' }}>{{ __('متوسط') }}</option>
                                <option value="high" {{ old('urgency_level') == 'high' ? 'selected' : '' }}>{{ __('مرتفع') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('urgency_level')" class="mt-2 text-lg" />
                        </div>

                        <div>
                            <label for="description" class="block font-medium text-lg
                                @if(Auth::user()->role === 'admin') text-purple-800
                                @elseif(Auth::user()->role === 'worker') text-blue-800
                                @else text-green-800 @endif">
                                {{ __('الوصف') }}
                            </label>
                            <textarea id="description" name="description" rows="4"
                                class="mt-2 block w-full rounded-lg shadow-sm text-lg py-3 px-4
                                @if(Auth::user()->role === 'admin') border-2 border-purple-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200
                                @elseif(Auth::user()->role === 'worker') border-2 border-blue-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200
                                @else border-2 border-green-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 @endif">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2 text-lg" />
                        </div>

                        <div>
                            <label for="image" class="block font-medium text-lg
                                @if(Auth::user()->role === 'admin') text-purple-800
                                @elseif(Auth::user()->role === 'worker') text-blue-800
                                @else text-green-800 @endif">
                                {{ __('صورة (اختياري)') }}
                            </label>
                            <div class="mt-2 flex items-center">
                                <label for="image" class="cursor-pointer flex items-center">
                                    <span class="inline-flex items-center px-6 py-3 rounded-lg shadow-sm text-lg font-medium
                                        @if(Auth::user()->role === 'admin') bg-purple-100 text-purple-700 hover:bg-purple-200
                                        @elseif(Auth::user()->role === 'worker') bg-blue-100 text-blue-700 hover:bg-blue-200
                                        @else bg-green-100 text-green-700 hover:bg-green-200 @endif
                                        transition-colors duration-300">
                                        {{ __('اختر ملف') }}
                                    </span>
                                    <span id="file-name" class="mr-4 text-gray-600 text-lg"></span>
                                </label>
                                <input type="file" id="image" name="image" class="hidden" onchange="document.getElementById('file-name').textContent = this.files[0]?.name || '{{ __('لم يتم اختيار ملف') }}'" />
                            </div>
                            <x-input-error :messages="$errors->get('image')" class="mt-2 text-lg" />
                        </div>

                        <div class="flex items-center justify-start gap-6 pt-6">
                            <button type="submit" class="inline-flex items-center px-8 py-3 border border-transparent rounded-lg font-bold text-lg text-white uppercase tracking-wider focus:outline-none focus:ring-4 focus:ring-opacity-50 transition ease-in-out duration-300
                                @if(Auth::user()->role === 'admin') bg-purple-600 hover:bg-purple-700 focus:ring-purple-300
                                @elseif(Auth::user()->role === 'worker') bg-blue-600 hover:bg-blue-700 focus:ring-blue-300
                                @else bg-green-600 hover:bg-green-700 focus:ring-green-300 @endif">
                                {{ __('إرسال التقرير') }}
                            </button>
                            <a href="{{ route('waste-reports.index') }}" class="text-lg text-gray-600 hover:text-gray-800 font-medium transition-colors duration-300">
                                {{ __('إلغاء') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Display selected file name
        document.getElementById('image').addEventListener('change', function() {
            const fileName = this.files[0] ? this.files[0].name : '{{ __("لم يتم اختيار ملف") }}';
            document.getElementById('file-name').textContent = fileName;
        });
    </script>
</x-app-layout>