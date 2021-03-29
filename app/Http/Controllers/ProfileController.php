<?php

namespace App\Http\Controllers;

use App\ActiveCode;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function manageTwoFactor()
    {
        return view('profile.two-factor-auth');
    }

    public function postManageTwoFactor(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:sms,off',
            'phone' => 'required_unless:type,off'
        ]);

        if($data['type'] === 'sms') {
            if($request->user()->phone_number !== $data['phone']) {
                // create a new code
                $code = ActiveCode::generateCode($request->user());
                // send the code to user phone number
                // TODO Send Sms

                return redirect(route('profile.2fa.phone'));
            } else {
                $request->user()->update([
                    'two_factor_type' => 'sms'
                ]);
            }
        }

        if($data['type'] === 'off') {
            $request->user()->update([
                'two_factor_type' => 'off'
            ]);
        }

        return back();
    }


    public function getPhoneVerify()
    {
        return view('profile.phone-verify');
    }

    public function postPhoneVerify(Request $request)
    {
        $request->validate([
           'token' => 'required'
        ]);


        return $request->token;
    }
}
