<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('auth')->group(function () {
    Route::post('signup', [AuthController::class, 'signUp']);
    Route::post('signin', [AuthController::class, 'signIn']);
    Route::get('getuser', [AuthController::class, 'getUser']);
});

Route::resource('products', ProductsController::class)->only('index');
