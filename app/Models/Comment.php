<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'waste_report_id',
        'content',
    ];

    /**
<<<<<<< HEAD
=======
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
>>>>>>> 231977c8c8cc7dfc8f6b499ce1a4fff2b8175808
     * The relationships that should always be loaded.
     *
     * @var array<string>
     */
    protected $with = ['user'];

    /**
     * Get the user that created the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the waste report that owns the comment.
     */
    public function wasteReport(): BelongsTo
    {
        return $this->belongsTo(WasteReport::class);
    }
}
