<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Waste Report') }}
            </h2>
            <a href="{{ route('waste-reports.show', $wasteReport) }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                {{ __('Back to Details') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">{{ __('Whoops!') }}</strong>
                            <span class="block sm:inline">{{ __('There were some problems with your input.') }}</span>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('waste-reports.update', $wasteReport) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="waste_type" :value="__('Waste Type')" />
                            <x-text-input id="waste_type" name="waste_type" type="text" class="mt-1 block w-full" 
                                :value="old('waste_type', $wasteReport->waste_type)" required />
                            <x-input-error :messages="$errors->get('waste_type')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="quantity" :value="__('Quantity')" />
                                <x-text-input id="quantity" name="quantity" type="number" step="0.01" class="mt-1 block w-full" 
                                    :value="old('quantity', $wasteReport->quantity)" required />
                                <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="unit" :value="__('Unit')" />
                                <select id="unit" name="unit" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">{{ __('Select a unit') }}</option>
                                    <option value="kg" {{ old('unit', $wasteReport->unit) == 'kg' ? 'selected' : '' }}>{{ __('Kilograms (kg)') }}</option>
                                    <option value="tons" {{ old('unit', $wasteReport->unit) == 'tons' ? 'selected' : '' }}>{{ __('Tons') }}</option>
                                    <option value="liters" {{ old('unit', $wasteReport->unit) == 'liters' ? 'selected' : '' }}>{{ __('Liters') }}</option>
                                    <option value="cubic_meters" {{ old('unit', $wasteReport->unit) == 'cubic_meters' ? 'selected' : '' }}>{{ __('Cubic Meters') }}</option>
                                    <option value="pieces" {{ old('unit', $wasteReport->unit) == 'pieces' ? 'selected' : '' }}>{{ __('Pieces') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="location" :value="__('Location')" />
                            <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" 
                                :value="old('location', $wasteReport->location)" required />
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="3" 
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $wasteReport->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        @can('updateStatus', $wasteReport)
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="pending" {{ old('status', $wasteReport->status) == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                    <option value="in_progress" {{ old('status', $wasteReport->status) == 'in_progress' ? 'selected' : '' }}>{{ __('In Progress') }}</option>
                                    <option value="resolved" {{ old('status', $wasteReport->status) == 'resolved' ? 'selected' : '' }}>{{ __('Resolved') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        @endcan

                        <div>
                            <x-input-label for="image" :value="__('Image (leave empty to keep current image)')" />
                            @if($wasteReport->image_path)
                                <div class="mt-2 mb-4">
                                    <img src="{{ Storage::url($wasteReport->image_path) }}" alt="Current waste report image" 
                                        class="max-w-xs rounded-lg shadow-md">
                                </div>
                            @endif
                            <input type="file" id="image" name="image" 
                                class="mt-1 block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('waste-reports.show', $wasteReport) }}" class="text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
                            <x-primary-button>{{ __('Update Report') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
