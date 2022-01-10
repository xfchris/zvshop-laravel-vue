<?php

namespace App\Models;

use App\Constants\AppConstants;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
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
        'surname',
        'email',
        'password',
        'banned_until',
        'document_type',
        'document',
        'address',
        'phone',
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
        'order',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getOrderAttribute(): Order
    {
        return $this->orders()->with('products:id,name,price,quantity')
                    ->firstOrCreate(['status' => AppConstants::CREATED], ['currency' => config('constants.currency')]);
    }

    public function getCheckBannedUntilAttribute(): ?string
    {
        if ($this->banned_until && now()->lessThan($this->banned_until)) {
            return $this->banned_until ? $this->banned_until->format('d/m/Y') : '';
        }
        return null;
    }
}
