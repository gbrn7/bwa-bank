<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedirectPaymentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('payment_finish', [RedirectPaymentController::class, 'finish']);
