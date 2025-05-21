<x-app-layout dir="rtl">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ __('إنشاء جدول جديد') }}
            </h2>
            <a href="{{ route('admin.schedules.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                {{ __('العودة للقائمة') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg" role="alert">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <strong class="font-bold">{{ __('تنبيه!') }}</strong>
                            </div>
                            <span class="block mt-2">{{ __('يوجد مشاكل في البيانات المدخلة') }}</span>
                            <ul class="mt-3 list-disc list-inside text-sm pr-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.schedules.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="site_id" :value="__('الموقع')" />
                            <select id="site_id" name="site_id" class="mt-1 block w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-lg shadow-sm text-right" required>
                                <option value="">{{ __('اختر موقع') }}</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>
                                        {{ $site->name }} ({{ $site->address }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('site_id')" class="mt-2 text-right" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="truck_number" :value="__('رقم الشاحنة')" />
                                <x-text-input id="truck_number" name="truck_number" type="text" class="mt-1 block w-full" :value="old('truck_number')" required />
                                <x-input-error :messages="$errors->get('truck_number')" class="mt-2 text-right" />
                            </div>

                            <div>
                                <x-input-label for="scheduled_time" :value="__('تاريخ ووقت الجمع')" />
                                <x-text-input id="scheduled_time" name="scheduled_time" type="datetime-local" class="mt-1 block w-full" :value="old('scheduled_time')" required />
                                <x-input-error :messages="$errors->get('scheduled_time')" class="mt-2 text-right" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="frequency" :value="__('التكرار')" />
                            <select id="frequency" name="frequency" class="mt-1 block w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-lg shadow-sm text-right" required>
                                <option value="once" {{ old('frequency') == 'once' ? 'selected' : '' }}>مرة واحدة</option>
                                <option value="daily" {{ old('frequency') == 'daily' ? 'selected' : '' }}>يومي</option>
                                <option value="weekly" {{ old('frequency') == 'weekly' ? 'selected' : '' }}>أسبوعي</option>
                                <option value="biweekly" {{ old('frequency') == 'biweekly' ? 'selected' : '' }}>كل أسبوعين</option>
                                <option value="monthly" {{ old('frequency') == 'monthly' ? 'selected' : '' }}>شهري</option>
                            </select>
                            <x-input-error :messages="$errors->get('frequency')" class="mt-2 text-right" />
                        </div>

                        <div>
                            <x-input-label for="notes" :value="__('ملاحظات')" />
                            <textarea id="notes" name="notes" rows="3" dir="rtl" class="mt-1 block w-full border-gray-300 focus:border-green-500 focus:ring-green-500 rounded-lg shadow-sm">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2 text-right" />
                        </div>

                        <div class="flex items-center justify-start gap-4 pt-4">
                            <x-primary-button class="flex items-center">
                                {{ __('إنشاء الجدول') }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </x-primary-button>
                            <a href="{{ route('admin.schedules.index') }}" class="text-gray-600 hover:text-gray-900 hover:underline">
                                {{ __('إلغاء') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>