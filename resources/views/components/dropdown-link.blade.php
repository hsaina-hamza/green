<a 
    {{ $attributes->merge([
        'class' => 'block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 transition duration-150 ease-in-out',
        'role' => 'menuitem',
        'tabindex' => '-1'
    ]) }}
>
    <div class="flex items-center">
        @if(isset($icon))
            <span class="mr-2 text-gray-500 dark:text-gray-400">
                {{ $icon }}
            </span>
        @endif
        <span>{{ $slot }}</span>
        @if(isset($badge))
            <span class="ml-auto px-2 py-0.5 text-xs font-medium rounded-full {{ $badge->attributes->get('class', 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200') }}">
                {{ $badge }}
            </span>
        @endif
    </div>
</a>