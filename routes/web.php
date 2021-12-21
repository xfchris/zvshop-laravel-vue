<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['guest'])->get('/', function () {
    return view('index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', App\Http\Controllers\UserController::class);
});

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('api')->name('api.')->group(function () {
    Route::post('users/activate-inactivate-user/{user}', [App\Http\Controllers\Api\ApiUserController::class, 'activateInactivateUser'])->name('users.activateInactivateUser');
});

require __DIR__ . '/auth.php';
