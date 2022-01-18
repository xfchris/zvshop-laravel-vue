<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->get('/', fn () => view('index'));
Route::middleware(['auth', 'verified'])->get('/dashboard', fn () => view('dashboard'))->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::resource('users', App\Http\Controllers\UserController::class)->except(['index', 'create', 'store', 'show', 'delete']);
});

Route::get('admin/products/download/{dir}/{name}', [App\Http\Controllers\AdminProductController::class, 'exportDownload'])->name('products.exportDownload');

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('products/export', [App\Http\Controllers\AdminProductController::class, 'export'])->name('products.export');
    Route::resource('products', App\Http\Controllers\AdminProductController::class)->except(['show', 'delete']);
    Route::get('product/{product}/disable', [App\Http\Controllers\AdminProductController::class, 'disable'])->name('products.disable');
    Route::get('product/{product}/enable', [App\Http\Controllers\AdminProductController::class, 'enable'])->name('products.enable');
});

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('api')->name('api.')->group(function () {
    Route::post('users/setbanned/{user}', [App\Http\Controllers\Api\V1\ApiUserController::class, 'setbanned'])->name('users.setbanned');
    Route::delete('images/{image}', [App\Http\Controllers\Api\V1\ApiProductController::class, 'removeImage'])->name('images.destroy');
});

Route::middleware(['auth', 'verified', 'role:admin|clients'])->prefix('store')->name('store.')->group(function () {
    Route::get('order', [App\Http\Controllers\OrderController::class, 'show'])->name('order.show');
    Route::put('order/address', [App\Http\Controllers\OrderController::class, 'updateOrderAddress'])->name('order.updateAddress');
    Route::delete('order', [App\Http\Controllers\OrderController::class, 'deleteOrder'])->name('order.deleteOrder');
    Route::post('order/product/{product}', [App\Http\Controllers\OrderController::class, 'addProduct'])->name('order.addProduct');
    Route::delete('order/product/{product}', [App\Http\Controllers\OrderController::class, 'removeProduct'])->name('order.deleteProduct');
    Route::get('{category?}', [App\Http\Controllers\StoreProductController::class, 'index'])->name('products.index');
    Route::get('product/{product}', [App\Http\Controllers\StoreProductController::class, 'show'])->name('products.show');
});

Route::middleware(['auth', 'verified', 'role:admin|clients'])->prefix('payment')->name('payment.')->group(function () {
    Route::middleware(['check.ordertopay'])->post('pay', [App\Http\Controllers\PaymentController::class, 'pay'])->name('pay');
    Route::get('details/{order}', [App\Http\Controllers\PaymentController::class, 'details'])->name('details');
    Route::get('orders', [App\Http\Controllers\PaymentController::class, 'showUserOrders'])->name('orders');
    Route::middleware(['check.ordertoretrypay'])->post('retryPay/{order}', [App\Http\Controllers\PaymentController::class, 'retryPay'])
    ->name('retryPay');
});

Route::get('payment/changestatus/{reference_id}', [App\Http\Controllers\PaymentController::class, 'changeStatus'])->name('payment.changeStatus');

require __DIR__ . '/auth.php';
