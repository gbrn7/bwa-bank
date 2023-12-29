<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\History;
use App\Models\User;

class UserController extends Controller
{
    public function show(){
        $user = getUser(auth()->user()->email);

        return response()->json($user);
    }

    public function getUserByUsername(Request $request, $username){
        $users = User::select('id', 'name', 'username', 'verified', 'profile_picture')
                    ->where('username', 'LIKE', '%'.$username.'%')
                    ->where('id', '<>',auth()->user()->id )
                    ->get();
        $users->map(function ($item){
            $item->profile_picture = $item->profile_picture ?
            url('storage/'.$item->profile_picture) : '';
            return $item;
        });


    return response()->json($users);
    }

}
