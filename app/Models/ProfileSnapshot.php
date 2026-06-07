<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileSnapshot extends Model
{
    protected $fillable = [
        'profile_id',
        'followers_count',
        'following_count',
        'posts_count',
        'snapshot_at',
    ];

    protected $casts = [
        'snapshot_at' => 'datetime',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }
}