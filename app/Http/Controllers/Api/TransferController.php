<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\History;
use App\Models\TransactionType;
use App\Models\Wallet;
use App\Models\User;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    public function store(Request $request){
       $data = $request->only('amount', 'pin', 'send_to'); 
        
       $validator = Validator::make($data, [
        'amount' => 'required|integer|min:10000',
        'pin' => 'required|digits:6',
        'send_to' => 'required',
       ]);

       if($validator->fails()){
        return response()->json(['errors' => $validator->messages()], 400);
       }
    //    dd($request->send_to);
       $sender = auth()->user();
       $receiver = User::select('users.id', 'users.username')
                    ->join('wallets', 'wallets.user_id', 'users.id')
                    ->where('users.username', $request->send_to)
                    ->orWhere('wallets.card_number', $request->send_to)
                    ->first();

        $pinChecker = pinChecker($request->pin);

        if(!$pinChecker){
            return response()->json(['message' => 'Your PIN is wrong'], 400);
        }   
        
        if(!$receiver){
            return response()->json(['message' => 'User Receiver Not Found'], 404);
        }

        if($sender->id == $receiver->id){
            return response()->json(['message' => 'You cannot transfer to yourself'], 400);
        }

        $senderWallet = Wallet::where('user_id', $sender->id)->first();

        // dd($sender, $receiver);
        if($senderWallet->balance < $request->amount){
            return response()->json(['message' => 'Your Balance Is Not Enough'], 400);
        }

        DB::beginTransaction();
        try {
            $transactionType = TransactionType::whereIn('code', ['receive', 'transfer'])
                                                ->orderBy('code', 'asc')
                                                ->get();
            $receiveTransactionType = $transactionType->first();
            $transferTransactionType = $transactionType->last();
            $paymentMethod = PaymentMethod::where('code', 'bwa')->first();
            $transactionCode = strtoupper(Str::random(10));
            // Transaction for transfer
            $transferTransaction = Transaction::create([
                'user_id' => $sender->id,
                'transaction_type_id' => $transferTransactionType->id,
                'description' => 'Transfer fund to'.$receiver->username,
                'amount' => $request->amount,
                'transaction_code' => $transactionCode,
                'status' => 'success',
                'payment_method_id' => $paymentMethod->id
            ]);

            $senderWallet->decrement('balance', $request->amount);
            // dd('cek');
            // Transaction for receive
            $transferTransaction = Transaction::create([
                'user_id' => $receiver->id,
                'transaction_type_id' => $receiveTransactionType->id,
                'description' => 'Receive fund from'.$sender->username,
                'amount' => $request->amount,
                'transaction_code' => $transactionCode,
                'status' => 'success',
                'payment_method_id' => $paymentMethod->id
            ]);
            Wallet::where('user_id', $receiver->id)->increment('balance', $request->amount);

            History::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'transaction_code' => $transactionCode,
            ]);

            DB::commit();
            
            return response()->json(['message' => 'Transfer Success']);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
