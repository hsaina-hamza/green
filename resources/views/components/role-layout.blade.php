@props([
    'title' => null,
    'role' => Auth::user()->role ?? 'user',
    'rtl' => true
])

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            @if($title)
                <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                    @if($role === 'admin')
                        <i class="fas fa-user-shield mr-3 text-purple-500"></i>
                    @elseif($role === 'worker')
                        <i class="fas fa-hard-hat mr-3 text-blue-500"></i>
                    @else
                        <i class="fas fa-user mr-3 text-green-500"></i>
                    @endif
                    {{ $title }}
                </h2>
            @endif
            @if(isset($header))
                <div class="flex items-center space-x-4 space-x-reverse">
                    {{ $header }}
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12" dir="{{ $rtl ? 'rtl' : 'ltr' }}">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div @class([
                'overflow-hidden shadow-sm sm:rounded-lg border',
                'bg-purple-50 border-purple-200' => $role === 'admin',
                'bg-blue-50 border-blue-200' => $role === 'worker',
                'bg-green-50 border-green-200' => $role === 'user'
            ])>
                <div class="p-6">
                    @if(isset($alert))
                        <div class="mb-6">
                            {{ $alert }}
                        </div>
                    @endif
                    
                    {{ $slot }}
                    
                    @if(isset($footer))
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            {{ $footer }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    [dir="rtl"] {
        direction: rtl;
        text-align: right;
    }
</style>