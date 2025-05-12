@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block w-full pl-3 pr-4 py-2 border-l-4 text-left text-base font-medium focus:outline-none transition duration-150 ease-in-out ' . 
      (Auth::user()->role === 'admin' 
        ? 'border-purple-400 text-purple-700 bg-purple-50 focus:text-purple-800 focus:bg-purple-100 focus:border-purple-700' 
        : (Auth::user()->role === 'worker'
            ? 'border-blue-400 text-blue-700 bg-blue-50 focus:text-blue-800 focus:bg-blue-100 focus:border-blue-700'
            : 'border-green-400 text-green-700 bg-green-50 focus:text-green-800 focus:bg-green-100 focus:border-green-700'))
    : 'block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out ' .
      (Auth::user()->role === 'admin'
        ? 'hover:text-purple-700 hover:bg-purple-50 hover:border-purple-300'
        : (Auth::user()->role === 'worker'
            ? 'hover:text-blue-700 hover:bg-blue-50 hover:border-blue-300'
            : 'hover:text-green-700 hover:bg-green-50 hover:border-green-300'));
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
