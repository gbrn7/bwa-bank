<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;


class WebhookController extends Controller{

    public function update(){
        \Midtrans\Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION');
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        $notif = new \Midtrans\Notification();
        
        $$transactionStatus = $notif->transaction_status;
        $type = $notif = $notif->payment_type;
        $transactionCode = $notif->order_id;
        $fraudStatus = $notif->fraud_status;

        DB::beginTransaction();
        try {
            $status = null;

            if ($transactionStatus == 'capture'){
                if ($fraudStatus == 'accept'){
                        // TODO set transaction status on your database to 'success'
                        // and response with 200 OK
                        $status = 'success';
                    }
                } else if ($transactionStatus == 'settlement'){
                    // TODO set transaction status on your database to 'success'
                    // and response with 200 OK
                    $status = 'success';
                } else if ($transactionStatus == 'cancel' ||
                  $transactionStatus == 'deny' ||
                  $transactionStatus == 'expire'){
                  // TODO set transaction status on your database to 'failure'
                  // and response with 200 OK
                  $status = 'failed';
                } else if ($transactionStatus == 'pending'){
                  // TODO set transaction status on your database to 'pending' / waiting payment
                  // and response with 200 OK
                  $status = 'pending';
                }
                
            $transaction = Transaction::where('transaction_code', $transactionCode)->first();
            if($transaction-> status != 'success'){
                $transactionAmount = $transaction->amount;
                $userId = $transaction->user_id;

                $transaction->update(['status' => $status]);
            
                if($status == 'success'){
                    Wallet::where('user_id', $userId)->increment('balance', $transactionAmount);
                }
            }

            DB::commit();
            return response()->json();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => $th->getMessage()]);
        }
    }

}
