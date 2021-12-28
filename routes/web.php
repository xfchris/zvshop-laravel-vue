<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->get('/', function () {
    return view('index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', App\Http\Controllers\AdminUserController::class)->except(['create', 'store', 'show', 'delete']);
    Route::resource('products', App\Http\Controllers\AdminProductController::class)->except(['show', 'delete']);
    Route::get('product/{product}/disable', [App\Http\Controllers\AdminProductController::class, 'disable'])->name('products.disable');
    Route::get('product/{product}/enable', [App\Http\Controllers\AdminProductController::class, 'enable'])->name('products.enable');
});

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('api')->name('api.')->group(function () {
    Route::post('users/activate-inactivate-user/{user}', [App\Http\Controllers\Api\ApiUserController::class, 'activateInactivateUser'])
    ->name('users.activateInactivateUser');
    Route::delete('images/{image}', [App\Http\Controllers\Api\ApiProductController::class, 'removeImage'])->name('images.destroy');
});

Route::middleware(['auth', 'role:admin|clients'])->prefix('store')->name('store.')->group(function () {
    Route::get('{category?}', [App\Http\Controllers\StoreProductController::class, 'index'])->name('products.index');
    Route::get('product/{product}', [App\Http\Controllers\StoreProductController::class, 'show'])->name('products.show');
});

require __DIR__ . '/auth.php';
