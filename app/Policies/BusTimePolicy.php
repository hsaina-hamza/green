<?php

namespace App\Policies;

use App\Models\BusTime;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BusTimePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Anyone can view bus times
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BusTime $busTime): bool
    {
        return true; // Anyone can view individual bus times
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('worker') || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BusTime $busTime): bool
    {
        return $user->hasRole('worker') || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BusTime $busTime): bool
    {
        return $user->hasRole('worker') || $user->hasRole('admin');
    }
}
