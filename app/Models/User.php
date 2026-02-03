<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_banned'
    ];
    public const ROLE_USER = 'user';
    public const ROLE_MOD = 'mod';
    public const ROLE_ADMIN = 'admin';

    public static function roles(): array
    {
        return [
            self::ROLE_USER,
            self::ROLE_MOD,
            self::ROLE_ADMIN,
        ];
    }

    // ✅ Role helpers
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isModerator(): bool
    {
        return $this->role === self::ROLE_MOD;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    // ✅ Ban helper
    public function isBanned(): bool
    {
        return (bool) $this->is_banned; // or $this->is_banned
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_banned' => 'boolean'
        ];
    }
    // App\Models\User.php
    public function tasks()
    {
        return $this->hasMany(\App\Models\Task::class);
    }

    public function documents()
    {
        return $this->hasMany(\App\Models\Document::class);
    }

    public function shortLinks()
    {
        return $this->hasMany(\App\Models\ShortLink::class);
    }

}
