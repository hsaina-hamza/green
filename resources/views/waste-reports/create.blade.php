<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Report a Waste Site') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(session('success'))
                    <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('waste-reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" id="title" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            value="{{ old('title') }}" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            required>{{ old('description') }}</textarea>
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
                                <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>General</option>
                                <option value="recyclable" {{ old('type') == 'recyclable' ? 'selected' : '' }}>Recyclable</option>
                                <option value="hazardous" {{ old('type') == 'hazardous' ? 'selected' : '' }}>Hazardous</option>
                                <option value="organic" {{ old('type') == 'organic' ? 'selected' : '' }}>Organic</option>
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
                                <option value="low" {{ old('urgency_level') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('urgency_level') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('urgency_level') == 'high' ? 'selected' : '' }}>High</option>
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
                                <option value="{{ $site->id }}" {{ old('site_id') == $site->id ? 'selected' : '' }}>
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
                                value="{{ old('estimated_size') }}" min="1">
                            @error('estimated_size')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="location_details" class="block text-sm font-medium text-gray-700">Location Details</label>
                            <input type="text" name="location_details" id="location_details" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                value="{{ old('location_details') }}">
                            @error('location_details')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Map Section -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Location on Map</label>
                        <div id="map" class="h-96 w-full rounded-lg border border-gray-300"></div>
                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                    </div>

                    <div>
                        <label for="image_url" class="block text-sm font-medium text-gray-700">Upload Image</label>
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
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Submit Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('waste-reports.multi-step-create-scripts')
</x-app-layout>
