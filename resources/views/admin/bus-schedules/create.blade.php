@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">إضافة جدول حافلات جديد</h2>
            <a href="{{ route('bus-schedules.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                رجوع للقائمة
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <form method="POST" action="{{ route('bus-schedules.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="route_number" value="رقم الخط" />
                        <x-text-input id="route_number" name="route_number" type="text" class="mt-1 block w-full" 
                            value="{{ old('route_number') }}" required placeholder="مثال: 123" />
                        <x-input-error class="mt-2" :messages="$errors->get('route_number')" />
                    </div>

                    <div>
                        <x-input-label for="route_name" value="اسم المسار" />
                        <x-text-input id="route_name" name="route_name" type="text" class="mt-1 block w-full" 
                            value="{{ old('route_name') }}" required placeholder="مثال: المحطة المركزية - الحي السكني" />
                        <x-input-error class="mt-2" :messages="$errors->get('route_name')" />
                    </div>

                    <div>
                        <x-input-label for="operating_days" value="أيام العمل" />
                        <div class="mt-2 space-y-2">
                            @foreach(['Monday' => 'الإثنين', 'Tuesday' => 'الثلاثاء', 'Wednesday' => 'الأربعاء', 
                                    'Thursday' => 'الخميس', 'Friday' => 'الجمعة', 'Saturday' => 'السبت', 'Sunday' => 'الأحد'] as $day => $arabicDay)
                                <label class="inline-flex items-center ml-6">
                                    <input type="checkbox" name="operating_days[]" value="{{ $day }}" 
                                        class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500"
                                        {{ in_array($day, old('operating_days', [])) ? 'checked' : '' }}>
                                    <span class="mr-2">{{ $arabicDay }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('operating_days')" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="start_time" value="وقت البداية" />
                            <x-text-input id="start_time" name="start_time" type="time" class="mt-1 block w-full" 
                                value="{{ old('start_time') }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('start_time')" />
                        </div>

                        <div>
                            <x-input-label for="end_time" value="وقت النهاية" />
                            <x-text-input id="end_time" name="end_time" type="time" class="mt-1 block w-full" 
                                value="{{ old('end_time') }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('end_time')" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="frequency" value="تكرار الرحلات (بالدقائق)" />
                        <x-text-input id="frequency" name="frequency" type="number" class="mt-1 block w-full" 
                            value="{{ old('frequency') }}" required placeholder="مثال: 30" min="1" />
                        <x-input-error class="mt-2" :messages="$errors->get('frequency')" />
                    </div>

                    <div>
                        <x-input-label for="notes" value="ملاحظات" />
                        <x-textarea id="notes" name="notes" class="mt-1 block w-full" rows="3" 
                            placeholder="أي معلومات إضافية عن الخط">{{ old('notes') }}</x-textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                    </div>

                    <div class="flex justify-end">
                        <x-primary-button>
                            حفظ الجدول
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
