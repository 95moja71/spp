<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{


    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {

        try {
            $googleuser = Socialite::driver('google')->stateless()->user();
            $user = User::where('email', $googleuser->email)->first();

            if ($user) {


                auth()->loginUsingId($user->id);


            } else {
                // Register User With Google
                $newUser = User::create([
                    "name" => $googleuser->name,
                    "email" => $googleuser->email,
                    "password" => bcrypt(\Str::random(16)),
                    "email_verified_at" => now(),
                ]);

                auth()->loginUsingId($newUser->id);
            }


            return redirect('/');


        } catch (\Exception $e) {

            return 'error';
        }


        // $user->token

    }


}
