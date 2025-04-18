<?php

namespace App\Policies;

use App\Models\Site;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SitePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        // Allow all users (including guests) to view sites
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Site $site): bool
    {
        // Allow all users (including guests) to view individual sites
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admins can create sites
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Site $site): bool
    {
        // Only admins can update sites
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Site $site): bool
    {
        // Only admins can delete sites
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can manage schedules for the site.
     */
    public function manageSchedules(User $user, Site $site): bool
    {
        // Only admins can manage garbage collection schedules
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view site statistics.
     */
    public function viewStatistics(User $user, Site $site): bool
    {
        // Admins and workers can view site statistics
        return $user->hasAnyRole(['admin', 'worker']);
    }

    /**
     * Determine whether the user can view site reports.
     */
    public function viewReports(?User $user, Site $site): bool
    {
        // Allow all users (including guests) to view site reports
        return true;
    }

    /**
     * Determine whether the user can export site data.
     */
    public function export(User $user): bool
    {
        // Only admins can export site data
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the site's location on map.
     */
    public function viewMap(?User $user, Site $site): bool
    {
        // Allow all users (including guests) to view site locations on map
        return true;
    }

    /**
     * Determine whether the user can view site activity history.
     */
    public function viewHistory(User $user, Site $site): bool
    {
        // Admins and workers can view site history
        return $user->hasAnyRole(['admin', 'worker']);
    }

    /**
     * Determine whether the user can manage site workers.
     */
    public function manageWorkers(User $user, Site $site): bool
    {
        // Only admins can manage site workers
        return $user->isAdmin();
    }
}
