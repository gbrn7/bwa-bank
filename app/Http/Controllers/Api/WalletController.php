<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;

class WalletController extends Controller
{
    public function show(){
        $user = auth()->user();

        $wallet = Wallet::select('pin', 'balance', 'card_number')
                        ->where('user_id', $user->id)
                        ->first();
        
        return response()->json($wallet);
    }
}
