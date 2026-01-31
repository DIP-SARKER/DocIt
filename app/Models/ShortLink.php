<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class ShortLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'long_url',
        'alias',
        'track_clicks',
        'clicks',
        'expires_at',
    ];

    protected static function booted()
    {
        static::creating(function ($link) {
            if (is_null($link->expires_at)) {
                $link->expires_at = now()->addDays(10)->toDateString();
            }
        });
    }

    // Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
