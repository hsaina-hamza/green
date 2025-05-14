@props([
    'active' => false,
    'icon' => null,
    'badge' => null,
    'badgeColor' => 'bg-indigo-100 text-indigo-800'
])

@php
    $baseClasses = 'inline-flex items-center px-3 py-2 text-sm font-medium transition duration-150 ease-in-out';
    $activeClasses = 'border-b-2 border-indigo-500 text-gray-900 focus:outline-none focus:border-indigo-700';
    $inactiveClasses = 'border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300';
    
    $classes = $active ? $baseClasses.' '.$activeClasses : $baseClasses.' '.$inactiveClasses;
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} 
   aria-current="{{ $active ? 'page' : 'false' }}"
>
    @if($icon)
        <span class="mr-2 {{ $active ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500' }}">
            {{ $icon }}
        </span>
    @endif
    
    <span>{{ $slot }}</span>
    
    @if($badge)
        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $badgeColor }}">
            {{ $badge }}
        </span>
    @endif
</a>