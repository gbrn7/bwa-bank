<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\History;

class UserController extends Controller
{
    public function show(){
        $user = getUser(auth()->user()->email);

        return response()->json($user);
    }
}
