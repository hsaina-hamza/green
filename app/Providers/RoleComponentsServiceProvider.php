<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class RoleComponentsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register role-based components
        Blade::component('role-button', \App\View\Components\RoleButton::class);
        Blade::component('role-input', \App\View\Components\RoleInput::class);
        Blade::component('role-select', \App\View\Components\RoleSelect::class);
        Blade::component('role-form', \App\View\Components\RoleForm::class);
        Blade::component('role-container', \App\View\Components\RoleContainer::class);
        Blade::component('role-nav-link', \App\View\Components\RoleNavLink::class);
        Blade::component('role-responsive-nav-link', \App\View\Components\RoleResponsiveNavLink::class);
        Blade::component('role-dropdown-link', \App\View\Components\RoleDropdownLink::class);

        // Register role-based directives
        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->role === 'admin';
        });

        Blade::if('worker', function () {
            return auth()->check() && auth()->user()->role === 'worker';
        });

        Blade::if('user', function () {
            return auth()->check() && auth()->user()->role === 'user';
        });
    }
}
