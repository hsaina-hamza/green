<div class="border-b border-gray-200">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="hidden sm:flex sm:space-x-8">
                    <x-role-nav-link :href="route('conservation-tips')" :active="request()->routeIs('conservation-tips')">
                        {{ __('Conservation Tips') }}
                    </x-role-nav-link>

                    @admin
                        <x-role-nav-link :href="route('waste-reports.create')" :active="request()->routeIs('waste-reports.create')">
                            {{ __('Report Issue') }}
                        </x-role-nav-link>
                    @endadmin

                    @worker
                        <x-role-nav-link :href="route('schedules.index')" :active="request()->routeIs('schedules.index')">
                            {{ __('Collection Schedule') }}
                        </x-role-nav-link>
                    @endworker

                    @user
                        <x-role-nav-link :href="route('waste-reports.index')" :active="request()->routeIs('waste-reports.index')">
                            {{ __('My Reports') }}
                        </x-role-nav-link>
                    @enduser
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center">
                <x-role-button :href="route('waste-reports.create')" class="ml-4">
                    {{ __('Report Waste Issue') }}
                </x-role-button>
            </div>
        </div>
    </nav>
</div>
