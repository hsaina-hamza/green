<?php

namespace App\Providers;

use App\Models\BusTime;
use App\Models\Comment;
use App\Models\GarbageSchedule;
use App\Models\Site;
use App\Models\User;
use App\Models\WasteReport;
use App\Policies\BusTimePolicy;
use App\Policies\CommentPolicy;
use App\Policies\GarbageSchedulePolicy;
use App\Policies\SitePolicy;
use App\Policies\UserPolicy;
use App\Policies\WasteReportPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        WasteReport::class => WasteReportPolicy::class,
        Comment::class => CommentPolicy::class,
        Site::class => SitePolicy::class,
        GarbageSchedule::class => GarbageSchedulePolicy::class,
        BusTime::class => BusTimePolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Register all policies
        $this->registerPolicies();

        // Define gates for role-based permissions
        Gate::define('access-admin', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('access-worker', function ($user) {
            return $user->hasAnyRole(['worker', 'admin']);
        });

        Gate::define('access-dashboard', function ($user) {
            return $user->hasAnyRole(['admin', 'worker']);
        });

        Gate::define('manage-users', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('manage-sites', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('manage-schedules', function ($user) {
            return $user->hasAnyRole(['admin', 'worker']);
        });

        Gate::define('view-statistics', function ($user) {
            return $user->hasAnyRole(['admin', 'worker']);
        });

        Gate::define('export-data', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('moderate-content', function ($user) {
            return $user->isAdmin();
        });

        // Super admin can do everything
        Gate::before(function ($user, $ability) {
            if ($user && $user->isAdmin()) {
                return true;
            }
        });
    }
}
