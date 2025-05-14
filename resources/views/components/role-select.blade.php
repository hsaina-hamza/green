@props([
    'label' => null,
    'disabled' => false,
    'required' => false,
    'placeholder' => null,
    'labelClasses' => 'block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300 text-right',
    'selectClasses' => 'block w-full px-4 py-2 pr-10 text-right border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:border-primary-300 focus:ring-primary-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:ring-primary-500/20 dark:focus:border-primary-500 transition duration-200 ease-in-out'
])

<div class="space-y-1" dir="rtl">
    @if($label)
        <label for="{{ $attributes->get('id') }}" class="{{ $labelClasses }}">
            {{ $label }}
            @if($required)
                <span class="text-red-500 mr-1">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <select 
            {{ $disabled ? 'disabled' : '' }}
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => $selectClasses . ($disabled ? ' bg-gray-100 dark:bg-gray-700 cursor-not-allowed' : '')]) }}
        >
            @if($placeholder)
                <option value="" class="text-gray-400">{{ $placeholder }}</option>
            @endif
            {{ $slot }}
        </select>
        
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </div>
    </div>

    @error($attributes->get('name'))
        <p class="mt-1 text-sm text-red-600 dark:text-red-400 text-right flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            {{ $message }}
        </p>
    @enderror
</div>