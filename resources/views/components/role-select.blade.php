@props(['label' => null, 'disabled' => false, 'required' => false, 'placeholder' => null])

<div>
    @if($label)
        <label for="{{ $attributes->get('id') }}" class="{{ $labelClasses }}">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <select 
        {{ $disabled ? 'disabled' : '' }}
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => $selectClasses]) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        {{ $slot }}
    </select>

    @error($attributes->get('name'))
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
