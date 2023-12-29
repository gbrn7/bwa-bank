<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TopUpController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\Api\TransferController;
use App\Http\Controllers\Api\DataPlanController;
use App\Http\Controllers\Api\OperatorCardController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Api\TransferHistoryController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WalletController;


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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('is-email-exists', [UserController::class, 'isEmailExists']);

Route::post('webhooks', [WebhookController::class, 'update']);

Route::middleware(['middleware' => 'jwt.verify'])->group(function ($router) {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('top_ups', [TopUpController::class, 'store']);
    Route::post('transfers', [TransferController::class, 'store']);
    Route::post('data_plans', [DataPlanController::class, 'store']);
    Route::get('operator_cards', [OperatorCardController::class, 'index']);
    Route::get('payment_methods', [PaymentMethodController::class, 'index']);
    Route::get('transfer_histories', [TransferHistoryController::class, 'index']);
    Route::get('transactions', [TransactionController::class, 'index']);
    Route::get('users', [UserController::class, 'show']);
    Route::get('users/{username}', [UserController::class, 'getUserByUsername']);
    Route::put('users', [UserController::class, 'update']);
    Route::get('wallet', [WalletController::class, 'show']);
    Route::put('wallet', [WalletController::class, 'update']);
});
