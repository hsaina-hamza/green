@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700 dark:text-gray-300 mb-1 text-right']) }}>
    @if(isset($icon))
        <span class="flex items-center">
            @if($iconPosition === 'right') {{-- For RTL, icon appears on the left --}}
                <span class="ml-2">{{ $value ?? $slot }}</span>
                @if($icon)
                    <x-dynamic-component :component="$icon" class="h-4 w-4 text-gray-500 dark:text-gray-400" />
                @endif
            @else
                @if($icon)
                    <x-dynamic-component :component="$icon" class="h-4 w-4 text-gray-500 dark:text-gray-400 mr-2" />
                @endif
                <span>{{ $value ?? $slot }}</span>
            @endif
        </span>
    @else
        {{ $value ?? $slot }}
    @endif
</label>