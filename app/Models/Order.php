<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'name_receive', 'address', 'phone'];

    protected $appends = [
        'totalAmount',
        'totalProducts',
        'referencePayment',
    ];

    public function getReferencePaymentAttribute()
    {
        return $this->id . '_' . $this->created_at->timestamp;
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class)->latest();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalAmountAttribute(): float
    {
        if ($this->payment) {
            return $this->payment->totalAmount;
        }

        $total = 0;
        foreach ($this->products as $product) {
            $total += $product->pivot->quantity * $product->price;
        }
        return $total;
    }

    public function getTotalProductsAttribute(): int
    {
        $count = 0;
        foreach ($this->products as $product) {
            $count += $product->pivot->quantity;
        }
        return $count;
    }
}
