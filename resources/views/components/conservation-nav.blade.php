<div class="border-b border-gray-200 bg-white shadow-sm">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="hidden sm:flex sm:space-x-2 space-x-reverse">
                    <!-- Conservation Tips -->
                    <x-role-nav-link :href="route('conservation-tips')" :active="request()->routeIs('conservation-tips')" class="flex items-center">
                        <i class="fas fa-leaf mr-2 text-green-500"></i>
                        {{ __('نصائح الحفاظ') }}
                    </x-role-nav-link>

                    @admin
                        <!-- Report Issue -->
                        <x-role-nav-link :href="route('waste-reports.create')" :active="request()->routeIs('waste-reports.create')" class="flex items-center">
                            <i class="fas fa-flag mr-2 text-red-500"></i>
                            {{ __('تبليغ عن مشكلة') }}
                        </x-role-nav-link>
                    @endadmin

                    @worker
                        <!-- Collection Schedule -->
                        <x-role-nav-link :href="route('schedules.index')" :active="request()->routeIs('schedules.index')" class="flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                            {{ __('جدول الجمع') }}
                        </x-role-nav-link>
                    @endworker

                    @user
                        <!-- My Reports -->
                        <x-role-nav-link :href="route('waste-reports.index')" :active="request()->routeIs('waste-reports.index')" class="flex items-center">
                            <i class="fas fa-clipboard-list mr-2 text-purple-500"></i>
                            {{ __('تقاريري') }}
                        </x-role-nav-link>
                    @enduser
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center">
                <!-- Report Waste Issue Button -->
                <x-role-button :href="route('waste-reports.create')" class="ml-4 flex items-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-300">
                    <i class="fas fa-trash-alt mr-2"></i>
                    {{ __('تبليغ عن نفايات') }}
                </x-role-button>
            </div>
        </div>
    </nav>
</div>

<style>
    .role-nav-link {
        transition: all 0.3s ease;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
    }
    .role-nav-link:hover {
        background-color: #f3f4f6;
    }
    .role-nav-link.active {
        border-bottom: 2px solid #3b82f6;
        color: #3b82f6;
        font-weight: 500;
    }
    .role-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
</style>