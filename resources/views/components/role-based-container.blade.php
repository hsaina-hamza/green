@props([
    'type' => 'bg', // 'bg', 'border', 'text', 'button', 'input', 'file', 'badge'
    'role' => Auth::user()->role ?? 'default', // Allow override
    'intensity' => '500', // Color intensity (100-900)
    'hoverIntensity' => '600', // Hover color intensity
    'focusIntensity' => '500' // Focus color intensity
])

@php
    // Define color mappings for each role
    $roleColors = [
        'admin' => 'purple',
        'worker' => 'blue',
        'user' => 'green',
        'default' => 'gray'
    ];

    // Get the base color for the current role
    $color = $roleColors[$role] ?? $roleColors['default'];

    // Generate classes based on type
    $classes = match($type) {
        'bg' => "bg-{$color}-50 dark:bg-{$color}-900/20",
        'border' => "border-{$color}-200 dark:border-{$color}-700",
        'text' => "text-{$color}-700 dark:text-{$color}-300",
        'button' => "bg-{$color}-{$intensity} hover:bg-{$color}-{$hoverIntensity} 
                    focus:bg-{$color}-{$focusIntensity} text-white",
        'input' => "border-{$color}-300 focus:border-{$color}-{$focusIntensity} 
                   focus:ring-{$color}-{$focusIntensity}",
        'file' => "file:bg-{$color}-50 file:text-{$color}-700 hover:file:bg-{$color}-100 
                  dark:file:bg-{$color}-900/30 dark:file:text-{$color}-300",
        'badge' => "bg-{$color}-100 text-{$color}-800 dark:bg-{$color}-900/30 
                   dark:text-{$color}-200",
        'link' => "text-{$color}-600 hover:text-{$color}-800 dark:text-{$color}-400 
                  dark:hover:text-{$color}-300",
        default => ''
    };

    // Add transition for interactive elements
    if (in_array($type, ['button', 'input', 'file', 'link'])) {
        $classes .= ' transition-colors duration-200 ease-in-out';
    }
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>