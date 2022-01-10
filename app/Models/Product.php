<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;

    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'price',
        'quantity',
    ];

    protected $dates = ['deleted_at'];

    protected $appends = [
        'poster',
    ];

    public function toSearchableArray(): array
    {
        return $this->only(['id', 'name', 'description', 'deleted_at', 'category_id']);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function getPosterAttribute(): string
    {
        $image = $this->images()->latest()->first();
        return ($image) ? $image->url : config('constants.default_poster');
    }
}
