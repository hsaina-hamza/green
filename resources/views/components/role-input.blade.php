@props([
    'type' => 'text',
    'label' => null,
    'disabled' => false,
    'required' => false,
    'error' => null,
    'helperText' => null,
    'prefix' => null,
    'suffix' => null,
    'size' => 'medium', // small, medium, large
    'fullWidth' => false,
])

@php
    // Size classes
    $sizeClasses = [
        'small' => 'px-2 py-1 text-sm',
        'medium' => 'px-3 py-2 text-base',
        'large' => 'px-4 py-3 text-lg',
    ][$size] ?? 'px-3 py-2 text-base';

    // Input classes
    $inputClasses = implode(' ', [
        'block w-full rounded-md border-0 shadow-sm ring-1 ring-inset focus:ring-2 focus:ring-inset transition duration-150 ease-in-out',
        $sizeClasses,
        $disabled ? 'bg-gray-50 text-gray-500 cursor-not-allowed' : 'bg-white text-gray-900',
        $error 
            ? 'ring-red-300 placeholder:text-red-300 focus:ring-red-500'
            : 'ring-gray-300 placeholder:text-gray-400 focus:ring-indigo-600',
        $fullWidth ? 'w-full' : '',
    ]);

    // Label classes
    $labelClasses = implode(' ', [
        'block text-sm font-medium leading-6',
        $error ? 'text-red-600' : 'text-gray-900',
        $disabled ? 'text-gray-400' : '',
    ]);
@endphp

<div class="{{ $fullWidth ? 'w-full' : '' }}">
    @if($label)
        <label for="{{ $attributes->get('id') }}" class="{{ $labelClasses }}">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative mt-1 rounded-md shadow-sm">
        @if($prefix)
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <span class="text-gray-500 sm:text-sm">{{ $prefix }}</span>
            </div>
        @endif

        <input 
            type="{{ $type }}"
            {{ $disabled ? 'disabled' : '' }}
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => $inputClasses . ($prefix ? ' pl-7' : '') . ($suffix ? ' pr-7' : '')]) }}
            @if($error) aria-invalid="true" aria-describedby="{{ $attributes->get('id') }}-error" @endif
        >

        @if($suffix)
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                <span class="text-gray-500 sm:text-sm">{{ $suffix }}</span>
            </div>
        @endif
    </div>

    @if($helperText && !$error)
        <p class="mt-2 text-sm text-gray-500" id="{{ $attributes->get('id') }}-description">
            {{ $helperText }}
        </p>
    @endif

    @if($error)
        <p class="mt-2 text-sm text-red-600" id="{{ $attributes->get('id') }}-error">
            {{ $error }}
        </p>
    @endif
</div>