<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\ForgotPassword;
use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    // use SendsPasswordResetEmails;

    public function index()
    {
        return view('forgot-password');    
    }

    public function forgetPassword(Request $request)
    {
        $request->validate(['email'=>'required|email|exists:users']);  
       
        $check = User::where('email',$request['email'])->where('isActive',1)->exists();

        if($check == 1)
        {
            $token = Str::random(64);

            PasswordReset::insert([
                'email' => $request['email'], 
                'token' => $token, 
                'created_at' => Carbon::now()
            ]);

            dispatch(new ForgotPassword($request['email'],$token));

            return back()->with('message', 'We have e-mailed your password reset link!');
        }
        else
        {
            return back()->with('error', 'Your Account is not found, please contact Admin.');
        }
    }

    public function showResetPasswordForm($token) 
    { 
        $getEmail = PasswordReset::where(['token' => $token])->get('email')->toArray();
        $email = (isset($getEmail[0])) ? $getEmail[0]['email'] : "";

        return view('passwords.reset', ['token' => $token, 'email' => $email]);
    }

    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = PasswordReset::where(['email' => $request->email,'token' => $request->token])->first();

        if(!$updatePassword){
            return back()->withInput()->with('error', 'Invalid token!');
        }

        User::where('email', $request->email)
        ->update(
            ['password' => Hash::make($request->password)
        ]);

        PasswordReset::where(['email'=> $request->email])->delete();

        return redirect('/')->with('message', 'Your password has been changed!');
    }
}
