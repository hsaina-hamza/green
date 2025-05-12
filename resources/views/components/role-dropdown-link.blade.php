@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block w-full px-4 py-2 text-left text-sm leading-5 focus:outline-none transition duration-150 ease-in-out ' . 
      (Auth::user()->role === 'admin' 
        ? 'bg-purple-100 text-purple-900' 
        : (Auth::user()->role === 'worker'
            ? 'bg-blue-100 text-blue-900'
            : 'bg-green-100 text-green-900'))
    : 'block w-full px-4 py-2 text-left text-sm leading-5 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out ' .
      (Auth::user()->role === 'admin'
        ? 'text-purple-700 hover:bg-purple-50'
        : (Auth::user()->role === 'worker'
            ? 'text-blue-700 hover:bg-blue-50'
            : 'text-green-700 hover:bg-green-50'));
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
