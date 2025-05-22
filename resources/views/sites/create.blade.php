<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                {{ __('إضافة موقع جديد') }}
            </h2>
            <a href="{{ route('sites.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                {{ __('العودة للقائمة') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6 flex items-center" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ __('يوجد أخطاء في البيانات المدخلة، يرجى المراجعة') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <form action="{{ route('admin.sites.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('اسم الموقع')" class="text-right" />
                            <x-text-input 
                                id="name" 
                                name="name" 
                                type="text" 
                                class="mt-1 block w-full text-right" 
                                :value="old('name')" 
                                required 
                                autofocus 
                                placeholder="أدخل اسم الموقع"
                            />
                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-right" />
                        </div>

                        <div>
                            <x-input-label for="address" :value="__('العنوان')" class="text-right" />
                            <x-text-input 
                                id="address" 
                                name="address" 
                                type="text" 
                                class="mt-1 block w-full text-right" 
                                :value="old('address')" 
                                required 
                                placeholder="أدخل العنوان التفصيلي"
                            />
                            <x-input-error :messages="$errors->get('address')" class="mt-2 text-right" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="latitude" :value="__('خط العرض')" class="text-right" />
                                <x-text-input 
                                    id="latitude" 
                                    name="latitude" 
                                    type="number" 
                                    step="any" 
                                    class="mt-1 block w-full text-right" 
                                    :value="old('latitude')" 
                                    required 
                                    placeholder="مثال: 24.7136"
                                />
                                <x-input-error :messages="$errors->get('latitude')" class="mt-2 text-right" />
                            </div>

                            <div>
                                <x-input-label for="longitude" :value="__('خط الطول')" class="text-right" />
                                <x-text-input 
                                    id="longitude" 
                                    name="longitude" 
                                    type="number" 
                                    step="any" 
                                    class="mt-1 block w-full text-right" 
                                    :value="old('longitude')" 
                                    required 
                                    placeholder="مثال: 46.6753"
                                />
                                <x-input-error :messages="$errors->get('longitude')" class="mt-2 text-right" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4 justify-end">
                            <a href="{{ route('sites.index') }}" class="text-gray-600 hover:text-gray-900 px-4 py-2">
                                {{ __('إلغاء') }}
                            </a>
                            <x-primary-button class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                {{ __('حفظ الموقع') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="text-lg font-medium text-blue-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    {{ __('كيف تحصل على الإحداثيات؟') }}
                </h3>
                <ol class="mt-2 text-sm text-blue-700 list-decimal list-inside space-y-1">
                    <li>{{ __('اذهب إلى موقع الموقع على خرائط جوجل') }}</li>
                    <li>{{ __('انقر بزر الماوس الأيمن على الموقع المحدد') }}</li>
                    <li>{{ __('اختر "ماذا يوجد هنا؟" من القائمة') }}</li>
                    <li>{{ __('ستظهر الإحداثيات في مربع البحث أسفل الخريطة') }}</li>
                </ol>
                <div class="mt-3 text-xs text-blue-600">
                    {{ __('ملاحظة: تأكد من استخدام التنسيق الصحيح (مثال: 24.7136, 46.6753)') }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>