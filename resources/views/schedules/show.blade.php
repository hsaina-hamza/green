<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Schedule Details') }}
            </h2>
            <div class="flex space-x-4">
                @can('update', $schedule)
                    <a href="{{ route('schedules.edit', $schedule) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('Edit Schedule') }}
                    </a>
                @endcan
                <a href="{{ route('schedules.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Schedule Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">{{ __('Collection Details') }}</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Site') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <a href="{{ route('sites.show', $schedule->site) }}" class="text-blue-600 hover:text-blue-900">
                                            {{ $schedule->site->name }}
                                        </a>
                                    </dd>
                                    <dd class="text-sm text-gray-500">{{ $schedule->site->address }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Truck Number') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $schedule->truck_number }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Collection Time') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $schedule->scheduled_time->format('M d, Y H:i') }}</dd>
                                    <dd class="text-sm text-gray-500">{{ $schedule->scheduled_time->diffForHumans() }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Frequency') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($schedule->frequency) }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4">{{ __('Additional Information') }}</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Status') }}</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($schedule->scheduled_time->isFuture()) bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $schedule->scheduled_time->isFuture() ? 'Upcoming' : 'Past' }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Created') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $schedule->created_at->format('M d, Y H:i') }}</dd>
                                    <dd class="text-sm text-gray-500">{{ $schedule->created_at->diffForHumans() }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Last Updated') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $schedule->updated_at->format('M d, Y H:i') }}</dd>
                                    <dd class="text-sm text-gray-500">{{ $schedule->updated_at->diffForHumans() }}</dd>
                                </div>
                                @if($schedule->notes)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">{{ __('Notes') }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $schedule->notes }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    @can('delete', $schedule)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <form action="{{ route('schedules.destroy', $schedule) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <x-danger-button onclick="return confirm('Are you sure you want to delete this schedule?')">
                                    {{ __('Delete Schedule') }}
                                </x-danger-button>
                            </form>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
