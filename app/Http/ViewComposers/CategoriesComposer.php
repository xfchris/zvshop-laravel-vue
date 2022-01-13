<?php

namespace App\Http\ViewComposers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CategoriesComposer
{
    public function compose(View $view): void
    {
        $view->with('categories', DB::table('categories')->select('id', 'name', 'slug')->orderBy('name')->get());
    }
}
