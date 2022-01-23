<?php

namespace App\Http\ViewComposers;

use App\Models\Category;
use Illuminate\View\View;

class CategoriesComposer
{
    public function compose(View $view): void
    {
        $view->with('categories', Category::getFromCache());
    }
}
