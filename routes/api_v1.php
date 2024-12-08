<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\WalletController;
use App\Http\Controllers\API\V1\BillPaymentController;
use App\Http\Controllers\API\V1\TransactionController;
use App\Http\Controllers\API\V1\WalletFundingController;

/*
|--------------------------------------------------------------------------
| V1 API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(
  ['middleware' => ['auth:sanctum'], 'prefix' => 'v1'],
  function () {
    Route::apiResource('user', UserController::class);

    Route::apiResource(
      'transactions',
      TransactionController::class
    )->only('index', 'show');

    Route::apiResource(
      'wallet',
      WalletController::class
    )->only('create', 'show');

    Route::post('/wallet/top-up', WalletFundingController::class);

    Route::post(
      'bill-payments/vend',
      [BillPaymentController::class, 'vend']
    )->middleware('insufficient_wallet_balance');
  }
);