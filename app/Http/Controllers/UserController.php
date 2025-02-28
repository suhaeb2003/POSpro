<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class UserController extends Controller
{
    function LoginPage()
    {
        $cookie = Cookie::get('token');
        if($cookie){
            return redirect()->route('dashboard');
        }else{

            return Inertia::render('Users/LoginPage');
        }
    }
    function profilePage(Request $request)
    {
        $user_id = $request->header('id');
        $info = User::where('id', $user_id)->first();
        return Inertia::render('Users/ProfilePage')->with('info', $info);
    }
    function SignUp()
    {
        $cookie = Cookie::get('token');
        if($cookie){
            return redirect()->route('dashboard');
        }else{
            return Inertia::render('Users/SignupPage');
        }
        
    }
    function SendOtpPage()
    {
        return Inertia::render('Users/SendOtpPage');
    }
    function VerifyOtpPage()
    {
        return Inertia::render('Users/VerifyOtpPage');
    }
    function setpassword()
    {
        return Inertia::render('Users/SetPasswordPage');
    }
    function userResistration(Request $request)
    {
        $request->validate([
            'firstname' => 'required|min:3',
            'lastname' => 'nullable',
            'email' => 'required|email',
            'mobile' => 'required',
            'password' => 'required|min:3'
        ]);
        try {
            User::create([
                'firstname' => $request['firstname'],
                'lastname' => $request['lastname'],
                'email' => $request['email'],
                'mobile' => $request['mobile'],
                'password' => $request['password']
            ]);
            $data = ['message' => 'User Registraion Successfull!', 'status' => true, 'error' => ''];
            return redirect()->route('signup')->with($data);
            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'User Registraion Successfull!'
            // ]);
        } catch (Exception $e) {
            $data = ['message' => 'Registraion Failed!', 'status' => false, 'error' => $e->getMessage()];
            return redirect()->route('signup')->with($data);
        }
    }

    function userLogin(Request $request)
    {
        $cookie = Cookie::get('token');
        if($cookie){
            return redirect()->route('dashboard');
        }else{

        
            $login = $request->input('login');
            $password = $request->input('password');

            // $count = User::where('email', $email)->where('password', $password)->first();
            $count = User::where(function ($query) use ($login) {
                $query->where('email', $login)->orWhere('mobile', $login);
            })->where('password', $password)->first();

            if ($count !== null) {
                $token = JWTToken::CreateToken($count->email, $count->id);
                $data = ['message' => 'User Login Successfull!', 'status' => true, 'error' => ''];
                return redirect()->route('Login')->with($data)->withCookie('token', $token);
            } else {
                $data = ['message' => 'User Login Failed!', 'status' => false, 'error' => 'User Not Found'];
                return redirect()->route('Login')->with($data);
            }
        }
    }
    function userLogout(Request $request)
    {
        $cookie = Cookie::forget('token');
        return redirect()->route('Login')->withCookie($cookie);
    }
    function sendOTP(Request $request)
    {
        $email = $request->input('email');
        $otp = rand(1000, 9999);
        $count = User::where('email', $email)->count();
        //set email in cookie
        Cookie::queue('email', $email, 60);
        if ($count !== null) {
            //for send otp
            Mail::to($email)->send(new OTPMail($otp));
            //save otp to database
            User::where('email', $email)->update(['otp' => $otp]);
            $data = ['message' => '4 digit OTP send to your email', 'status' => true, 'error' => ''];
            return redirect()->route('send-otp')->with($data);
        } else {
            $data = ['message' => 'User Not Found', 'status' => false, 'error' => 'User Not Found'];
            return redirect()->route('send-otp')->with($data);
        }
    }

    function verifyOTP(Request $request)
    {
        $email = $request->cookie('email');
        $opt = $request->input('otp');
        $count = User::where('email', $email)->where('otp', $opt)->first();
        if ($count !== null) {
            //remove email form cookie
            Cookie::queue(Cookie::forget('email'));
            User::where('email', $email)->update(['otp' => '0']);
            $token = JWTToken::SetTokenForSetPassword($email, $count->id);
            $data = ['message' => 'OTP Verify Successfull', 'status' => true, 'error' => ''];
            return redirect()->route('verify-otp')->with($data)->withCookie('token', $token);
        } else {
            $data = ['message' => 'OTP Verify Failed', 'status' => false, 'error' => 'OTP Not Match'];
            return redirect()->route('verify-otp')->with($data);
        }
    }
    function resetPass(Request $request)
    {
        try {
            $email = $request->header('email');
            $newPass = $request->input('password');
            $updatePass = User::where('email', $email)->update(['password' => $newPass]);
            if ($updatePass) {
                $token = Cookie::forget('token');
                $data = ['message' => 'Reset Password Successfull', 'status' => true, 'error' => ''];
                return redirect()->route('reset-password')->with($data)->withCookie($token);
            } else {
                $data = ['message' => 'Reset Password Failed', 'status' => false, 'error' => ''];
                return redirect()->route('reset-password')->with($data);
            }

        } catch (Exception $e) {
            $data = ['message' => 'Reset password Failed', 'status' => false, 'error' => $e->getMessage()];
            return redirect()->route('reset-password')->with($data);
        }
    }
    function updateUser(Request $request)
    {

        $request->validate([
            'firstname' => 'nullable|min:3',
            'lastname' => 'nullable',
            'email' => 'nullable|email',
            'mobile' => 'nullable|min:11|max:14',
            'password' => 'nullable|min:3|max:5'
        ]);
        $email = $request->input('email');
        $id = $request->header('id');
        try {
            User::where('email', $email)->where('id', $id)->update([
                'firstname' => $request['firstname'],
                'lastname' => $request['lastname'],
                'email' => $request['email'],
                'mobile' => $request['mobile'],
                'password' => $request['password']
            ]);
            $data = ['message' => 'Profile Update Successfull', 'status' => true, 'error' => ''];
            return redirect()->route('profile')->with($data);
        } catch (Exception $e) {
            $data = ['message' => 'Profile Update Failed', 'status' => false];
            return redirect()->route('profile')->with($data);
        }

    }
}
