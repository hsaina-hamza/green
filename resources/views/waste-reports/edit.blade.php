<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <i class="fas fa-trash-alt ml-2 text-green-500"></i>
                {{ __('تعديل بلاغ النفايات') }}
            </h2>
            <a href="{{ route('waste-reports.show', $wasteReport) }}" 
               class="flex items-center bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg">
                <i class="fas fa-arrow-left ml-2"></i>
                {{ __('العودة للتفاصيل') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg" role="alert">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle ml-2"></i>
                                <strong class="font-bold">{{ __('تنبيه!') }}</strong>
                            </div>
                            <span class="block mt-2">{{ __('يوجد مشاكل في البيانات المدخلة') }}</span>
                            <ul class="mt-3 list-disc pr-5 space-y-1 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('waste-reports.update', $wasteReport) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="waste_type" :value="__('نوع النفايات')" />
                            <x-text-input id="waste_type" name="waste_type" type="text" class="mt-1 block w-full text-right" 
                                :value="old('waste_type', $wasteReport->waste_type)" required />
                            <x-input-error :messages="$errors->get('waste_type')" class="mt-2 text-right" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="quantity" :value="__('الكمية')" />
                                <x-text-input id="quantity" name="quantity" type="number" step="0.01" class="mt-1 block w-full text-right" 
                                    :value="old('quantity', $wasteReport->quantity)" required />
                                <x-input-error :messages="$errors->get('quantity')" class="mt-2 text-right" />
                            </div>

                            <div>
                                <x-input-label for="unit" :value="__('الوحدة')" />
                                <select id="unit" name="unit" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-right" required>
                                    <option value="">{{ __('اختر وحدة القياس') }}</option>
                                    <option value="kg" {{ old('unit', $wasteReport->unit) == 'kg' ? 'selected' : '' }}>{{ __('كيلوغرام (كغ)') }}</option>
                                    <option value="tons" {{ old('unit', $wasteReport->unit) == 'tons' ? 'selected' : '' }}>{{ __('طن') }}</option>
                                    <option value="liters" {{ old('unit', $wasteReport->unit) == 'liters' ? 'selected' : '' }}>{{ __('لتر') }}</option>
                                    <option value="cubic_meters" {{ old('unit', $wasteReport->unit) == 'cubic_meters' ? 'selected' : '' }}>{{ __('متر مكعب') }}</option>
                                    <option value="pieces" {{ old('unit', $wasteReport->unit) == 'pieces' ? 'selected' : '' }}>{{ __('قطعة') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('unit')" class="mt-2 text-right" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="location" :value="__('الموقع')" />
                            <x-text-input id="location" name="location" type="text" class="mt-1 block w-full text-right" 
                                :value="old('location', $wasteReport->location)" required />
                            <x-input-error :messages="$errors->get('location')" class="mt-2 text-right" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('الوصف')" />
                            <textarea id="description" name="description" rows="3" dir="rtl"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $wasteReport->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2 text-right" />
                        </div>

                        @can('updateStatus', $wasteReport)
                            <div>
                                <x-input-label for="status" :value="__('الحالة')" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-right" required>
                                    <option value="pending" {{ old('status', $wasteReport->status) == 'pending' ? 'selected' : '' }}>{{ __('قيد الانتظار') }}</option>
                                    <option value="in_progress" {{ old('status', $wasteReport->status) == 'in_progress' ? 'selected' : '' }}>{{ __('قيد المعالجة') }}</option>
                                    <option value="resolved" {{ old('status', $wasteReport->status) == 'resolved' ? 'selected' : '' }}>{{ __('تم الحل') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2 text-right" />
                            </div>
                        @endcan

                        <div>
                            <x-input-label for="image" :value="__('الصورة (اتركه فارغاً للحفاظ على الصورة الحالية)')" />
                            @if($wasteReport->image_path)
                                <div class="mt-2 mb-4">
                                    <img src="{{ Storage::url($wasteReport->image_path) }}" alt="صورة بلاغ النفايات الحالية" 
                                        class="max-w-xs rounded-lg shadow-md border border-gray-200">
                                </div>
                            @endif
                            <input type="file" id="image" name="image" 
                                class="mt-1 block w-full text-sm text-gray-500
                                file:ml-4 file:py-2 file:px-4 rtl:file:mr-4 rtl:file:ml-0
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2 text-right" />
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('waste-reports.show', $wasteReport) }}" class="text-gray-600 hover:text-gray-900 flex items-center">
                                <i class="fas fa-times ml-2"></i>
                                {{ __('إلغاء') }}
                            </a>
                            <x-primary-button class="flex items-center bg-green-600 hover:bg-green-700">
                                <i class="fas fa-save ml-2"></i>
                                {{ __('تحديث البلاغ') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    
    <style>
        [dir="rtl"] select {
            background-position: left 0.5rem center;
            padding-right: 0.75rem;
            padding-left: 2.5rem;
        }
        textarea {
            text-align: right;
        }
    </style>
</x-app-layout>