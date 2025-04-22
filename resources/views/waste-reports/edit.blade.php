<x-app-layout>
    <x-slot name="header">
<<<<<<< HEAD
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Waste Report') }}
            </h2>
            <a href="{{ route('waste-reports.show', $wasteReport) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                {{ __('Back to Details') }}
            </a>
        </div>
=======
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Waste Report') }}
        </h2>
>>>>>>> 231977c8c8cc7dfc8f6b499ce1a4fff2b8175808
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
<<<<<<< HEAD
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('waste-reports.update', $wasteReport) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="site_id" :value="__('Site')" />
                            <select id="site_id" name="site_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                                <option value="">Select a site</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->id }}" {{ old('site_id', $wasteReport->site_id) == $site->id ? 'selected' : '' }}>
                                        {{ $site->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('site_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="waste_type" :value="__('Waste Type')" />
                            <select id="waste_type" name="waste_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                                <option value="">Select waste type</option>
                                @foreach(['household', 'construction', 'green_waste', 'electronic', 'hazardous', 'recyclable'] as $type)
                                    <option value="{{ $type }}" {{ old('waste_type', $wasteReport->waste_type) == $type ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('waste_type')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea
                                id="description"
                                name="description"
                                rows="4"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500"
                                placeholder="Describe the waste situation..."
                            >{{ old('description', $wasteReport->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        @can('updateStatus', $wasteReport)
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                                    @foreach(['pending', 'in_progress', 'completed'] as $status)
                                        <option value="{{ $status }}" {{ old('status', $wasteReport->status) == $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        @endcan

                        @can('assign', $wasteReport)
                            <div>
                                <x-input-label for="assigned_worker_id" :value="__('Assigned Worker')" />
                                <select id="assigned_worker_id" name="assigned_worker_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                                    <option value="">Select a worker</option>
                                    @foreach($workers as $worker)
                                        <option value="{{ $worker->id }}" {{ old('assigned_worker_id', $wasteReport->assigned_worker_id) == $worker->id ? 'selected' : '' }}>
                                            {{ $worker->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('assigned_worker_id')" class="mt-2" />
                            </div>
                        @endcan

                        <div>
                            <x-input-label for="image" :value="__('Image')" />
                            @if($wasteReport->image_path)
                                <div class="mt-2 mb-4">
                                    <img src="{{ Storage::url($wasteReport->image_path) }}" alt="Current waste site image" class="w-48 h-48 object-cover rounded-lg">
                                    <p class="mt-1 text-sm text-gray-500">Current image</p>
                                </div>
                            @endif
                            <input
                                type="file"
                                id="image"
                                name="image"
                                accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-green-50 file:text-green-700
                                    hover:file:bg-green-100"
                            />
                            <p class="mt-1 text-sm text-gray-500">
                                Upload a new photo to replace the current one (max 2MB)
                            </p>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update Report') }}</x-primary-button>
                            <a href="{{ route('waste-reports.show', $wasteReport) }}" class="text-gray-600 hover:text-gray-900">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
=======
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('waste-reports.update', $wasteReport) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" id="title" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            value="{{ old('title', $wasteReport->title) }}" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            required>{{ old('description', $wasteReport->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                            <select name="type" id="type" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                required>
                                <option value="">Select type</option>
                                <option value="general" {{ old('type', $wasteReport->type) == 'general' ? 'selected' : '' }}>General</option>
                                <option value="recyclable" {{ old('type', $wasteReport->type) == 'recyclable' ? 'selected' : '' }}>Recyclable</option>
                                <option value="hazardous" {{ old('type', $wasteReport->type) == 'hazardous' ? 'selected' : '' }}>Hazardous</option>
                                <option value="organic" {{ old('type', $wasteReport->type) == 'organic' ? 'selected' : '' }}>Organic</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="urgency_level" class="block text-sm font-medium text-gray-700">Urgency Level</label>
                            <select name="urgency_level" id="urgency_level" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                required>
                                <option value="">Select urgency</option>
                                <option value="low" {{ old('urgency_level', $wasteReport->urgency_level) == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('urgency_level', $wasteReport->urgency_level) == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('urgency_level', $wasteReport->urgency_level) == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                            @error('urgency_level')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="site_id" class="block text-sm font-medium text-gray-700">Site</label>
                        <select name="site_id" id="site_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            required>
                            <option value="">Select site</option>
                            @foreach($sites as $site)
                                <option value="{{ $site->id }}" {{ old('site_id', $wasteReport->site_id) == $site->id ? 'selected' : '' }}>
                                    {{ $site->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('site_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="estimated_size" class="block text-sm font-medium text-gray-700">Estimated Size (m³)</label>
                            <input type="number" name="estimated_size" id="estimated_size" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                value="{{ old('estimated_size', $wasteReport->estimated_size) }}" min="1">
                            @error('estimated_size')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="location_details" class="block text-sm font-medium text-gray-700">Location Details</label>
                            <input type="text" name="location_details" id="location_details" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                value="{{ old('location_details', $wasteReport->location_details) }}">
                            @error('location_details')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Map Section -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Location on Map</label>
                        <div id="map" class="h-96 w-full rounded-lg border border-gray-300"></div>
                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $wasteReport->latitude) }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $wasteReport->longitude) }}">
                    </div>

                    <div>
                        <label for="image_url" class="block text-sm font-medium text-gray-700">Upload New Image</label>
                        <input type="file" name="image_url" id="image_url" 
                            class="mt-1 block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-green-50 file:text-green-700
                            hover:file:bg-green-100">
                        @error('image_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if($wasteReport->image_url)
                            <p class="mt-2 text-sm text-gray-600">Current image: {{ basename($wasteReport->image_url) }}</p>
                        @endif
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('waste-reports.show', $wasteReport) }}" 
                            class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Cancel
                        </a>
                        <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Update Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('waste-reports.multi-step-create-scripts')
>>>>>>> 231977c8c8cc7dfc8f6b499ce1a4fff2b8175808
</x-app-layout>
