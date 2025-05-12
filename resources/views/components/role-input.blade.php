@props(['type' => 'text', 'label' => null, 'disabled' => false, 'required' => false])

<div>
    @if($label)
        <label for="{{ $attributes->get('id') }}" class="{{ $labelClasses }}">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <input 
        type="{{ $type }}"
        {{ $disabled ? 'disabled' : '' }}
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => $inputClasses]) }}
    >

    @error($attributes->get('name'))
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
