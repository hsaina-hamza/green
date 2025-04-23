<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Bus Transit Times') }}
            </h2>
            @auth
                @if(auth()->user()->hasAnyRole(['admin', 'worker']))
                    <a href="{{ route('bus-times.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('Add New Schedule') }}
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Location
                                    </th>
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
                                    @auth
                                        @if(auth()->user()->hasAnyRole(['admin', 'worker']))
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        @endif
                                    @endauth
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($busSchedules as $schedule)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $schedule->location->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $schedule->route }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $schedule->departure_time }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $schedule->arrival_time }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $schedule->frequency }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $schedule->notes }}
                                        </td>
                                        @auth
                                            @if(auth()->user()->hasAnyRole(['admin', 'worker']))
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <a href="{{ route('bus-times.edit', $schedule) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                                    <form action="{{ route('bus-times.destroy', $schedule) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this schedule?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            @endif
                                        @endauth
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            No bus schedules available.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
