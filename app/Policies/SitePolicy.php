<?php

namespace App\Policies;

use App\Models\Site;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SitePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true; // All authenticated users can view sites
    }

    public function view(User $user, Site $site)
    {
        return true; // All authenticated users can view individual sites
    }

    public function create(User $user)
    {
        return $user->isAdmin(); // Only admins can create sites
    }

    public function update(User $user, Site $site)
    {
        return $user->isAdmin(); // Only admins can update sites
    }

    public function delete(User $user, Site $site)
    {
        return $user->isAdmin(); // Only admins can delete sites
    }

    public function manageSchedule(User $user, Site $site)
    {
        return $user->isAdmin(); // Only admins can manage garbage collection schedules
    }
}
