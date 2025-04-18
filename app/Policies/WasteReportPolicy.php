<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WasteReport;
use Illuminate\Auth\Access\HandlesAuthorization;

class WasteReportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        // Allow all users (including guests) to view waste reports
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, WasteReport $wasteReport): bool
    {
        // Allow all users (including guests) to view individual waste reports
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user): bool
    {
        // Allow any authenticated user to create waste reports
        return $user !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WasteReport $wasteReport): bool
    {
        // Allow users to update their own reports
        if ($user->id === $wasteReport->user_id) {
            return true;
        }

        // Allow workers and admins to update any report
        return $user->hasAnyRole(['worker', 'admin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WasteReport $wasteReport): bool
    {
        // Allow users to delete their own reports
        if ($user->id === $wasteReport->user_id) {
            return true;
        }

        // Allow admins to delete any report
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the status of the model.
     */
    public function updateStatus(User $user, WasteReport $wasteReport): bool
    {
        // Only workers and admins can update status
        return $user->hasAnyRole(['worker', 'admin']);
    }

    /**
     * Determine whether the user can assign workers to the model.
     */
    public function assign(User $user, WasteReport $wasteReport): bool
    {
        // Only admins can assign workers
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can add comments to the model.
     */
    public function comment(User $user, WasteReport $wasteReport): bool
    {
        // Any authenticated user can comment
        return true;
    }

    /**
     * Determine whether the user can view report statistics.
     */
    public function viewStatistics(User $user): bool
    {
        // Only workers and admins can view statistics
        return $user->hasAnyRole(['worker', 'admin']);
    }

    /**
     * Determine whether the user can export reports.
     */
    public function export(User $user): bool
    {
        // Only admins can export reports
        return $user->isAdmin();
    }
}
