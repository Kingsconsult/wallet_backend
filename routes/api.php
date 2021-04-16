<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\StateLgaController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\UserController;
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

Route::group(['middleware' => ['auth:api']], function () {

    Route::get('all-users', [UserController::class, 'allUsers']);
    Route::get('user/{id}', [UserController::class, 'user']);
    Route::get('count-users', [UserController::class, 'countUsers']);

    Route::prefix('wallets')->group(function () {
        Route::get('counts', [WalletController::class, 'counts']);
        Route::get('balance/{id}', [WalletController::class, 'walletBalance']);
        Route::post('fund-wallet', [WalletController::class, 'fundWallet']);
        Route::post('fund-transfer', [WalletController::class, 'walletTransfer']);
    });

    Route::post('import-excel', [StateLgaController::class, 'importStateLga']);


    Route::apiResource('wallet-types', WalletTypeController::class);
    Route::apiResource('wallets', WalletController::class);
    Route::apiResource('transactions', TransactionController::class);
    // Route::apiResource('transactions', WalletController::class);
});


// Route::post('projects/importProject', [ProjectController::class, 'importProject'])->name('importProject');
