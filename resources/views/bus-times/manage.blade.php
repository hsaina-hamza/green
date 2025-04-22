<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Bus Transit Times') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('bus-times.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Add New Schedule') }}
                </a>
                <a href="{{ route('bus-times.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    {{ __('View Public Schedule') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @forelse($schedules as $neighborhood => $neighborhoodSchedules)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4 bg-gray-100 p-3 rounded">
                                {{ $neighborhood }}
                            </h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Route
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Departure
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Arrival
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Frequency
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Notes
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($neighborhoodSchedules as $schedule)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {{ $schedule->route_name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $schedule->formatted_departure_time }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $schedule->formatted_arrival_time }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $schedule->frequency_label }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500">
                                                    {{ $schedule->notes }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $schedule->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $schedule->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                        <a href="{{ route('bus-times.edit', $schedule) }}" 
                                                           class="text-indigo-600 hover:text-indigo-900">
                                                            Edit
                                                        </a>
                                                        <form action="{{ route('bus-times.toggle-status', $schedule) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                                                {{ $schedule->is_active ? 'Deactivate' : 'Activate' }}
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('bus-times.destroy', $schedule) }}" method="POST" class="inline"
                                                              onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <p class="text-gray-500 mb-4">No bus schedules available.</p>
                            <a href="{{ route('bus-times.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded">
                                Create First Schedule
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
