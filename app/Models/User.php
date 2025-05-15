<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Traits\ManagesNotifications;

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
     * Get the user's notification preferences.
     */
    public function notificationPreferences()
    {
        return $this->hasMany(NotificationPreference::class);
    }

    /**
     * Get notification preferences for a specific category.
     */
    public function getNotificationPreferencesForCategory($category)
    {
        $preferences = $this->notificationPreferences()
            ->where('category', $category)
            ->first();

        if (!$preferences) {
            // Create default preferences if none exist
            $preferences = $this->notificationPreferences()->create([
                'category' => $category,
                'email' => true,
                'sms' => false,
            ]);
        }

        return [
            'email' => $preferences->email,
            'sms' => $preferences->sms,
        ];
    }

    /**
     * Check if the user has a specific role.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        if ($this->role === null) {
            return false;
        }
        return strtolower(trim($this->role)) === strtolower(trim($role));
    }

    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if the user is a worker.
     *
     * @return bool
     */
    public function isWorker(): bool
    {
        return $this->hasRole('worker');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * Check if the user is a regular user.
     *
     * @return bool
     */
    public function isUser(): bool
    {
        return $this->hasRole('user');
    }

    /**
     * Get the waste reports created by the user.
     */
    public function wasteReports()
    {
        return $this->hasMany(WasteReport::class);
    }

    /**
     * Get the waste reports assigned to the user.
     */
    public function assignedReports()
    {
        return $this->hasMany(WasteReport::class, 'worker_id');
    }

    /**
     * Get the comments created by the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Check if the user has any of the given roles.
     *
     * @param array|string $roles
     * @return bool
     */
    public function hasAnyRole($roles): bool
    {
        if (!is_array($roles)) {
            $roles = [$roles];
        }

        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the role attribute.
     *
     * @param string|null $value
     * @return string|null
     */
    public function getRoleAttribute(?string $value): ?string
    {
        return $value ? strtolower(trim($value)) : null;
    }

    /**
     * Set the role attribute.
     *
     * @param string|null $value
     * @return void
     */
    public function setRoleAttribute(?string $value): void
    {
        $this->attributes['role'] = $value ? strtolower(trim($value)) : null;
    }
    public function assignedWorker()
{
    return $this->belongsTo(User::class, 'assigned_worker_id');
}


}
