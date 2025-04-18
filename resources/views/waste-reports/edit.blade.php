<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Waste Report') }}
            </h2>
            <a href="{{ route('waste-reports.show', $wasteReport) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                {{ __('Back to Details') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
</x-app-layout>
