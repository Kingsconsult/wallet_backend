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

// Register in the app
Route::post('register', [AuthController::class, 'register'])->name('register');
// Login to the app
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:api']], function () {
    // Get all users 
    Route::get('all-users', [UserController::class, 'allUsers']);
    // Get a user's details
    Route::get('user/{id}', [UserController::class, 'user']);
    // Get the total number of users of the app
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
