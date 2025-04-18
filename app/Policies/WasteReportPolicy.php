<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WasteReport;
use Illuminate\Auth\Access\HandlesAuthorization;

class WasteReportPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true; // All authenticated users can view reports
    }

    public function view(User $user, WasteReport $wasteReport)
    {
        return true; // All authenticated users can view individual reports
    }

    public function create(User $user)
    {
        return true; // All authenticated users can create reports
    }

    public function update(User $user, WasteReport $wasteReport)
    {
        return $user->isAdmin() || 
               $user->id === $wasteReport->user_id || 
               ($user->isWorker() && $user->id === $wasteReport->assigned_worker_id);
    }

    public function delete(User $user, WasteReport $wasteReport)
    {
        return $user->isAdmin() || $user->id === $wasteReport->user_id;
    }

    public function assign(User $user, WasteReport $wasteReport)
    {
        return $user->isAdmin();
    }

    public function updateStatus(User $user, WasteReport $wasteReport)
    {
        return $user->isAdmin() || 
               ($user->isWorker() && $user->id === $wasteReport->assigned_worker_id);
    }
}
