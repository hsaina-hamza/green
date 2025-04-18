<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true; // All authenticated users can view comments
    }

    public function view(User $user, Comment $comment)
    {
        return true; // All authenticated users can view individual comments
    }

    public function create(User $user)
    {
        return true; // All authenticated users can create comments
    }

    public function update(User $user, Comment $comment)
    {
        return $user->isAdmin() || $user->id === $comment->user_id;
    }

    public function delete(User $user, Comment $comment)
    {
        return $user->isAdmin() || $user->id === $comment->user_id;
    }
}
