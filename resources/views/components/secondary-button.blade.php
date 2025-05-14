@props([
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, danger, success
    'size' => 'medium', // small, medium, large
    'icon' => null, // FontAwesome icon name
    'iconPosition' => 'right', // left, right
    'disabled' => false,
    'rtl' => true // RTL support
])

@php
    // Base classes
    $baseClasses = 'inline-flex items-center font-semibold uppercase tracking-widest shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 rounded-md border';
    
    // Variant classes
    $variantClasses = [
        'primary' => 'bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500',
        'secondary' => 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 focus:ring-indigo-500',
        'danger' => 'bg-red-600 text-white border-red-600 hover:bg-red-700 focus:ring-red-500',
        'success' => 'bg-green-600 text-white border-green-600 hover:bg-green-700 focus:ring-green-500'
    ][$variant] ?? $variantClasses['primary'];
    
    // Size classes
    $sizeClasses = [
        'small' => 'px-3 py-1.5 text-xs',
        'medium' => 'px-4 py-2 text-sm',
        'large' => 'px-6 py-3 text-base'
    ][$size] ?? 'px-4 py-2 text-sm';
    
    // RTL classes
    $rtlClasses = $rtl ? 'rtl' : '';
    
    // Combine classes
    $classes = $baseClasses . ' ' . $variantClasses . ' ' . $sizeClasses . ' ' . $rtlClasses;
@endphp

<button 
    type="{{ $type }}"
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => $classes]) }}
    dir="{{ $rtl ? 'rtl' : 'ltr' }}"
>
    @if($icon && $iconPosition === 'left')
        <i class="fas fa-{{ $icon }} {{ $rtl ? 'ml-2' : 'mr-2' }}"></i>
    @endif
    
    {{ $slot }}
    
    @if($icon && $iconPosition === 'right')
        <i class="fas fa-{{ $icon }} {{ $rtl ? 'mr-2' : 'ml-2' }}"></i>
    @endif
</button>

<style>
    .rtl {
        direction: rtl;
    }
    button[disabled] {
        cursor: not-allowed;
    }
</style>