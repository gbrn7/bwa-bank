<?php
use App\Models\User;
use App\Models\Wallet;

 function getUser($param){
  $user = User::where('email', $param)
          ->orWhere('username', $param)
          ->first();

  $wallet = Wallet::where('user_id', $user->id)->first();
  $user->profile_picture = $user->profile_picture ? url ('storage/'.$user->profile_picture) : '';
  $user->ktp = $user->ktp ? url ('storage/'.$user->ktp) : '';
  $user->balance = $wallet->balance;
  $user->card_number = $wallet->card_number;
  $user->pin = $wallet->pin;

  return $user;
}
