<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\History;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

    public function update(Request $request){
        try {
            $user = User::find(auth()->user()->id);

            $data = $request->only('name', 'username', 'ktp', 'email', 'verified', 'password', 'profile_picture');
            if($request->username != $user->username){
                $isExistUsername = User::where('username', $request->username)->exists();
                if($isExistUsername){
                    //409 http code its mean confilict
                    return response()->json(['message' => 'username already taken'], 409);
                }
            }

            if($request->email != $user->email){
                $isExistEmail = User::where('email', $request->email)->exists();
                if($isExistEmail && $request->email != $user->email){
                    //409 http code its mean confilict
                    return response()->json(['message' => 'email already taken'], 409);
                }
            }

            if($request->password){
                $data['password'] = bcrypt($request->password);
            }

            if($request->profile_picture){
                $profilePicture = uploadBase64Image($request->profile_picture);
                $data['profile_picture'] = $profilePicture;

                if($user->profile_picture){
                    Storage::delete('public/'.$user->profile_picture);
                }
            }

            if($request->ktp){
                $ktpPicture = uploadBase64Image($request->ktp);
                $data['ktp'] = $ktpPicture;
                $data['verified'] = true;

                if($user->ktp){
                    Storage::delete('public/'.$user->ktp);
                }
            }

            $user->update($data);

            return response()->json(['message' => 'User Updated', 'data' => $user]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function isEmailExists(Request $request){
        $validator = Validator::make($request->only('email'), [
            'email' => 'required|email'
        ]);

        if($validator->fails()){
            return response()->json(['errors'=> $validator->messages()], 400);
        }

        $isEmailExist = User::where('email', $request->email)->exists();

        return response()->json(['is_email_exist' => $isEmailExist]);
    }

}
