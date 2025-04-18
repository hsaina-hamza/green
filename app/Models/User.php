<?php

namespace App\Models;

use App\Models\Traits\ManagesNotifications;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, ManagesNotifications;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Valid user roles.
     *
     * @var array<string>
     */
    public const ROLES = [
        'admin',
        'worker',
        'user',
    ];

    /**
     * Get the waste reports created by the user.
     */
    public function wasteReports(): HasMany
    {
        return $this->hasMany(WasteReport::class);
    }

    /**
     * Get the waste reports assigned to the user.
     */
    public function assignedReports(): HasMany
    {
        return $this->hasMany(WasteReport::class, 'assigned_worker_id');
    }

    /**
     * Get the comments made by the user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is a worker.
     */
    public function isWorker(): bool
    {
        return $this->role === 'worker';
    }

    /**
     * Check if the user is a regular user.
     */
    public function isRegularUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Get the user's active assigned reports.
     */
    public function getActiveAssignedReports(): HasMany
    {
        return $this->assignedReports()
            ->whereNotIn('status', ['completed', 'cancelled']);
    }

    /**
     * Get the user's pending reports.
     */
    public function getPendingReports(): HasMany
    {
        return $this->wasteReports()
            ->where('status', 'pending');
    }

    /**
     * Get the user's completed reports.
     */
    public function getCompletedReports(): HasMany
    {
        return $this->wasteReports()
            ->where('status', 'completed');
    }

    /**
     * Get the user's notification preferences for a specific category.
     */
    public function getNotificationPreferencesForCategory(string $category): array
    {
        return $this->notificationPreferences()
            ->where('category', $category)
            ->get()
            ->mapWithKeys(function ($preference) {
                return [$preference->channel => $preference->enabled];
            })
            ->toArray();
    }

    /**
     * Route notifications for the mail channel.
     *
     * @return string
     */
    public function routeNotificationForMail(): string
    {
        return $this->email;
    }

    /**
     * Route notifications for the SMS channel.
     *
     * @return string|null
     */
    public function routeNotificationForSms(): ?string
    {
        return $this->phone_number;
    }

    /**
     * Get the user's work statistics.
     */
    public function getWorkStatistics(): array
    {
        $assignedReports = $this->assignedReports();
        
        return [
            'total_assigned' => $assignedReports->count(),
            'completed' => $assignedReports->where('status', 'completed')->count(),
            'in_progress' => $assignedReports->where('status', 'in_progress')->count(),
            'pending' => $assignedReports->where('status', 'pending')->count(),
            'completion_rate' => $this->calculateCompletionRate(),
            'average_completion_time' => $this->calculateAverageCompletionTime(),
        ];
    }

    /**
     * Calculate the user's report completion rate.
     */
    protected function calculateCompletionRate(): float
    {
        $total = $this->assignedReports()->count();
        if ($total === 0) {
            return 0;
        }

        $completed = $this->assignedReports()
            ->where('status', 'completed')
            ->count();

        return round(($completed / $total) * 100, 2);
    }

    /**
     * Calculate the user's average report completion time in hours.
     */
    protected function calculateAverageCompletionTime(): float
    {
        $completedReports = $this->assignedReports()
            ->where('status', 'completed')
            ->get();

        if ($completedReports->isEmpty()) {
            return 0;
        }

        $totalHours = $completedReports->sum(function ($report) {
            $start = $report->assigned_at ?? $report->created_at;
            $end = $report->completed_at;
            return $end->diffInHours($start);
        });

        return round($totalHours / $completedReports->count(), 1);
    }
}
