<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    use HasFactory;

    public static function getFromCache($keyBy = 'name'): Collection
    {
        return Cache::remember(
            'categories_' . $keyBy,
            config('constants.cache_categories_duration'),
            fn () => self::select('id', 'name', 'slug')->orderBy('name')->get()->keyBy($keyBy),
        );
    }
}
