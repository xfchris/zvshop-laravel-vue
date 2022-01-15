<?php

namespace App\Providers;

use App\Strategies\GstImages\ContextImage;
use App\Strategies\GstImages\GstImgur;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Imgur\Client;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ContextImage::class, function () {
            return new ContextImage(new GstImgur(config('filesystems.disks.imgur'), new Client()));
        });
    }

    public function boot(): void
    {
        Paginator::useBootstrap();
        Blade::directive('money', fn ($amount) => "<?= '$' . number_format($amount, 2); ?>");
    }
}
