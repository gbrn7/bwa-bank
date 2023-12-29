<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\URL;

class TransactionController extends Controller
{
    public function index(Request $request){
        $limit = $request->query('limit') ? $request->query('limit') : 10;

        $user = auth()->user();

        $relations = [
            'paymentMethod:id,name,code,thumbnail',
            'transactionType:id,name,code,action,thumbnail',
        ];


        $transactions = Transaction::with($relations)
                        ->where('user_id', $user->id)
                        ->where('status', 'success')
                        ->orderBy('id', 'desc')
                        ->paginate($limit);

        $arr = [];
        $arr2 = [];
        $transactions->getCollection()->transform(function ($item){
            $paymentMethodThumbnail = $item->paymentMethod->thumbnail ? 
            url('banks/'.$item->paymentMethod->thumbnail) : '' ;
        
            //the bellow code will copy $item->paymentMethod that would not affet to mother or main data
            $item->paymentMethod = clone $item->paymentMethod;
            $item->paymentMethod->thumbnail = $paymentMethodThumbnail;

            $transactionTypeThumbnail = $item->transactionType->thumbnail ? 
            url('transaction-type/'.$item->transactionType->thumbnail) : '' ;
        
            //the bellow code will copy $item->paymentMethod that would not affet to mother or main data
            $item->transactionType = clone $item->transactionType;
            $item->transactionType->thumbnail = $transactionTypeThumbnail;
            
                return $item;
        });

        // dd($arr, $arr2);

        // for ($i=0; $i < count($transactions) ; $i++) { 

        //     if($transactions[$i]->paymentMethod->thumbnail && $transactions[$i]->paymentMethod->check == null){
        //         $transactions[$i]->paymentMethod->thumbnail = url('banks/'.$transactions[$i]->paymentMethod->thumbnail);
        //         $transactions[$i]->paymentMethod->check = true;
        //     }
            
        //     if($transactions[$i]->transactionType->thumbnail && $transactions[$i]->transactionType->check == null){
        //         $transactions[$i]->transactionType->thumbnail = url('transaction-type/'.$transactions[$i]->transactionType->thumbnail); 
        //         $transactions[$i]->transactionType->check = true;
        //     }
        // }

        return response()->json($transactions);
}
}
