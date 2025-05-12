<x-role-app>
    <x-slot name="header">
        <h2 @class([
            'font-semibold text-xl leading-tight',
            'text-purple-800' => Auth::user()->role === 'admin',
            'text-blue-800' => Auth::user()->role === 'worker',
            'text-green-800' => Auth::user()->role === 'user'
        ])>
            {{ __('Conservation Tips') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($tips as $tip)
                    <x-role-container>
                        <h3 @class([
                            'text-lg font-medium mb-2',
                            'text-purple-900' => Auth::user()->role === 'admin',
                            'text-blue-900' => Auth::user()->role === 'worker',
                            'text-green-900' => Auth::user()->role === 'user'
                        ])>
                            {{ $tip['title'] }}
                        </h3>
                        <p class="text-gray-600">
                            {{ $tip['description'] }}
                        </p>
                    </x-role-container>
                @endforeach
            </div>

            <div class="mt-8">
                <x-role-container>
                    @if(Auth::user()->role === 'admin')
                        <h3 class="text-lg font-medium text-purple-900">Admin Actions</h3>
                        <p class="mt-2 text-gray-600">Manage conservation tips and monitor their effectiveness.</p>
                    @elseif(Auth::user()->role === 'worker')
                        <h3 class="text-lg font-medium text-blue-900">Worker Resources</h3>
                        <p class="mt-2 text-gray-600">Access tools and guidelines for waste management.</p>
                    @else
                        <h3 class="text-lg font-medium text-green-900">Get Involved</h3>
                        <p class="mt-2 text-gray-600">Learn how you can contribute to conservation efforts.</p>
                    @endif

                    <div class="mt-4">
                        <x-role-button href="{{ route('waste-reports.create') }}">
                            {{ __('Report Waste Issue') }}
                        </x-role-button>
                    </div>
                </x-role-container>
            </div>
        </div>
    </div>
</x-role-app>
