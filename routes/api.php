<?php

use App\Http\Controllers\Api\V1\ApiProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;

Route::post('login', [App\Http\Controllers\Api\V1\ApiUserController::class, 'login'])->name('v1.login');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

JsonApiRoute::server('v1')
    ->middleware('auth:sanctum', 'cache.headers:private;max_age=1;must_revalidate;etag')
    ->resources(function ($server) {
        $server->resource('products', ApiProductController::class)
               ->parameter('id')
               ->actions(fn ($actions) => $actions->get('search'))
               ->relationships(fn ($relationships) => $relationships->hasOne('category'));
        $server->resource('categories', JsonApiController::class)
               ->parameter('id');
    });
