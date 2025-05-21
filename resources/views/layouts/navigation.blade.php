<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/ozen.png') }}" class="block h-9 w-auto" alt="" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-reverse space-x-8 sm:-my-px sm:mr-10 sm:flex flex-row-reverse">
                    <!-- Public Routes -->
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        الرئيسية
                    </x-nav-link>
                    <x-nav-link :href="route('conservationTips.tips')" :active="request()->routeIs('conservationTips.tips')">
                        نصائح بيئية
                    </x-nav-link>
                    <x-nav-link :href="route('waste-map')" :active="request()->routeIs('waste-map')">
                        خريطة النفايات
                    </x-nav-link>
                    <x-nav-link :href="route('bus-times.index')" :active="request()->routeIs('bus-times.*')">
                        مواعيد الحافلات
                    </x-nav-link>
                    <x-nav-link :href="route('sites.index')" :active="request()->routeIs('sites.*')">
                        المواقع
                    </x-nav-link>
                    <x-nav-link :href="route('schedules.index')" :active="request()->routeIs('schedules.*')">
                        جداول النظافة
                    </x-nav-link>

                    @auth
                        <x-nav-link :href="route('waste-reports.create')" :active="request()->routeIs('waste-reports.create')">
                            الإبلاغ عن نفايات
                        </x-nav-link>
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            لوحة التحكم
                        </x-nav-link>

                        @if(Auth::user()->hasRole('admin'))
                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                إدارة المستخدمين
                            </x-nav-link>
                            <x-nav-link :href="route('admin.statistics')" :active="request()->routeIs('admin.statistics')">
                                الإحصائيات
                            </x-nav-link>
                            <x-nav-link :href="route('admin.sites.index')" :active="request()->routeIs('admin.sites.*')">
                                إدارة المواقع
                            </x-nav-link>
                            <x-nav-link :href="route('admin.schedules.index')" :active="request()->routeIs('admin.schedules.*')">
                                إدارة الجداول
                            </x-nav-link>
                        @endif

                        @if(Auth::user()->hasRole('worker'))
                            <x-nav-link :href="route('worker.bus-schedules.index')" :active="request()->routeIs('worker.bus-schedules.*')">
                                جداول العمل
                            </x-nav-link>
                            <x-nav-link :href="route('statistics')" :active="request()->routeIs('statistics')">
                                الإحصائيات
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:mr-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name ?? 'الحساب' }}</div>

                            <div class="mr-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @auth
                            <x-dropdown-link :href="route('profile.edit')">
                                الملف الشخصي
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                    this.closest('form').submit();">
                                    تسجيل الخروج
                                </x-dropdown-link>
                            </form>
                        @else
                            <x-dropdown-link :href="route('login')">
                                تسجيل الدخول
                            </x-dropdown-link>
                            @if (Route::has('register'))
                                <x-dropdown-link :href="route('register')">
                                    إنشاء حساب
                                </x-dropdown-link>
                            @endif
                        @endauth
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
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
            <!-- Public Routes -->
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                الرئيسية
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('conservationTips.tips')" :active="request()->routeIs('conservationTips.tips')">
                نصائح بيئية
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('waste-map')" :active="request()->routeIs('waste-map')">
                خريطة النفايات
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('bus-times.index')" :active="request()->routeIs('bus-times.*')">
                مواعيد الحافلات
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('sites.index')" :active="request()->routeIs('sites.*')">
                المواقع
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('schedules.index')" :active="request()->routeIs('schedules.*')">
                جداول النظافة
            </x-responsive-nav-link>

            @auth
                <x-responsive-nav-link :href="route('waste-reports.create')" :active="request()->routeIs('waste-reports.create')">
                    الإبلاغ عن نفايات
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    لوحة التحكم
                </x-responsive-nav-link>

                @if(Auth::user()->hasRole('admin'))
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        إدارة المستخدمين
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.statistics')" :active="request()->routeIs('admin.statistics')">
                        الإحصائيات
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.sites.index')" :active="request()->routeIs('admin.sites.*')">
                        إدارة المواقع
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.schedules.index')" :active="request()->routeIs('admin.schedules.*')">
                        إدارة الجداول
                    </x-responsive-nav-link>
                @endif

                @if(Auth::user()->hasRole('worker'))
                    <x-responsive-nav-link :href="route('worker.bus-schedules.index')" :active="request()->routeIs('worker.bus-schedules.*')">
                        جداول العمل
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('statistics')" :active="request()->routeIs('statistics')">
                        الإحصائيات
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                        الملف الشخصي
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" :active="false"
                            onclick="event.preventDefault();
                            this.closest('form').submit();">
                            تسجيل الخروج
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
                        تسجيل الدخول
                    </x-responsive-nav-link>
                    @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">
                            إنشاء حساب
                        </x-responsive-nav-link>
                    @endif
                </div>
            @endauth
        </div>
    </div>
</nav>
