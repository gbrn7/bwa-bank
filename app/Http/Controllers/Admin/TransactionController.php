<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;


class TransactionController extends Controller
{
    public function index(){
        $relations = [
            'paymentMethod:id,name,code,thumbnail',
            'user:id,name',
            'transactionType:id,name,code,action',
        ];

        $transactions = Transaction::with($relations)
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        return view('transactions', ['transactions' => $transactions]);
    }


}
