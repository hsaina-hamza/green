@props(['active' => false])

@php
$baseClasses = 'block w-full py-2 text-base font-medium focus:outline-none transition duration-150 ease-in-out rtl';
$activeClasses = 'border-r-4 border-indigo-400 text-indigo-700 bg-indigo-50 focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700';
$inactiveClasses = 'border-r-4 border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300';
$paddingClasses = 'pr-3 pl-4';

$classes = $baseClasses . ' ' . $paddingClasses . ' ' . ($active ? $activeClasses : $inactiveClasses);
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} dir="rtl">
    <div class="flex items-center justify-end">
        {{ $slot }}
        @if($active)
            <svg class="ml-2 h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10 10.586 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
        @endif
    </div>
</a>
