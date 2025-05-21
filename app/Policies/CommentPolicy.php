<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use App\Models\WasteReport;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view comments
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Comment $comment): bool
    {
        return true; // All authenticated users can view individual comments
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, WasteReport $wasteReport): bool
    {
        // Admin can always comment
        if ($user->isAdmin()) {
            return true;
        }

        // Workers can comment on reports assigned to them
        if ($user->isWorker() && $wasteReport->worker_id === $user->id) {
            return true;
        }

        // Users can comment on their own reports
        if ($wasteReport->reported_by === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        // Users can only edit their own comments
        return $user->id === $comment->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        // Admin can delete any comment
        if ($user->isAdmin()) {
            return true;
        }

        // Users can only delete their own comments
        return $user->id === $comment->user_id;
    }

    /**
     * Determine whether the user can moderate comments.
     */
    public function moderate(User $user): bool
    {
        // Only admins can moderate comments
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view comment history.
     */
    public function viewHistory(User $user, WasteReport $wasteReport): bool
    {
        // Admin can view all comment history
        if ($user->isAdmin()) {
            return true;
        }

        // Workers can view comment history of reports assigned to them
        if ($user->isWorker() && $wasteReport->worker_id === $user->id) {
            return true;
        }

        // Users can view comment history of their own reports
        return $wasteReport->reported_by === $user->id;
    }

    /**
     * Determine whether the user can export comments.
     */
    public function export(User $user): bool
    {
        // Only admins can export comments
        return $user->isAdmin();
    }
}
