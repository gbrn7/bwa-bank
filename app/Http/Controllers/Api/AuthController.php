<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Wallet;
use Melihovv\Base64ImageDecoder\Base64ImageDecoder;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JwtExceptions;

class AuthController extends Controller
{
    public function register(Request $request){
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'pin' => 'required|digits:6',
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->messages()], 400);
        }

        $user  = User::where('email', $request->email)->exists();
        if($user){
            return response()->json(['message' => 'Email already taken'], 404);
        }

        DB::beginTransaction();
        try {
            $profilePicture = null;
            $ktp = null;

            if($request->profile_picture){
                $profilePicture = $this->uploadBase64Image($request->profile_picture);
            }
            if($request->ktp){
                $ktp = $this->uploadBase64Image($request->ktp);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->email,
                'password' => bcrypt($request->password),
                'profile_picture' => $profilePicture,
                'ktp' => $ktp,
                'verified' => ($ktp) ? true : false,
            ]);

            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
                'pin' => $request->pin,
                'card_number' => $this->generateCardNumber(16),
            ]);

            DB::commit();

            $token = JWTAuth::attempt(['email' => $request->email, 'password' => $request->password]);
            //the getUser function can accessed because we defined in composer.json in autoload and registered with comman
            $userResponse = getUser($request->email);
            $userResponse->token = $token;
            $userResponse->token_expires_in = auth()->factory()->getTTl() * 60;
            $userResponse->token_type = 'bearer';

            return response()->json($userResponse, 201);

        } catch (\Throwable $th) {
            //throw $th;
            // echo $th;
            DB::rollback();
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function login(Request $request){
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->messages()], 400);
        }

        try {
            $token = JWTAuth::attempt($credentials);

            if(!$token){
                return response()->json(['message' => 'Login Credentials are invalid'], 400);
            }

            //the getUser function can accessed because we defined in composer.json in autoload and registered with comman
            $userResponse = getUser($request->email);
            $userResponse->token = $token;
            $userResponse->token_expires_in = auth()->factory()->getTTl() * 60;
            $userResponse->token_type = 'bearer';

            return response()->json($userResponse, 200);        
        } catch (\JwtExceptions $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage()], 500);        
        }
    }

    private function generateCardNumber($length){
        $result = '';
        for ($i=0; $i < $length; $i++) { 
            $result .= mt_rand(0, 9);
        }

        $wallet = Wallet::where('card_number', $result)->exists();

        if($wallet){
            return $this->generateCardNumber($length);
        }

        return $result;
    }

    private function uploadBase64Image($base64Image){
        $decoder = new Base64ImageDecoder($base64Image, $allowedFormats = ['jpeg', 'png', 'jpg']);
        
        $decodedContent = $decoder->getDecodedContent();
        $format = $decoder->getFormat();
        $image = Str::random(10).'.'.$format;
        Storage::disk('public')->put($image, $decodedContent);

        return $image;
    }
}
