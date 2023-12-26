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

Route::post('webhooks', [WebhookController::class, 'update']);

Route::middleware(['middleware' => 'jwt.verify'])->group(function ($router) {
    Route::post('top_ups', [TopUpController::class, 'store']);
    Route::post('transfers', [TransferController::class, 'store']);
    Route::post('data_plans', [DataPlanController::class, 'store']);
    Route::get('operator_cards', [OperatorCardController::class, 'index']);
    Route::get('payment_methods', [PaymentMethodController::class, 'index']);
});
