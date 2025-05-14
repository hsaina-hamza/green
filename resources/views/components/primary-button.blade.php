@props([
    'type' => 'submit',
    'variant' => 'primary', // 'primary', 'secondary', 'danger', 'success'
    'size' => 'medium', // 'small', 'medium', 'large'
    'icon' => null,
    'iconPosition' => 'left', // 'left', 'right'
    'disabled' => false,
])

@php
    // Variant colors
    $colors = [
        'primary' => [
            'bg' => 'bg-indigo-600',
            'hover' => 'hover:bg-indigo-700',
            'focus' => 'focus:bg-indigo-700',
            'active' => 'active:bg-indigo-800',
            'ring' => 'focus:ring-indigo-500',
            'dark' => 'dark:bg-indigo-700 dark:hover:bg-indigo-600 dark:focus:bg-indigo-600 dark:active:bg-indigo-800'
        ],
        'secondary' => [
            'bg' => 'bg-gray-600',
            'hover' => 'hover:bg-gray-700',
            'focus' => 'focus:bg-gray-700',
            'active' => 'active:bg-gray-800',
            'ring' => 'focus:ring-gray-500',
            'dark' => 'dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:bg-gray-600 dark:active:bg-gray-800'
        ],
        'danger' => [
            'bg' => 'bg-red-600',
            'hover' => 'hover:bg-red-700',
            'focus' => 'focus:bg-red-700',
            'active' => 'active:bg-red-800',
            'ring' => 'focus:ring-red-500',
            'dark' => 'dark:bg-red-700 dark:hover:bg-red-600 dark:focus:bg-red-600 dark:active:bg-red-800'
        ],
        'success' => [
            'bg' => 'bg-green-600',
            'hover' => 'hover:bg-green-700',
            'focus' => 'focus:bg-green-700',
            'active' => 'active:bg-green-800',
            'ring' => 'focus:ring-green-500',
            'dark' => 'dark:bg-green-700 dark:hover:bg-green-600 dark:focus:bg-green-600 dark:active:bg-green-800'
        ]
    ][$variant] ?? $colors['primary'];

    // Size classes
    $sizes = [
        'small' => 'px-3 py-1.5 text-xs',
        'medium' => 'px-4 py-2 text-sm',
        'large' => 'px-6 py-3 text-base'
    ][$size] ?? $sizes['medium'];

    // Base classes
    $baseClasses = 'inline-flex items-center justify-center border border-transparent rounded-md font-semibold uppercase tracking-widest text-white focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-70 disabled:cursor-not-allowed';
@endphp

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => "$baseClasses $sizes {$colors['bg']} {$colors['hover']} {$colors['focus']} {$colors['active']} {$colors['ring']} {$colors['dark']}"]) }}
    {{ $disabled ? 'disabled' : '' }}
    aria-busy="{{ $attributes->has('wire:loading') ? 'true' : 'false' }}"
>
    @if($icon && $iconPosition === 'left')
        <span class="mr-2">{{ $icon }}</span>
    @endif

    {{ $slot }}

    @if($icon && $iconPosition === 'right')
        <span class="ml-2">{{ $icon }}</span>
    @endif

    @if($attributes->has('wire:loading'))
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @endif
</button>