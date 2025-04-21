<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Waste Report Details') }}
            </h2>
            <div class="flex space-x-4">
                @can('update', $wasteReport)
                    <a href="{{ route('waste-reports.edit', $wasteReport) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('Edit Report') }}
                    </a>
                @endcan
                <a href="{{ route('waste-reports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Report Information</h3>
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="font-medium text-gray-500">Site</dt>
                                    <dd class="mt-1">{{ $wasteReport->site->name }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Waste Type</dt>
                                    <dd class="mt-1">{{ ucfirst($wasteReport->waste_type) }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Description</dt>
                                    <dd class="mt-1">{{ $wasteReport->description }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Reported By</dt>
                                    <dd class="mt-1">{{ $wasteReport->user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Reported On</dt>
                                    <dd class="mt-1">{{ $wasteReport->created_at->format('M d, Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($wasteReport->status === 'completed') bg-green-100 text-green-800
                                            @elseif($wasteReport->status === 'in_progress') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($wasteReport->status) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Assigned Worker</dt>
                                    <dd class="mt-1">{{ $wasteReport->assignedWorker ? $wasteReport->assignedWorker->name : 'Unassigned' }}</dd>
                                </div>
                            </dl>

                            @if(auth()->user()->isAdmin())
                                <div class="mt-6">
                                    <h4 class="text-md font-semibold mb-2">Assign Worker</h4>
                                    <form action="{{ route('waste-reports.assign', $wasteReport) }}" method="POST" class="flex gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="assigned_worker_id" class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                            <option value="">Select Worker</option>
                                            @foreach(\App\Models\User::where('role', 'worker')->get() as $worker)
                                                <option value="{{ $worker->id }}" {{ $wasteReport->assigned_worker_id == $worker->id ? 'selected' : '' }}>
                                                    {{ $worker->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-primary-button>{{ __('Assign') }}</x-primary-button>
                                    </form>
                                </div>
                            @endif

                            @can('updateStatus', $wasteReport)
                                <div class="mt-6">
                                    <h4 class="text-md font-semibold mb-2">Update Status</h4>
                                    <form action="{{ route('waste-reports.update-status', $wasteReport) }}" method="POST" class="flex gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                            @foreach(['pending', 'in_progress', 'completed'] as $status)
                                                <option value="{{ $status }}" {{ $wasteReport->status == $status ? 'selected' : '' }}>
                                                    {{ ucfirst($status) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-primary-button>{{ __('Update') }}</x-primary-button>
                                    </form>
                                </div>
                            @endcan
                        </div>

                        <div>
                            @if($wasteReport->image_path)
                                <h3 class="text-lg font-semibold mb-4">Site Image</h3>
                                <img src="{{ Storage::url($wasteReport->image_path) }}" alt="Waste site image" class="w-full rounded-lg shadow-md">
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Comments</h3>

                    <form action="{{ route('comments.store', $wasteReport) }}" method="POST" class="mb-6">
                        @csrf
                        <div>
                            <textarea
                                name="content"
                                rows="3"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                placeholder="Add a comment..."
                            ></textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>
                        <div class="mt-2">
                            <x-primary-button>{{ __('Post Comment') }}</x-primary-button>
                        </div>
                    </form>

                    <div class="space-y-4">
                        @forelse($wasteReport->comments as $comment)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium">{{ $comment->user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                                    </div>
                                    @can('delete', $comment)
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm" onclick="return confirm('Are you sure you want to delete this comment?')">
                                                Delete
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                                <p class="mt-2">{{ $comment->content }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No comments yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
