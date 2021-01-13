<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', UserController::class . '@register')->middleware('guest');
Route::post('/login', UserController::class . '@login')->middleware('guest');

Route::prefix('admin')->group(function () {
    Route::post('/login', AdminController::class . '@loginAdmin')->middleware('guest:admin');

    Route::middleware('auth:admin')->group(function () {
        Route::post('/products/create', ProductController::class . '@create');
        Route::put('/products/{product}', ProductController::class . '@update');
        Route::delete('/products/{product}', ProductController::class . '@delete');
    });
});
