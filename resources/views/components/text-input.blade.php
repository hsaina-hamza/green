@props([
    'disabled' => false,
    'rtl' => true, // RTL support
    'size' => 'medium', // small, medium, large
    'icon' => null, // Optional icon (Font Awesome class)
    'iconPosition' => 'right' // left/right
])

@php
    // Base classes
    $baseClasses = 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition duration-200 ease-in-out';
    
    // Size classes
    $sizeClasses = [
        'small' => 'py-1 px-2 text-sm',
        'medium' => 'py-2 px-3 text-base',
        'large' => 'py-3 px-4 text-lg'
    ][$size] ?? 'py-2 px-3 text-base';
    
    // RTL classes
    $rtlClasses = $rtl ? 'rtl text-right' : '';
    
    // Combine classes
    $classes = $baseClasses . ' ' . $sizeClasses . ' ' . $rtlClasses;
    
    // Icon padding adjustment
    if ($icon) {
        $classes .= $iconPosition === 'left' ? ' pl-10' : ' pr-10';
    }
@endphp

<div class="relative">
    @if($icon && $iconPosition === 'left')
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3">
            <i class="fas fa-{{ $icon }} text-gray-400"></i>
        </div>
    @endif
    
    <input 
        {{ $disabled ? 'disabled' : '' }}
        {!! $attributes->merge(['class' => $classes]) !!}
        dir="{{ $rtl ? 'rtl' : 'ltr' }}"
    >
    
    @if($icon && $iconPosition === 'right')
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none rtl:right-auto rtl:left-0 rtl:pr-0 rtl:pl-3">
            <i class="fas fa-{{ $icon }} text-gray-400"></i>
        </div>
    @endif
</div>

<style>
    .rtl {
        direction: rtl;
    }
    input[disabled] {
        background-color: #f3f4f6;
        cursor: not-allowed;
    }
</style>