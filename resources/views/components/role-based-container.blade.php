@props(['type' => 'bg'])

@php
$classes = match($type) {
    'bg' => match(Auth::user()->role) {
        'admin' => 'bg-purple-50',
        'worker' => 'bg-blue-50',
        'user' => 'bg-green-50',
        default => 'bg-gray-50'
    },
    'border' => match(Auth::user()->role) {
        'admin' => 'border-purple-200',
        'worker' => 'border-blue-200',
        'user' => 'border-green-200',
        default => 'border-gray-200'
    },
    'text' => match(Auth::user()->role) {
        'admin' => 'text-purple-700',
        'worker' => 'text-blue-700',
        'user' => 'text-green-700',
        default => 'text-gray-700'
    },
    'button' => match(Auth::user()->role) {
        'admin' => 'bg-purple-500 hover:bg-purple-600',
        'worker' => 'bg-blue-500 hover:bg-blue-600',
        'user' => 'bg-green-500 hover:bg-green-600',
        default => 'bg-gray-500 hover:bg-gray-600'
    },
    'input' => match(Auth::user()->role) {
        'admin' => 'border-purple-300 focus:border-purple-500 focus:ring-purple-500',
        'worker' => 'border-blue-300 focus:border-blue-500 focus:ring-blue-500',
        'user' => 'border-green-300 focus:border-green-500 focus:ring-green-500',
        default => 'border-gray-300 focus:border-gray-500 focus:ring-gray-500'
    },
    'file' => match(Auth::user()->role) {
        'admin' => 'file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100',
        'worker' => 'file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100',
        'user' => 'file:bg-green-50 file:text-green-700 hover:file:bg-green-100',
        default => 'file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100'
    },
    default => ''
};
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
