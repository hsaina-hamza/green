@props(['title' => null])

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            @if($title)
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $title }}
                </h2>
            @endif
            @if(isset($header))
                {{ $header }}
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div @class([
                'overflow-hidden shadow-sm sm:rounded-lg border',
                'bg-purple-50 border-purple-200' => Auth::user()->role === 'admin',
                'bg-blue-50 border-blue-200' => Auth::user()->role === 'worker',
                'bg-green-50 border-green-200' => Auth::user()->role === 'user'
            ])>
                <div class="p-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
