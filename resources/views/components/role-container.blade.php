@props([
    'type' => 'both', // 'both', 'header', 'footer', 'none'
    'padded' => true,
    'rounded' => true,
    'shadowed' => true,
    'bordered' => false,
    'rtl' => true
])

@php
    // Base container classes
    $containerClasses = 'bg-white';
    
    // Padding
    $containerClasses .= $padded ? ' p-6' : '';
    
    // Border radius
    $containerClasses .= $rounded ? ' rounded-lg' : '';
    
    // Shadow
    $containerClasses .= $shadowed ? ' shadow-md' : '';
    
    // Border
    $containerClasses .= $bordered ? ' border border-gray-200' : '';
    
    // RTL support
    $containerClasses .= $rtl ? ' rtl' : '';
@endphp

<div {{ $attributes->merge(['class' => $containerClasses]) }} dir="{{ $rtl ? 'rtl' : 'ltr' }}">
    @if(in_array($type, ['both', 'header']) && isset($header))
        <div class="mb-6 border-b border-gray-100 pb-4 flex items-center justify-between">
            <div class="flex items-center space-x-3 space-x-reverse">
                @if(isset($icon))
                    <div class="p-2 rounded-full bg-blue-50 text-blue-600">
                        <i class="fas fa-{{ $icon }}"></i>
                    </div>
                @endif
                <h3 class="text-lg font-semibold text-gray-800">
                    {{ $header }}
                </h3>
            </div>
            @if(isset($actions))
                <div class="flex space-x-2 space-x-reverse">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    <div class="{{ $padded ? 'px-2' : '' }}">
        {{ $slot }}
    </div>

    @if(in_array($type, ['both', 'footer']) && isset($footer))
        <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
            {{ $footer }}
        </div>
    @endif
</div>

<style>
    .rtl {
        direction: rtl;
        text-align: right;
    }
</style>