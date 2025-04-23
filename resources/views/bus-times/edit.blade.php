<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Bus Schedule') }}
            </h2>
            <a href="{{ route('bus-times.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                {{ __('Back to Schedules') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('bus-times.update', $busTime) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Location -->
                            <div>
                                <label for="location_id" class="block text-sm font-medium text-gray-700">Location</label>
                                <select name="location_id" id="location_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    required>
                                    <option value="">Select location</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('location_id', $busTime->location_id) == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('location_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Route -->
                            <div>
                                <label for="route" class="block text-sm font-medium text-gray-700">Route</label>
                                <input type="text" name="route" id="route" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    value="{{ old('route', $busTime->route) }}" required>
                                @error('route')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Departure Time -->
                            <div>
                                <label for="departure_time" class="block text-sm font-medium text-gray-700">Departure Time</label>
                                <input type="time" name="departure_time" id="departure_time" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    value="{{ old('departure_time', $busTime->formatted_departure_time) }}" required>
                                @error('departure_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Arrival Time -->
                            <div>
                                <label for="arrival_time" class="block text-sm font-medium text-gray-700">Arrival Time</label>
                                <input type="time" name="arrival_time" id="arrival_time" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    value="{{ old('arrival_time', $busTime->formatted_arrival_time) }}" required>
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
                                    <option value="daily" {{ old('frequency', $busTime->frequency) == 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="weekdays" {{ old('frequency', $busTime->frequency) == 'weekdays' ? 'selected' : '' }}>Weekdays Only</option>
                                    <option value="weekends" {{ old('frequency', $busTime->frequency) == 'weekends' ? 'selected' : '' }}>Weekends Only</option>
                                </select>
                                @error('frequency')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" id="notes" rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">{{ old('notes', $busTime->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" 
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Update Schedule
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
