<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'nik',
        'initial',
        'email',
        'password',
        'role',
        'status',
        'failed_login_attempts',
        'locked_at',
        'password_changed_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'last_login' => 'datetime',
        'locked_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function passwordHistories()
    {
        return $this->hasMany(PasswordHistory::class)->latest()->limit(5);
    }

    public function isLocked(): bool
    {
        return $this->status === 'inactive' || !is_null($this->locked_at);
    }

    public function lockAccount(): void
    {
        $this->update([
            'status' => 'inactive',
            'locked_at' => now(),
        ]);
    }

    public function unlockAccount(): void
    {
        $this->update([
            'status' => 'active',
            'locked_at' => null,
            'failed_login_attempts' => 0,
        ]);
    }
}