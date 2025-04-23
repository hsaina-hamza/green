<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Site') }}
            </h2>
            <a href="{{ route('sites.show', $site) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                {{ __('Back to Details') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.sites.update', $site) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('Site Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $site->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="address" :value="__('Address')" />
                            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $site->address)" required />
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="latitude" :value="__('Latitude')" />
                                <x-text-input id="latitude" name="latitude" type="number" step="any" class="mt-1 block w-full" :value="old('latitude', $site->latitude)" required />
                                <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="longitude" :value="__('Longitude')" />
                                <x-text-input id="longitude" name="longitude" type="number" step="any" class="mt-1 block w-full" :value="old('longitude', $site->longitude)" required />
                                <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update Site') }}</x-primary-button>
                            <a href="{{ route('sites.show', $site) }}" class="text-gray-600 hover:text-gray-900">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
