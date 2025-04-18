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
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view waste reports
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WasteReport $wasteReport): bool
    {
        return true; // All authenticated users can view individual waste reports
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create waste reports
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WasteReport $wasteReport): bool
    {
        // Admin can update any report
        if ($user->isAdmin()) {
            return true;
        }

        // Workers can update reports assigned to them
        if ($user->isWorker() && $wasteReport->assigned_worker_id === $user->id) {
            return true;
        }

        // Users can update their own reports if they're still pending
        return $user->id === $wasteReport->user_id && $wasteReport->status === 'pending';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WasteReport $wasteReport): bool
    {
        // Only admins and the original creator (if report is still pending) can delete
        return $user->isAdmin() || 
               ($user->id === $wasteReport->user_id && $wasteReport->status === 'pending');
    }

    /**
     * Determine whether the user can assign workers to the model.
     */
    public function assign(User $user, WasteReport $wasteReport): bool
    {
        // Only admins can assign workers to reports
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the status of the model.
     */
    public function updateStatus(User $user, WasteReport $wasteReport): bool
    {
        // Admin can update any report's status
        if ($user->isAdmin()) {
            return true;
        }

        // Workers can update status of reports assigned to them
        return $user->isWorker() && $wasteReport->assigned_worker_id === $user->id;
    }

    /**
     * Determine whether the user can view reports statistics.
     */
    public function viewStatistics(User $user): bool
    {
        // Only admins and workers can view statistics
        return $user->isAdmin() || $user->isWorker();
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
