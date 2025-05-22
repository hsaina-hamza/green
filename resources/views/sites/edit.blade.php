<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                {{ __('تعديل الموقع') }}
            </h2>
            <a href="{{ route('sites.show', $site) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('العودة للتفاصيل') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6 flex items-center" role="alert">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ __('الرجاء تصحيح الأخطاء في النموذج') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-md sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <form action="{{ route('admin.sites.update', $site) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('اسم الموقع')" class="text-right" />
                            <x-text-input 
                                id="name" 
                                name="name" 
                                type="text" 
                                class="mt-1 block w-full text-right" 
                                :value="old('name', $site->name)" 
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
                                :value="old('address', $site->address)" 
                                required 
                                placeholder="أدخل عنوان الموقع"
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
                                    :value="old('latitude', $site->latitude)" 
                                    required 
                                    placeholder="أدخل خط العرض"
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
                                    :value="old('longitude', $site->longitude)" 
                                    required 
                                    placeholder="أدخل خط الطول"
                                />
                                <x-input-error :messages="$errors->get('longitude')" class="mt-2 text-right" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4 justify-end">
                            <a href="{{ route('sites.show', $site) }}" class="text-gray-600 hover:text-gray-900 px-4 py-2">
                                {{ __('إلغاء') }}
                            </a>
                            <x-primary-button class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ __('حفظ التعديلات') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="text-lg font-medium text-blue-800 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ __('نصائح لإدخال الإحداثيات') }}
                </h3>
                <ul class="mt-2 text-sm text-blue-700 list-disc list-inside">
                    <li>{{ __('استخدم نظام الإحداثيات الجغرافي (WGS84)') }}</li>
                    <li>{{ __('خط العرض: بين -90 و 90') }}</li>
                    <li>{{ __('خط الطول: بين -180 و 180') }}</li>
                    <li>{{ __('يمكنك الحصول على الإحداثيات من خرائط جوجل') }}</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>