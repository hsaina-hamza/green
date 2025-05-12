<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen" @class([
            'bg-purple-50' => Auth::user()->role === 'admin',
            'bg-blue-50' => Auth::user()->role === 'worker',
            'bg-green-50' => Auth::user()->role === 'user'
        ])>
            @include('layouts.role-navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header @class([
                    'bg-white shadow',
                    'border-b-4 border-purple-500' => Auth::user()->role === 'admin',
                    'border-b-4 border-blue-500' => Auth::user()->role === 'worker',
                    'border-b-4 border-green-500' => Auth::user()->role === 'user'
                ])>
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer @class([
                'bg-white shadow mt-8 py-4',
                'border-t-4 border-purple-500' => Auth::user()->role === 'admin',
                'border-t-4 border-blue-500' => Auth::user()->role === 'worker',
                'border-t-4 border-green-500' => Auth::user()->role === 'user'
            ])>
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center text-sm text-gray-500">
                        © {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
