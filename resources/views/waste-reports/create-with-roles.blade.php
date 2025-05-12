<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Report Waste') }}
            </h2>
            <a href="{{ route('waste-reports.index') }}" 
               class="text-white font-bold py-2 px-4 rounded
                    @if(Auth::user()->role === 'admin') bg-purple-500 hover:bg-purple-600
                    @elseif(Auth::user()->role === 'worker') bg-blue-500 hover:bg-blue-600
                    @else bg-green-500 hover:bg-green-600 @endif">
                {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg border
                @if(Auth::user()->role === 'admin') bg-purple-50 border-purple-200
                @elseif(Auth::user()->role === 'worker') bg-blue-50 border-blue-200
                @else bg-green-50 border-green-200 @endif">
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

                    <form action="{{ route('waste-reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="waste_type_id" class="block font-medium text-sm
                                    @if(Auth::user()->role === 'admin') text-purple-700
                                    @elseif(Auth::user()->role === 'worker') text-blue-700
                                    @else text-green-700 @endif">
                                    {{ __('Waste Type') }}
                                </label>
                                <select id="waste_type_id" name="waste_type_id" 
                                    class="mt-1 block w-full rounded-md shadow-sm
                                    @if(Auth::user()->role === 'admin') border-purple-300 focus:border-purple-500 focus:ring-purple-500
                                    @elseif(Auth::user()->role === 'worker') border-blue-300 focus:border-blue-500 focus:ring-blue-500
                                    @else border-green-300 focus:border-green-500 focus:ring-green-500 @endif"
                                    required autofocus>
                                    <option value="">{{ __('Select a waste type') }}</option>
                                    @foreach($wasteTypes as $wasteType)
                                        <option value="{{ $wasteType->id }}" {{ old('waste_type_id') == $wasteType->id ? 'selected' : '' }}>
                                            {{ $wasteType->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('waste_type_id')" class="mt-2" />
                            </div>

                            <div>
                                <label for="location_id" class="block font-medium text-sm
                                    @if(Auth::user()->role === 'admin') text-purple-700
                                    @elseif(Auth::user()->role === 'worker') text-blue-700
                                    @else text-green-700 @endif">
                                    {{ __('Location') }}
                                </label>
                                <select id="location_id" name="location_id" 
                                    class="mt-1 block w-full rounded-md shadow-sm
                                    @if(Auth::user()->role === 'admin') border-purple-300 focus:border-purple-500 focus:ring-purple-500
                                    @elseif(Auth::user()->role === 'worker') border-blue-300 focus:border-blue-500 focus:ring-blue-500
                                    @else border-green-300 focus:border-green-500 focus:ring-green-500 @endif"
                                    required>
                                    <option value="">{{ __('Select a location') }}</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('location_id')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="quantity" class="block font-medium text-sm
                                    @if(Auth::user()->role === 'admin') text-purple-700
                                    @elseif(Auth::user()->role === 'worker') text-blue-700
                                    @else text-green-700 @endif">
                                    {{ __('Quantity') }}
                                </label>
                                <input type="number" step="0.01" id="quantity" name="quantity"
                                    class="mt-1 block w-full rounded-md shadow-sm
                                    @if(Auth::user()->role === 'admin') border-purple-300 focus:border-purple-500 focus:ring-purple-500
                                    @elseif(Auth::user()->role === 'worker') border-blue-300 focus:border-blue-500 focus:ring-blue-500
                                    @else border-green-300 focus:border-green-500 focus:ring-green-500 @endif"
                                    value="{{ old('quantity') }}" required />
                                <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                            </div>

                            <div>
                                <label for="unit" class="block font-medium text-sm
                                    @if(Auth::user()->role === 'admin') text-purple-700
                                    @elseif(Auth::user()->role === 'worker') text-blue-700
                                    @else text-green-700 @endif">
                                    {{ __('Unit') }}
                                </label>
                                <select id="unit" name="unit" 
                                    class="mt-1 block w-full rounded-md shadow-sm
                                    @if(Auth::user()->role === 'admin') border-purple-300 focus:border-purple-500 focus:ring-purple-500
                                    @elseif(Auth::user()->role === 'worker') border-blue-300 focus:border-blue-500 focus:ring-blue-500
                                    @else border-green-300 focus:border-green-500 focus:ring-green-500 @endif"
                                    required>
                                    <option value="">{{ __('Select a unit') }}</option>
                                    <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>{{ __('Kilograms (kg)') }}</option>
                                    <option value="tons" {{ old('unit') == 'tons' ? 'selected' : '' }}>{{ __('Tons') }}</option>
                                    <option value="liters" {{ old('unit') == 'liters' ? 'selected' : '' }}>{{ __('Liters') }}</option>
                                    <option value="cubic_meters" {{ old('unit') == 'cubic_meters' ? 'selected' : '' }}>{{ __('Cubic Meters') }}</option>
                                    <option value="pieces" {{ old('unit') == 'pieces' ? 'selected' : '' }}>{{ __('Pieces') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('unit')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block font-medium text-sm
                                @if(Auth::user()->role === 'admin') text-purple-700
                                @elseif(Auth::user()->role === 'worker') text-blue-700
                                @else text-green-700 @endif">
                                {{ __('Description') }}
                            </label>
                            <textarea id="description" name="description" rows="3"
                                class="mt-1 block w-full rounded-md shadow-sm
                                @if(Auth::user()->role === 'admin') border-purple-300 focus:border-purple-500 focus:ring-purple-500
                                @elseif(Auth::user()->role === 'worker') border-blue-300 focus:border-blue-500 focus:ring-blue-500
                                @else border-green-300 focus:border-green-500 focus:ring-green-500 @endif">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <label for="image" class="block font-medium text-sm
                                @if(Auth::user()->role === 'admin') text-purple-700
                                @elseif(Auth::user()->role === 'worker') text-blue-700
                                @else text-green-700 @endif">
                                {{ __('Image (optional)') }}
                            </label>
                            <input type="file" id="image" name="image"
                                class="mt-1 block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                @if(Auth::user()->role === 'admin') file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100
                                @elseif(Auth::user()->role === 'worker') file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100
                                @else file:bg-green-50 file:text-green-700 hover:file:bg-green-100 @endif" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('waste-reports.index') }}" class="text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150
                                @if(Auth::user()->role === 'admin') bg-purple-500 hover:bg-purple-600 focus:ring-purple-500
                                @elseif(Auth::user()->role === 'worker') bg-blue-500 hover:bg-blue-600 focus:ring-blue-500
                                @else bg-green-500 hover:bg-green-600 focus:ring-green-500 @endif">
                                {{ __('Submit Report') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
