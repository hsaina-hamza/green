<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Garbage Truck Schedule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('garbage-truck-schedules.update', $schedule) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="location_id" :value="__('Location')" />
                            <select id="location_id" name="location_id" required 
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select a location</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" 
                                        {{ (old('location_id', $schedule->location_id) == $location->id) ? 'selected' : '' }}>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('location_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="truck_number" :value="__('Truck Number')" />
                            <x-text-input id="truck_number" name="truck_number" type="text" 
                                        class="mt-1 block w-full" 
                                        :value="old('truck_number', $schedule->truck_number)" required />
                            <x-input-error :messages="$errors->get('truck_number')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="scheduled_time" :value="__('Scheduled Time')" />
                            <x-text-input id="scheduled_time" name="scheduled_time" type="datetime-local" 
                                        class="mt-1 block w-full" 
                                        :value="old('scheduled_time', $schedule->scheduled_time->format('Y-m-d\TH:i'))" required />
                            <x-input-error :messages="$errors->get('scheduled_time')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update Schedule') }}</x-primary-button>
                            <a href="{{ route('garbage-truck-schedules.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
