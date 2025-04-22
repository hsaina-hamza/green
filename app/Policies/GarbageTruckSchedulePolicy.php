<?php

namespace App\Policies;

use App\Models\User;
use App\Models\GarbageTruckSchedule;
use Illuminate\Auth\Access\HandlesAuthorization;

class GarbageTruckSchedulePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any garbage truck schedules.
     */
    public function viewAny(User $user)
    {
        return $user->hasAnyRole(['admin', 'worker']);
    }

    /**
     * Determine whether the user can view the garbage truck schedule.
     */
    public function view(User $user, GarbageTruckSchedule $schedule)
    {
        return $user->hasAnyRole(['admin', 'worker']);
    }

    /**
     * Determine whether the user can create garbage truck schedules.
     */
    public function create(User $user)
    {
        return $user->hasAnyRole(['admin', 'worker']);
    }

    /**
     * Determine whether the user can update the garbage truck schedule.
     */
    public function update(User $user, GarbageTruckSchedule $schedule)
    {
        return $user->hasAnyRole(['admin', 'worker']);
    }

    /**
     * Determine whether the user can delete the garbage truck schedule.
     */
    public function delete(User $user, GarbageTruckSchedule $schedule)
    {
        return $user->hasRole('admin');
    }
}
