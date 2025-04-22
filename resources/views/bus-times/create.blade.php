<x-app-layout>
    <x-slot name="header">
<<<<<<< HEAD
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('إضافة جدول حافلة جديد') }}
        </h2>
    </x-slot>

    <div class="py-12" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('bus-times.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label for="location_id" class="block text-sm font-medium text-gray-700">الموقع</label>
                            <select name="location_id" id="location_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">اختر الموقع</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('location_id')
=======
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Add New Bus Schedule') }}
            </h2>
            <a href="{{ route('bus-times.manage') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                {{ __('Back to Management') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('bus-times.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Neighborhood -->
                            <div>
                                <label for="neighborhood" class="block text-sm font-medium text-gray-700">Neighborhood</label>
                                <input type="text" name="neighborhood" id="neighborhood" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    value="{{ old('neighborhood') }}" required>
                                @error('neighborhood')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Route Name -->
                            <div>
                                <label for="route_name" class="block text-sm font-medium text-gray-700">Route Name</label>
                                <input type="text" name="route_name" id="route_name" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    value="{{ old('route_name') }}" required>
                                @error('route_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Departure Time -->
                            <div>
                                <label for="departure_time" class="block text-sm font-medium text-gray-700">Departure Time</label>
                                <input type="time" name="departure_time" id="departure_time" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    value="{{ old('departure_time') }}" required>
                                @error('departure_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Arrival Time -->
                            <div>
                                <label for="arrival_time" class="block text-sm font-medium text-gray-700">Arrival Time</label>
                                <input type="time" name="arrival_time" id="arrival_time" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    value="{{ old('arrival_time') }}" required>
                                @error('arrival_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Frequency -->
                            <div>
                                <label for="frequency" class="block text-sm font-medium text-gray-700">Frequency</label>
                                <select name="frequency" id="frequency" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    required>
                                    <option value="">Select frequency</option>
                                    @foreach($frequencies as $value => $label)
                                        <option value="{{ $value }}" {{ old('frequency') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('frequency')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="is_active" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="is_active" id="is_active" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    required>
                                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('is_active')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" id="notes" rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">{{ old('notes') }}</textarea>
                            @error('notes')
>>>>>>> 231977c8c8cc7dfc8f6b499ce1a4fff2b8175808
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

<<<<<<< HEAD
                        <div>
                            <label for="route" class="block text-sm font-medium text-gray-700">المسار</label>
                            <input type="text" name="route" id="route" value="{{ old('route') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('route')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="departure_time" class="block text-sm font-medium text-gray-700">وقت المغادرة</label>
                            <input type="time" name="departure_time" id="departure_time" value="{{ old('departure_time') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('departure_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="arrival_time" class="block text-sm font-medium text-gray-700">وقت الوصول</label>
                            <input type="time" name="arrival_time" id="arrival_time" value="{{ old('arrival_time') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('arrival_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="frequency" class="block text-sm font-medium text-gray-700">التكرار</label>
                            <input type="text" name="frequency" id="frequency" value="{{ old('frequency') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('frequency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                حفظ
                            </button>
                            <a href="{{ route('bus-times.index') }}" class="mr-2 text-gray-600 hover:text-gray-900">
                                إلغاء
                            </a>
=======
                        <div class="flex justify-end">
                            <button type="submit" 
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Create Schedule
                            </button>
>>>>>>> 231977c8c8cc7dfc8f6b499ce1a4fff2b8175808
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
