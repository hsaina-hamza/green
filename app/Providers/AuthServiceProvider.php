<?php

namespace App\Providers;

use App\Models\WasteReport;
use App\Models\Comment;
use App\Models\Site;
use App\Models\GarbageSchedule;
use App\Policies\WasteReportPolicy;
use App\Policies\CommentPolicy;
use App\Policies\SitePolicy;
use App\Policies\GarbageSchedulePolicy;
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
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Register role-based gates
        Gate::define('access-admin', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('access-worker', function ($user) {
            return $user->isWorker() || $user->isAdmin();
        });

        // Define gates for specific actions
        Gate::define('manage-users', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('manage-sites', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('manage-schedules', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('view-statistics', function ($user) {
            return $user->isAdmin() || $user->isWorker();
        });
    }
}
