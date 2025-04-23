<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GreenMorocco</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="bg-green-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold">GM</h1>
            </div>
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="{{ route('home') }}" class="hover:underline">Home</a></li>
                    <li><a href="{{ route('conservation.tips') }}" class="hover:underline">Conservation Tips</a></li>
                    <li><a href="{{ route('waste-map') }}" class="hover:underline">Waste Map</a></li>
                    <li><a href="{{ route('bus-times.index') }}" class="hover:underline">Bus Times</a></li>
                    <li><a href="{{ route('waste-reports.create') }}" class="hover:underline">Report Waste</a></li>
                    @if (Route::has('login'))
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a></li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:underline">Login</a></li>
                        @endauth
                    @endif
                </ul>
            </nav>
        </div>
    </header>

    <section class="bg-green-100 py-16">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-bold mb-4">Together for a Cleaner Environment</h2>
            <p class="mb-8">Join our community effort to conserve the environment, report waste sites, and make a positive impact on our planet for future generations.</p>
            <div>
                @auth
                    <a href="{{ route('waste-reports.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mr-2">Report Waste Site</a>
                @else
                    <a href="{{ route('login') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mr-2" 
                       onclick="alert('Please log in to report a waste site.')">Report Waste Site</a>
                @endauth
                <a href="{{ route('conservation.tips') }}" class="bg-transparent hover:bg-green-600 text-green-600 hover:text-white px-4 py-2 rounded border border-green-600">Conservation Tips</a>
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="container mx-auto">
            <h2 class="text-2xl font-bold mb-8 text-center">Our Services</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white p-6 rounded shadow text-center">
                    <i class="fas fa-trash-alt text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-lg font-semibold">Report Waste</h3>
                    <p class="text-sm">Report illegal waste sites with photos and location data to help clean up our community.</p>
                    @auth
                        <a href="{{ route('waste-reports.create') }}" class="mt-4 inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Report Now</a>
                    @else
                        <a href="{{ route('login') }}" class="mt-4 inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                           onclick="alert('Please log in to report a waste site.')">Report Now</a>
                    @endauth
                </div>
                <div class="bg-white p-6 rounded shadow text-center">
                    <i class="fas fa-map-marked-alt text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-lg font-semibold">Waste Map</h3>
                    <p class="text-sm">View reported waste sites on an interactive map and track cleanup progress.</p>
                    <a href="{{ route('waste-map') }}" class="mt-4 inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">View Map</a>
                </div>
                <div class="bg-white p-6 rounded shadow text-center">
                    <i class="fas fa-bus text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-lg font-semibold">Bus Times</h3>
                    <p class="text-sm">Check local bus schedules to reduce your carbon footprint with public transport.</p>
                    <a href="{{ route('bus-times.index') }}" class="mt-4 inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">View Schedule</a>
                </div>
                <div class="bg-white p-6 rounded shadow text-center">
                    <i class="fas fa-leaf text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-lg font-semibold">Conservation Tips</h3>
                    <p class="text-sm">Learn how to reduce waste and protect our environment with practical tips.</p>
                    <a href="{{ route('conservation.tips') }}" class="mt-4 inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Learn More</a>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-green-600 text-white py-16">
        <div class="container mx-auto text-center">
            <h2 class="text-2xl font-bold mb-4">Join Our Environmental Mission</h2>
            <p class="mb-8">Create an account to report waste sites, track your reports, and become part of our community dedicated to environmental conservation.</p>
            <div>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="bg-white text-green-600 px-4 py-2 rounded hover:bg-gray-100 mr-2">Create Account</a>
                @endif
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="bg-transparent hover:bg-green-600 text-white hover:text-green-600 px-4 py-2 rounded border border-white">Login</a>
                @endif
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="container mx-auto">
            <h2 class="text-2xl font-bold mb-8 text-center">Recent Reports</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($recentReports ?? [] as $report)
                <div class="bg-white p-6 rounded shadow">
                    <div class="bg-gray-200 h-48 mb-4 rounded flex items-center justify-center">
                        <i class="fas fa-image text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold">{{ $report->title ?? '' }}</h3>
                    <p class="text-sm">{{ $report->location ?? '' }}</p>
                    <p class="text-sm text-green-600 font-semibold">{{ $report->status ?? '' }}</p>
                    <a href="{{ route('waste-reports.show', ['waste_report' => $report->id ?? 0]) }}" class="mt-4 inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">View Report</a>
                </div>
                @empty
                <div class="col-span-3 text-center text-gray-500">
                    <i class="fas fa-clipboard text-4xl mb-2"></i>
                    <p>No reports available yet. Be the first to report a waste site!</p>
                </div>
                @endforelse
            </div>
            <div class="text-center mt-8">
                <a href="{{ route('waste-map') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">View All Reports</a>
            </div>
        </div>
    </section>

    <footer class="bg-green-800 text-white py-8">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h4 class="text-lg font-semibold">GreenMorocco</h4>
                    <p class="text-sm mt-2">Dedicated to environmental conservation and proper waste management for a cleaner, greener future.</p>
                </div>
                <div>
                    <h5 class="text-lg font-semibold">Quick Links</h5>
                    <ul class="mt-2 space-y-2">
                        <li><a href="{{ route('home') }}" class="hover:underline">Home</a></li>
                        <li><a href="{{ route('conservation.tips') }}" class="hover:underline">Conservation Tips</a></li>
                        <li><a href="{{ route('waste-map') }}" class="hover:underline">Waste Map</a></li>
                        <li><a href="{{ route('bus-times.index') }}" class="hover:underline">Bus Times</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-lg font-semibold">Services</h5>
                    <ul class="mt-2 space-y-2">
                        <li><a href="{{ route('waste-reports.create') }}" class="hover:underline">Report Waste</a></li>
                        @if (Route::has('register'))
                            <li><a href="{{ route('register') }}" class="hover:underline">Create Account</a></li>
                        @endif
                        @if (Route::has('login'))
                            <li><a href="{{ route('login') }}" class="hover:underline">Login</a></li>
                        @endif
                    </ul>
                </div>
                <div>
                    <h5 class="text-lg font-semibold">Contact Us</h5>
                    <ul class="mt-2 space-y-2">
                        <li>
                            <a href="mailto:contact@greenmorocco.org" class="hover:underline">
                                <i class="fas fa-envelope mr-2"></i>contact@greenmorocco.org
                            </a>
                        </li>
                        <li>
                            <a href="tel:+212123456789" class="hover:underline">
                                <i class="fas fa-phone mr-2"></i>(+212) 123-456-789
                            </a>
                        </li>
                        <li>
                            <a href="#" class="hover:underline">
                                <i class="fas fa-map-marker-alt mr-2"></i>123 Green Street, Rabat
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
