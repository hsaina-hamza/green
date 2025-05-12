<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo @class([
                            'block h-9 w-auto fill-current',
                            'text-purple-800' => Auth::user()->role === 'admin',
                            'text-blue-800' => Auth::user()->role === 'worker',
                            'text-green-800' => Auth::user()->role === 'user'
                        ]) />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-role-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-role-nav-link>
                    
                    <x-role-nav-link :href="route('waste-reports.index')" :active="request()->routeIs('waste-reports.*')">
                        {{ __('Waste Reports') }}
                    </x-role-nav-link>

                    <x-role-nav-link :href="route('schedules.index')" :active="request()->routeIs('schedules.*')">
                        {{ __('Schedules') }}
                    </x-role-nav-link>

                    @if(Auth::user()->role === 'admin')
                        <x-role-nav-link :href="route('sites.index')" :active="request()->routeIs('sites.*')">
                            {{ __('Sites') }}
                        </x-role-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button @class([
                            'inline-flex items-center px-3 py-2 border text-sm font-medium leading-4 rounded-md transition ease-in-out duration-150',
                            'bg-purple-50 border-purple-300 text-purple-700 hover:text-purple-900' => Auth::user()->role === 'admin',
                            'bg-blue-50 border-blue-300 text-blue-700 hover:text-blue-900' => Auth::user()->role === 'worker',
                            'bg-green-50 border-green-300 text-green-700 hover:text-green-900' => Auth::user()->role === 'user'
                        ])>
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-role-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-role-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-role-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-role-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" @class([
                    'inline-flex items-center justify-center p-2 rounded-md transition duration-150 ease-in-out',
                    'text-purple-500 hover:text-purple-900 hover:bg-purple-100' => Auth::user()->role === 'admin',
                    'text-blue-500 hover:text-blue-900 hover:bg-blue-100' => Auth::user()->role === 'worker',
                    'text-green-500 hover:text-green-900 hover:bg-green-100' => Auth::user()->role === 'user'
                ])>
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-role-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-role-responsive-nav-link>

            <x-role-responsive-nav-link :href="route('waste-reports.index')" :active="request()->routeIs('waste-reports.*')">
                {{ __('Waste Reports') }}
            </x-role-responsive-nav-link>

            <x-role-responsive-nav-link :href="route('schedules.index')" :active="request()->routeIs('schedules.*')">
                {{ __('Schedules') }}
            </x-role-responsive-nav-link>

            @if(Auth::user()->role === 'admin')
                <x-role-responsive-nav-link :href="route('sites.index')" :active="request()->routeIs('sites.*')">
                    {{ __('Sites') }}
                </x-role-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div @class([
            'pt-4 pb-1 border-t',
            'border-purple-200' => Auth::user()->role === 'admin',
            'border-blue-200' => Auth::user()->role === 'worker',
            'border-green-200' => Auth::user()->role === 'user'
        ])>
            <div class="px-4">
                <div @class([
                    'font-medium text-base',
                    'text-purple-800' => Auth::user()->role === 'admin',
                    'text-blue-800' => Auth::user()->role === 'worker',
                    'text-green-800' => Auth::user()->role === 'user'
                ])>{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-role-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-role-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-role-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-role-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
