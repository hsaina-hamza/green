<?php

namespace App\Policies;

use App\Models\GarbageSchedule;
use App\Models\Site;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GarbageSchedulePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view schedules
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, GarbageSchedule $schedule): bool
    {
        return true; // All authenticated users can view individual schedules
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Site $site): bool
    {
        // Only admins can create schedules
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GarbageSchedule $schedule): bool
    {
        // Only admins can update schedules
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GarbageSchedule $schedule): bool
    {
        // Only admins can delete schedules
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view schedule statistics.
     */
    public function viewStatistics(User $user): bool
    {
        // Admins and workers can view schedule statistics
        return $user->isAdmin() || $user->isWorker();
    }

    /**
     * Determine whether the user can view upcoming schedules.
     */
    public function viewUpcoming(User $user, Site $site): bool
    {
        // All authenticated users can view upcoming schedules
        return true;
    }

    /**
     * Determine whether the user can view past schedules.
     */
    public function viewPast(User $user, Site $site): bool
    {
        // All authenticated users can view past schedules
        return true;
    }

    /**
     * Determine whether the user can manage truck assignments.
     */
    public function manageTrucks(User $user): bool
    {
        // Only admins can manage truck assignments
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can export schedules.
     */
    public function export(User $user): bool
    {
        // Only admins can export schedules
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view schedule conflicts.
     */
    public function viewConflicts(User $user): bool
    {
        // Only admins can view schedule conflicts
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can manage recurring schedules.
     */
    public function manageRecurring(User $user): bool
    {
        // Only admins can manage recurring schedules
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view schedule optimization suggestions.
     */
    public function viewOptimization(User $user): bool
    {
        // Only admins can view schedule optimization suggestions
        return $user->isAdmin();
    }
}
