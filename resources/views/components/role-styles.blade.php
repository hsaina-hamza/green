@props([
    'type' => 'bg', // bg, border, text, button, focus, input-border, file, badge, link
    'role' => null, // Optional override (admin, worker, user)
    'intensity' => [ // Color intensity levels
        'light' => '50',
        'medium' => '200',
        'dark' => '700'
    ],
    'darkMode' => true // Enable/disable dark mode variants
])

@php
    // Determine role if not explicitly provided
    $role = $role ?? (Auth::user()->isAdmin() ? 'admin' : (Auth::user()->isWorker() ? 'worker' : 'user'));
    
    // Define color palette for each role
    $roleColors = [
        'admin' => 'purple',
        'worker' => 'blue',
        'user' => 'green',
        'default' => 'gray'
    ];
    
    $color = $roleColors[$role] ?? $roleColors['default'];
    
    // Generate classes based on type
    $classes = match($type) {
        'bg' => "bg-{$color}-{$intensity['light']}" . ($darkMode ? " dark:bg-{$color}-900/20" : ""),
        'border' => "border-{$color}-{$intensity['medium']}" . ($darkMode ? " dark:border-{$color}-700" : ""),
        'text' => "text-{$color}-{$intensity['dark']}" . ($darkMode ? " dark:text-{$color}-300" : ""),
        'button' => "bg-{$color}-500 hover:bg-{$color}-600 text-white",
        'focus' => "focus:border-{$color}-500 focus:ring-{$color}-500",
        'input-border' => "border-{$color}-300 focus:border-{$color}-500",
        'file' => "file:bg-{$color}-{$intensity['light']} file:text-{$color}-{$intensity['dark']} hover:file:bg-{$color}-100",
        'badge' => "bg-{$color}-{$intensity['light']} text-{$color}-{$intensity['dark']}",
        'link' => "text-{$color}-600 hover:text-{$color}-800",
        default => ''
    };
@endphp

{{ $classes }}