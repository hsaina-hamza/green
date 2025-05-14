@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block w-full px-4 py-2 text-right text-sm leading-5 focus:outline-none transition duration-150 ease-in-out rounded-md ' . 
      (Auth::user()->role === 'admin' 
        ? 'bg-purple-100 text-purple-900 dark:bg-purple-900 dark:text-purple-100' 
        : (Auth::user()->role === 'worker'
            ? 'bg-blue-100 text-blue-900 dark:bg-blue-900 dark:text-blue-100'
            : 'bg-green-100 text-green-900 dark:bg-green-900 dark:text-green-100'))
    : 'block w-full px-4 py-2 text-right text-sm leading-5 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 transition duration-150 ease-in-out rounded-md ' .
      (Auth::user()->role === 'admin'
        ? 'text-purple-700 hover:bg-purple-50 dark:text-purple-300 dark:hover:bg-purple-800'
        : (Auth::user()->role === 'worker'
            ? 'text-blue-700 hover:bg-blue-50 dark:text-blue-300 dark:hover:bg-blue-800'
            : 'text-green-700 hover:bg-green-50 dark:text-green-300 dark:hover:bg-green-800'));
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} dir="rtl">
    <div class="flex items-center justify-end space-x-2 space-x-reverse">
        @if(isset($icon))
            <span class="shrink-0">
                {{ $icon }}
            </span>
        @endif
        <span>{{ $slot }}</span>
    </div>
</a>