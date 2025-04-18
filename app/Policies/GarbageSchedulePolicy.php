<?php

namespace App\Policies;

use App\Models\GarbageSchedule;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GarbageSchedulePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true; // All authenticated users can view schedules
    }

    public function view(User $user, GarbageSchedule $schedule)
    {
        return true; // All authenticated users can view individual schedules
    }

    public function create(User $user)
    {
        return $user->isAdmin(); // Only admins can create schedules
    }

    public function update(User $user, GarbageSchedule $schedule)
    {
        return $user->isAdmin(); // Only admins can update schedules
    }

    public function delete(User $user, GarbageSchedule $schedule)
    {
        return $user->isAdmin(); // Only admins can delete schedules
    }
}
