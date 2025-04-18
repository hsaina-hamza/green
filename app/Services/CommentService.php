<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\WasteReport;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CommentService extends BaseService
{
    /**
     * Create a new comment.
     */
    public function createComment(array $data): Comment
    {
        return $this->executeInTransaction(function () use ($data) {
            $comment = $this->create(Comment::class, $data);
            $this->logAction('create_comment', [
                'comment_id' => $comment->id,
                'waste_report_id' => $comment->waste_report_id
            ]);
            
            return $comment;
        });
    }

    /**
     * Update an existing comment.
     */
    public function updateComment(Comment $comment, array $data): bool
    {
        return $this->executeInTransaction(function () use ($comment, $data) {
            $updated = $this->update($comment, $data);
            $this->logAction('update_comment', [
                'comment_id' => $comment->id,
                'waste_report_id' => $comment->waste_report_id
            ]);
            
            return $updated;
        });
    }

    /**
     * Delete a comment.
     */
    public function deleteComment(Comment $comment): bool
    {
        return $this->executeInTransaction(function () use ($comment) {
            $deleted = $this->delete($comment);
            $this->logAction('delete_comment', [
                'comment_id' => $comment->id,
                'waste_report_id' => $comment->waste_report_id
            ]);
            
            return $deleted;
        });
    }

    /**
     * Get comments for a waste report.
     */
    public function getReportComments(WasteReport $report, int $perPage = 10): LengthAwarePaginator
    {
        return Comment::with(['user'])
            ->where('waste_report_id', $report->id)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    /**
     * Get recent comments for a waste report.
     */
    public function getRecentComments(WasteReport $report, int $limit = 5): Collection
    {
        return Comment::with(['user'])
            ->where('waste_report_id', $report->id)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get comments by a specific user.
     */
    public function getUserComments(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return Comment::with(['wasteReport', 'wasteReport.site'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    /**
     * Get comments statistics.
     */
    public function getStatistics(WasteReport $report = null): array
    {
        $query = Comment::query();

        if ($report) {
            $query->where('waste_report_id', $report->id);
        }

        return [
            'total_comments' => $query->count(),
            'comments_by_role' => [
                'admin' => $query->whereHas('user', function ($q) {
                    $q->where('role', 'admin');
                })->count(),
                'worker' => $query->whereHas('user', function ($q) {
                    $q->where('role', 'worker');
                })->count(),
                'user' => $query->whereHas('user', function ($q) {
                    $q->where('role', 'user');
                })->count(),
            ],
            'recent_activity' => $query->where('created_at', '>=', now()->subDays(7))->count(),
        ];
    }

    /**
     * Check if user can modify the comment.
     */
    public function canModifyComment(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || $user->role === 'admin';
    }

    /**
     * Get latest comments across all reports.
     */
    public function getLatestComments(int $limit = 10): Collection
    {
        return Comment::with(['user', 'wasteReport', 'wasteReport.site'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Get comments requiring attention (e.g., unanswered user comments).
     */
    public function getCommentsRequiringAttention(): Collection
    {
        return Comment::with(['user', 'wasteReport'])
            ->whereHas('user', function ($query) {
                $query->where('role', 'user');
            })
            ->whereDoesntHave('wasteReport.comments', function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->whereIn('role', ['admin', 'worker']);
                })->where('created_at', '>', Comment::raw('comments.created_at'));
            })
            ->orderByDesc('created_at')
            ->get();
    }
}
