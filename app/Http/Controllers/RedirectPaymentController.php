<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class RedirectPaymentController extends Controller
{
    public function finish(Request $request){
        $transactionCode = $request->order_id;
        $transaction = Transaction::where('transaction_code', $transactionCode)->first();

        return view('payment-finish', ['transaction' => $transaction]);
    }
}
