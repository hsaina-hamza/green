@props([
    'type' => 'button',
    'href' => null,
    'variant' => 'primary', // Added variant prop (primary, secondary, danger)
    'icon' => null, // Added icon prop
    'iconPosition' => 'right' // Added icon position (right/left)
])

@php
    // Base classes
    $baseClasses = 'inline-flex items-center justify-center px-4 py-2 rounded-md font-medium transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 rtl';
    
    // Variant classes
    $variantClasses = [
        'primary' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500',
        'secondary' => 'bg-gray-200 text-gray-800 hover:bg-gray-300 focus:ring-gray-500',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
    ][$variant] ?? $variantClasses['primary'];
    
    // Combine classes
    $classes = $baseClasses . ' ' . $variantClasses;
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }} dir="rtl">
        @if($icon && $iconPosition === 'left')
            <i class="fas fa-{{ $icon }} mr-2 rtl:mr-0 rtl:ml-2"></i>
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <i class="fas fa-{{ $icon }} ml-2 rtl:ml-0 rtl:mr-2"></i>
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }} dir="rtl">
        @if($icon && $iconPosition === 'left')
            <i class="fas fa-{{ $icon }} mr-2 rtl:mr-0 rtl:ml-2"></i>
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <i class="fas fa-{{ $icon }} ml-2 rtl:ml-0 rtl:mr-2"></i>
        @endif
    </button>
@endif

<style>
    .rtl {
        direction: rtl;
    }
    [dir="rtl"] .rtl\:mr-0 {
        margin-right: 0;
    }
    [dir="rtl"] .rtl\:ml-2 {
        margin-left: 0.5rem;
    }
    [dir="rtl"] .rtl\:ml-0 {
        margin-left: 0;
    }
    [dir="rtl"] .rtl\:mr-2 {
        margin-right: 0.5rem;
    }
</style>