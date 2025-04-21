<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class UserService extends BaseService
{
    /**
     * Create a new user.
     */
    public function createUser(array $data): User
    {
        return $this->executeInTransaction(function () use ($data) {
            $data['password'] = Hash::make($data['password']);
            
            $user = $this->create(User::class, $data);
            $this->logAction('create_user', ['user_id' => $user->id]);
            
            return $user;
        });
    }

    /**
     * Update an existing user.
     */
    public function updateUser(User $user, array $data): bool
    {
        return $this->executeInTransaction(function () use ($user, $data) {
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $updated = $this->update($user, $data);
            $this->logAction('update_user', ['user_id' => $user->id]);
            
            return $updated;
        });
    }

    /**
     * Delete a user.
     */
    public function deleteUser(User $user): bool
    {
        return $this->executeInTransaction(function () use ($user) {
            $deleted = $this->delete($user);
            $this->logAction('delete_user', ['user_id' => $user->id]);
            
            return $deleted;
        });
    }

    /**
     * Get users by role.
     */
    public function getUsersByRole(string $role, int $perPage = 10): LengthAwarePaginator
    {
        return User::where('role', $role)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get available workers.
     */
    public function getAvailableWorkers(): Collection
    {
        return User::where('role', 'worker')
            ->whereDoesntHave('assignedReports', function ($query) {
                $query->whereIn('status', ['pending', 'in_progress']);
            })
            ->get();
    }

    /**
     * Get worker workload statistics.
     */
    public function getWorkerWorkload(User $worker): array
    {
        if ($worker->role !== 'worker') {
            throw new \InvalidArgumentException('User must be a worker');
        }

        $reports = $worker->assignedReports();

        return [
            'active_reports' => $reports->whereIn('status', ['pending', 'in_progress'])->count(),
            'completed_reports' => $reports->where('status', 'completed')->count(),
            'completion_rate' => $this->calculateCompletionRate($worker),
            'average_completion_time' => $this->calculateAverageCompletionTime($worker),
        ];
    }

    /**
     * Calculate worker completion rate.
     */
    private function calculateCompletionRate(User $worker): float
    {
        $totalReports = $worker->assignedReports()->count();
        if ($totalReports === 0) {
            return 0;
        }

        $completedReports = $worker->assignedReports()
            ->where('status', 'completed')
            ->count();

        return round(($completedReports / $totalReports) * 100, 2);
    }

    /**
     * Calculate average completion time for reports.
     */
    private function calculateAverageCompletionTime(User $worker): ?float
    {
        $completedReports = $worker->assignedReports()
            ->where('status', 'completed')
            ->get();

        if ($completedReports->isEmpty()) {
            return null;
        }

        $totalHours = 0;
        foreach ($completedReports as $report) {
            $assigned = Carbon::parse($report->assigned_at);
            $completed = Carbon::parse($report->updated_at);
            $totalHours += $assigned->diffInHours($completed);
        }

        return round($totalHours / $completedReports->count(), 2);
    }

    /**
     * Get user activity statistics.
     */
    public function getUserActivity(User $user): array
    {
        return [
            'reports' => [
                'created' => $user->wasteReports()->count(),
                'pending' => $user->wasteReports()->where('status', 'pending')->count(),
                'in_progress' => $user->wasteReports()->where('status', 'in_progress')->count(),
                'completed' => $user->wasteReports()->where('status', 'completed')->count(),
            ],
            'comments' => [
                'total' => $user->comments()->count(),
                'recent' => $user->comments()
                    ->where('created_at', '>=', now()->subDays(7))
                    ->count(),
            ],
            'assigned_reports' => $user->role === 'worker' ? [
                'total' => $user->assignedReports()->count(),
                'active' => $user->assignedReports()
                    ->whereIn('status', ['pending', 'in_progress'])
                    ->count(),
            ] : null,
        ];
    }

    /**
     * Send password reset link.
     */
    public function sendPasswordResetLink(string $email): string
    {
        return Password::sendResetLink(['email' => $email]);
    }

    /**
     * Get user performance metrics.
     */
    public function getUserPerformance(User $user): array
    {
        $metrics = [
            'total_reports' => $user->wasteReports()->count(),
            'total_comments' => $user->comments()->count(),
            'response_rate' => $this->calculateResponseRate($user),
        ];

        if ($user->role === 'worker') {
            $metrics = array_merge($metrics, [
                'completion_rate' => $this->calculateCompletionRate($user),
                'average_completion_time' => $this->calculateAverageCompletionTime($user),
            ]);
        }

        return $metrics;
    }

    /**
     * Calculate user response rate to comments.
     */
    private function calculateResponseRate(User $user): float
    {
        $receivedComments = $user->wasteReports()
            ->withCount('comments')
            ->get()
            ->sum('comments_count');

        if ($receivedComments === 0) {
            return 0;
        }

        $userResponses = $user->comments()
            ->whereIn('waste_report_id', $user->wasteReports()->pluck('id'))
            ->count();

        return round(($userResponses / $receivedComments) * 100, 2);
    }
}
