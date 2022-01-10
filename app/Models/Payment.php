<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = ['status', 'requestId', 'processUrl', 'products', 'totalAmount', 'reference_id'];

    protected $casts = [
        'products' => 'array',
    ];

    protected $appends = [
        'totalProducts',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getTotalProductsAttribute(): int
    {
        return array_reduce($this->products, fn ($total, $item) => $total += $item['pivot']['quantity']);
    }
}
