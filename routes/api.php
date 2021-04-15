<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\WalletController;
use App\Http\Controllers\API\WalletTypeController;
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


Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login']);

Route::get('all-users', [AuthController::class, 'allUsers'])->middleware('auth:api');
Route::get('user/{id}', [AuthController::class, 'user'])->middleware('auth:api');

Route::apiResource('wallet-types', WalletTypeController::class)->middleware('auth:api');
Route::apiResource('wallets', WalletController::class)->middleware('auth:api');
