@props(['active', 'role' => Auth::user()->role ?? 'user'])

@php
// Base classes
$baseClasses = 'block w-full py-2 text-base font-medium focus:outline-none transition duration-150 ease-in-out rtl';

// Active state classes
$activeClasses = [
    'admin' => 'border-r-4 border-purple-400 text-purple-700 bg-purple-50 focus:text-purple-800 focus:bg-purple-100 focus:border-purple-700',
    'worker' => 'border-r-4 border-blue-400 text-blue-700 bg-blue-50 focus:text-blue-800 focus:bg-blue-100 focus:border-blue-700',
    'user' => 'border-r-4 border-green-400 text-green-700 bg-green-50 focus:text-green-800 focus:bg-green-100 focus:border-green-700'
][$role] ?? 'border-r-4 border-green-400 text-green-700 bg-green-50';

// Inactive state classes
$inactiveClasses = [
    'admin' => 'border-r-4 border-transparent text-gray-600 hover:text-purple-700 hover:bg-purple-50 hover:border-purple-300',
    'worker' => 'border-r-4 border-transparent text-gray-600 hover:text-blue-700 hover:bg-blue-50 hover:border-blue-300',
    'user' => 'border-r-4 border-transparent text-gray-600 hover:text-green-700 hover:bg-green-50 hover:border-green-300'
][$role] ?? 'border-r-4 border-transparent text-gray-600';

// Padding classes (RTL adjusted)
$paddingClasses = 'pr-3 pl-4'; // Reversed for RTL

// Combine all classes
$classes = $baseClasses . ' ' . $paddingClasses . ' ' . (($active ?? false) ? $activeClasses : $inactiveClasses);
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} dir="rtl">
    <div class="flex items-center justify-between">
        <span>{{ $slot }}</span>
        @if($active)
            <i class="fas fa-arrow-left text-gray-400 transform rtl:rotate-180"></i>
        @endif
    </div>
</a>

<style>
    .rtl {
        direction: rtl;
        text-align: right;
    }
    .rtl .rtl\:rotate-180 {
        transform: rotate(180deg);
    }
    a {
        text-decoration: none;
    }
</style>