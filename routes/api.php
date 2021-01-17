<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PublicProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('user', function (Request $request) {
    return $request->user();
});

Route::post('register', UserController::class . '@register');
Route::post('login', UserController::class . '@login');

Route::get('products', PublicProductController::class . '@all');

Route::prefix('admin')->group(function () {
    Route::post('login', AdminController::class . '@loginAdmin');

    Route::middleware('auth:admin')->group(function () {
        Route::get('user', fn(Request $request) => $request->user());

        Route::post('products/create', ProductController::class . '@create');
        Route::get('products/{product}', ProductController::class . '@show');
        Route::put('products/{product}', ProductController::class . '@update');
        Route::delete('products/{product}', ProductController::class . '@delete');

        Route::post('categories/create', CategoryController::class . '@create');
        Route::put('categories/{category}', CategoryController::class . '@update');
        Route::delete('categories/{category}', CategoryController::class . '@delete');
        Route::get('categories/{category}', CategoryController::class .'@get');
        Route::post('products/{product}/add-image', ImageController::class . '@store');
        Route::delete('images/{image}', ImageController::class . '@delete');

        Route::get('categories', CategoryController::class .'@all')->withoutMiddleware('auth:admin');
        Route::get('products', ProductController::class .'@all');
    });
});
