<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
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
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
