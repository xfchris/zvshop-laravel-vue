<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'name_receive', 'address', 'phone'];

    protected $appends = [
        'totalAmount',
        'totalProducts',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTotalAmountAttribute(): float
    {
        $total = 0;
        foreach ($this->products as $product) {
            $total += $product->pivot->quantity * ($product->pivot->price ?? $product->price);
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
