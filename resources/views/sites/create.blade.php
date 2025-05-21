<x-app-layout dir="rtl">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('إضافة موقع جديد') }}
            </h2>
            <a href="{{ route('admin.sites.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                {{ __('العودة إلى القائمة') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.sites.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('اسم الموقع')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="address" :value="__('العنوان')" />
                            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address')" required />
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="latitude" :value="__('خط العرض')" />
                                <x-text-input id="latitude" name="latitude" type="number" step="any" class="mt-1 block w-full" :value="old('latitude')" required />
                                <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="longitude" :value="__('خط الطول')" />
                                <x-text-input id="longitude" name="longitude" type="number" step="any" class="mt-1 block w-full" :value="old('longitude')" required />
                                <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                            </div>
                        </div>

                        <!-- حقول إضافية -->
                        <div>
                            <x-input-label for="description" :value="__('الوصف (اختياري)')" />
                            <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-right">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="contact_phone" :value="__('هاتف الاتصال (اختياري)')" />
                                <x-text-input id="contact_phone" name="contact_phone" type="tel" class="mt-1 block w-full text-right" :value="old('contact_phone')" />
                                <x-input-error :messages="$errors->get('contact_phone')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="contact_email" :value="__('البريد الإلكتروني (اختياري)')" />
                                <x-text-input id="contact_email" name="contact_email" type="email" class="mt-1 block w-full text-right" :value="old('contact_email')" />
                                <x-input-error :messages="$errors->get('contact_email')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button class="bg-green-600 hover:bg-green-700">
                                {{ __('إنشاء الموقع') }}
                            </x-primary-button>
                            <a href="{{ route('admin.sites.index') }}" class="text-gray-600 hover:text-gray-900">
                                {{ __('إلغاء') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // يمكنك إضافة خريطة هنا لاختيار الإحداثيات
            console.log('الصفحة جاهزة لإضافة الخريطة');
        });
    </script>
    @endpush
</x-app-layout>