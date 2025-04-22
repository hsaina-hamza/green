<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
<<<<<<< HEAD
                {{ __('Waste Report Details') }}
            </h2>
            <div class="flex space-x-4">
                @can('update', $wasteReport)
                    <a href="{{ route('waste-reports.edit', $wasteReport) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('Edit Report') }}
                    </a>
                @endcan
                <a href="{{ route('waste-reports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
=======
                {{ $wasteReport->title }}
            </h2>
            <div class="flex space-x-4">
                @can('update', $wasteReport)
                    <a href="{{ route('waste-reports.edit', $wasteReport) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ __('Edit Report') }}
                    </a>
                @endcan
                <a href="{{ route('waste-reports.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
>>>>>>> 231977c8c8cc7dfc8f6b499ce1a4fff2b8175808
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
<<<<<<< HEAD
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
=======
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Status and Metadata -->
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <div class="flex items-center space-x-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $wasteReport->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($wasteReport->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                        'bg-green-100 text-green-800') }}">
                                    {{ ucfirst($wasteReport->status) }}
                                </span>
                                <span class="text-gray-600">
                                    Reported by: {{ $wasteReport->user->name }}
                                </span>
                                <span class="text-gray-600">
                                    {{ $wasteReport->created_at->format('M d, Y H:i') }}
                                </span>
                            </div>
                        </div>
                        @if(auth()->user() && (auth()->user()->isAdmin() || auth()->user()->isWorker()))
                            <div class="flex items-center space-x-4">
                                <form action="{{ route('waste-reports.update-status', $wasteReport) }}" method="POST" class="flex items-center">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 mr-2">
                                        <option value="pending" {{ $wasteReport->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ $wasteReport->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $wasteReport->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Update Status
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Report Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Report Details</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Type</label>
                                    <p class="mt-1">{{ ucfirst($wasteReport->type) }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Urgency Level</label>
                                    <p class="mt-1">{{ ucfirst($wasteReport->urgency_level) }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Site</label>
                                    <p class="mt-1">{{ $wasteReport->site->name }}</p>
                                </div>
                                @if($wasteReport->estimated_size)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Estimated Size</label>
                                        <p class="mt-1">{{ $wasteReport->estimated_size }} m³</p>
                                    </div>
                                @endif
                                @if($wasteReport->location_details)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Location Details</label>
                                        <p class="mt-1">{{ $wasteReport->location_details }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">Description</h3>
                            <p class="text-gray-700 whitespace-pre-line">{{ $wasteReport->description }}</p>
                        </div>
                    </div>

                    <!-- Map -->
                    @if($wasteReport->latitude && $wasteReport->longitude)
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold mb-4">Location</h3>
                            <div id="map" class="h-96 w-full rounded-lg border border-gray-300"></div>
                        </div>

                        @push('scripts')
                        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
                        <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
                        <script>
                            const map = L.map('map').setView([{{ $wasteReport->latitude }}, {{ $wasteReport->longitude }}], 13);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                maxZoom: 19,
                                attribution: '© OpenStreetMap contributors'
                            }).addTo(map);
                            L.marker([{{ $wasteReport->latitude }}, {{ $wasteReport->longitude }}]).addTo(map);
                        </script>
                        @endpush
                    @endif

                    <!-- Comments Section -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4">Comments</h3>
                        
                        @auth
                            <form action="{{ route('comments.store', $wasteReport) }}" method="POST" class="mb-6">
                                @csrf
                                <div>
                                    <label for="content" class="sr-only">Comment</label>
                                    <textarea id="content" name="content" rows="3" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                        placeholder="Add a comment..."></textarea>
                                </div>
                                <div class="mt-3 flex justify-end">
                                    <button type="submit" 
                                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Post Comment
                                    </button>
                                </div>
                            </form>
                        @endauth

                        <div class="space-y-6">
                            @forelse($wasteReport->comments as $comment)
                                <div class="flex space-x-4">
                                    <div class="flex-1">
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div class="flex justify-between items-start">
                                                <div class="text-sm">
                                                    <span class="font-medium text-gray-900">{{ $comment->user->name }}</span>
                                                    <span class="text-gray-500 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                                                </div>
                                                @can('delete', $comment)
                                                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="flex">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                            <p class="text-gray-700 mt-2">{{ $comment->content }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-600 text-center">No comments yet.</p>
                            @endforelse
                        </div>
>>>>>>> 231977c8c8cc7dfc8f6b499ce1a4fff2b8175808
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
