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
        return true; // Anyone authenticated can view the list
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WasteReport $wasteReport): bool
    {
        return true; // Anyone authenticated can view details
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can create reports
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WasteReport $wasteReport): bool
    {
        // Users can update their own reports
        // Admins and workers can update any report
        return $user->id === $wasteReport->reported_by ||
               $user->isAdmin() ||
               $user->isWorker();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WasteReport $wasteReport): bool
    {
        // Only admins or the report creator can delete
        return $user->isAdmin() || $user->id === $wasteReport->reported_by;
    }

    /**
     * Determine whether the user can update the status of the model.
     */
    public function updateStatus(User $user, WasteReport $wasteReport): bool
    {
        // Only admins and workers can update status
        return $user->isAdmin() || $user->isWorker();
    }
}
