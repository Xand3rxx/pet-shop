<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\Administrator\UserController;
use App\Http\Controllers\Api\V1\Administrator\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Routes for authenticatication and authorization.
Route::prefix('admin')->name('admin.')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::post('login', 'login')->name('login');
        Route::post('logout', 'logout')->name('logout');
        Route::get('refresh', 'refresh')->name('refresh');
    });
});

// Routes for Admin features.
Route::prefix('admin')->name('admin.')->middleware('auth:api')->group(function () {
    Route::controller(UserController::class)->name('users.')->group(function () {
        Route::get('user-listing', 'index')->name('index');
        Route::post('create', 'store')->name('store');
        Route::put('user-edit/{user:uuid}', 'update')->name('update');
        Route::delete('user-delete/{user:uuid}', 'destroy')->name('destroy');
    });
});

// Routes for products feature.
Route::apiResource('products', ProductController::class)->middleware('auth:api');
