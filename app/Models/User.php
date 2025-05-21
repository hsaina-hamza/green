<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Traits\ManagesNotifications;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, ManagesNotifications, HasRoles;

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
        'is_active',
        'last_login_at',
    ];

    protected $primaryKey = 'id';

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
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
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

    /**
     * Check if the user is a regular user.
     *
     * @return bool
     */
    public function isUser(): bool
    {
        return $this->hasRole('user');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
    public function assignedWorker()
{
    return $this->belongsTo(User::class, 'assigned_worker_id');
}


}
