<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Waste Report Details') }}
            </h2>
            <div class="flex space-x-4">
                @can('update', $wasteReport)
                    <a href="{{ route('waste-reports.edit', $wasteReport) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('Edit Report') }}
                    </a>
                @endcan
                <a href="{{ route('waste-reports.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">{{ __('Report Information') }}</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Waste Type') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $wasteReport->waste_type }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Quantity') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $wasteReport->quantity }} {{ $wasteReport->unit }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Location') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $wasteReport->location }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Status') }}</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($wasteReport->status === 'resolved') bg-green-100 text-green-800
                                            @elseif($wasteReport->status === 'in_progress') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $wasteReport->status)) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Reported By') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $wasteReport->user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Reported On') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $wasteReport->created_at->format('F d, Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ __('Last Updated') }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $wasteReport->updated_at->format('F d, Y H:i') }}</dd>
                                </div>
                            </dl>

                            @can('updateStatus', $wasteReport)
                                <div class="mt-6">
                                    <h4 class="text-md font-semibold mb-2">{{ __('Update Status') }}</h4>
                                    <form action="{{ route('waste-reports.update-status', $wasteReport) }}" method="POST" class="flex gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                            <option value="pending" {{ $wasteReport->status === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                            <option value="in_progress" {{ $wasteReport->status === 'in_progress' ? 'selected' : '' }}>{{ __('In Progress') }}</option>
                                            <option value="resolved" {{ $wasteReport->status === 'resolved' ? 'selected' : '' }}>{{ __('Resolved') }}</option>
                                        </select>
                                        <x-primary-button>{{ __('Update') }}</x-primary-button>
                                    </form>
                                </div>
                            @endcan
                        </div>

                        <div>
                            @if($wasteReport->description)
                                <h3 class="text-lg font-semibold mb-4">{{ __('Description') }}</h3>
                                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                    <p class="text-gray-700 whitespace-pre-line">{{ $wasteReport->description }}</p>
                                </div>
                            @endif

                            @if($wasteReport->image_path)
                                <h3 class="text-lg font-semibold mb-4">{{ __('Image') }}</h3>
                                <img src="{{ Storage::url($wasteReport->image_path) }}" 
                                     alt="Waste report image"
                                     class="rounded-lg shadow-md max-w-full h-auto">
                            @endif
                        </div>
                    </div>

                    @can('delete', $wasteReport)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <form action="{{ route('waste-reports.destroy', $wasteReport) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                    onclick="return confirm('{{ __('Are you sure you want to delete this report?') }}')">
                                    {{ __('Delete Report') }}
                                </button>
                            </form>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
