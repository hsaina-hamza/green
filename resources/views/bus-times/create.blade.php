<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <i class="fas fa-bus mr-2 text-blue-500"></i>
                {{ __('إضافة جدول حافلة جديد') }}
            </h2>
            <a href="{{ route('bus-times.index') }}" class="flex items-center bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('العودة إلى الجداول') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('bus-times.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Location -->
                            <div class="space-y-2">
                                <label for="location_id" class="block text-sm font-medium text-gray-700 flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                    الموقع
                                </label>
                                <select name="location_id" id="location_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
                                    required>
                                    <option value="">اختر الموقع</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('location_id')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Route -->
                            <div class="space-y-2">
                                <label for="route" class="block text-sm font-medium text-gray-700 flex items-center">
                                    <i class="fas fa-route mr-2 text-blue-500"></i>
                                    المسار
                                </label>
                                <input type="text" name="route" id="route" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
                                    value="{{ old('route') }}" required>
                                @error('route')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Departure Time -->
                            <div class="space-y-2">
                                <label for="departure_time" class="block text-sm font-medium text-gray-700 flex items-center">
                                    <i class="far fa-clock mr-2 text-blue-500"></i>
                                    وقت المغادرة
                                </label>
                                <input type="time" name="departure_time" id="departure_time" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
                                    value="{{ old('departure_time') }}" required>
                                @error('departure_time')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Arrival Time -->
                            <div class="space-y-2">
                                <label for="arrival_time" class="block text-sm font-medium text-gray-700 flex items-center">
                                    <i class="far fa-clock mr-2 text-blue-500"></i>
                                    وقت الوصول
                                </label>
                                <input type="time" name="arrival_time" id="arrival_time" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
                                    value="{{ old('arrival_time') }}" required>
                                @error('arrival_time')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Frequency -->
                            <div class="space-y-2">
                                <label for="frequency" class="block text-sm font-medium text-gray-700 flex items-center">
                                    <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                    التكرار
                                </label>
                                <select name="frequency" id="frequency" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border"
                                    required>
                                    <option value="">اختر التكرار</option>
                                    <option value="daily" {{ old('frequency') == 'daily' ? 'selected' : '' }}>يومي</option>
                                    <option value="weekdays" {{ old('frequency') == 'weekdays' ? 'selected' : '' }}>أيام الأسبوع فقط</option>
                                    <option value="weekends" {{ old('frequency') == 'weekends' ? 'selected' : '' }}>عطلات نهاية الأسبوع فقط</option>
                                </select>
                                @error('frequency')
                                    <p class="mt-1 text-sm text-red-600 flex items-center">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="space-y-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-sticky-note mr-2 text-blue-500"></i>
                                ملاحظات
                            </label>
                            <textarea name="notes" id="notes" rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" 
                                class="flex items-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg transition duration-300 shadow-md">
                                <i class="fas fa-plus-circle mr-2"></i>
                                {{ __('إضافة الجدول') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        select, input, textarea {
            transition: all 0.3s ease;
        }
        select:focus, input:focus, textarea:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        .primary-button:hover {
            transform: translateY(-2px);
        }
    </style>
</x-app-layout>