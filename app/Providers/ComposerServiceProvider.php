<?php

namespace App\Providers;

use App\Http\ViewComposers\CategoriesComposer;
use App\Strategies\GstImages\ContextImage;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    public function boot(ContextImage $contextImage): void
    {
        view()->composer(['store.products.index', 'store.products.show', 'store.orders.show'], function ($view) use ($contextImage) {
            $view->with(['contextImage' => $contextImage]);
        });

        view()->composer(['store.products.index', 'store.products.show'], CategoriesComposer::class);
    }
}
