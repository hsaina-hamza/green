@props(['active'])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out ' . 
      (Auth::user()->role === 'admin' 
        ? 'border-purple-400 text-purple-900 focus:border-purple-700' 
        : (Auth::user()->role === 'worker'
            ? 'border-blue-400 text-blue-900 focus:border-blue-700'
            : 'border-green-400 text-green-900 focus:border-green-700'))
    : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out ' .
      (Auth::user()->role === 'admin'
        ? 'text-purple-500 hover:text-purple-700'
        : (Auth::user()->role === 'worker'
            ? 'text-blue-500 hover:text-blue-700'
            : 'text-green-500 hover:text-green-700'));
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
