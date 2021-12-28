<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;

    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'banned_until',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = [
        'banned_until',
    ];

    protected $appends = [
        'check_banned_until',
    ];

    public function getCheckBannedUntilAttribute(): ?string
    {
        if ($this->banned_until && now()->lessThan($this->banned_until)) {
            return $this->banned_until ? $this->banned_until->format('d/m/Y') : '';
        }
        return null;
    }
}
