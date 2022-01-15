<?php

namespace App\JsonApi\V1\Categories;

use App\Models\Category;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;

class CategorySchema extends Schema
{
    public static string $model = Category::class;

    public function fields(): array
    {
        return [
            ID::make(),
            Str::make('name'),
            Str::make('slug'),
        ];
    }

    public function filters(): array
    {
        return [
            WhereIdIn::make($this),
        ];
    }

    public function pagination(): ?Paginator
    {
        return PagePagination::make();
    }
}
