<?php

namespace App\Http\Controllers\Mobile;

use Str;
use Auth;
use Hash;
use App\Models\Otp;
use App\Models\User;
use App\Trait\OtpTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;


class OTPController extends Controller
{
    use OtpTrait;

    public function sendOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_or_email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        return $this->sendOtpAndSave($request->phone_or_email);
    }

    public function resendOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_or_email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $this->resendOtpAndSave($request->phone_or_email);
    }

    public function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required',
            'phone_or_email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);
        }

        $otp = Otp::where('phone_or_email', $request->phone_or_email)
        ->where('otp', $request->otp)->first();

        if (!$otp || $otp->expire_at < now()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP',
            ], 422);
        }else{
            $otp->delete();
            $user = User::where('mobile', $request->phone_or_email)->where('login_type','otp')->with('subscriptionPackage')->first();

            if (!empty($user)) {
                    if($user->user_type !='user'){
                        return response()->json([
                            'success' => false,
                            'message'=>"Admin doesn't have access to login"
                        ],406);
                    }
                    $token = $user->createToken('auth_token')->plainTextToken;
                    return response()->json([
                        'success' => true,
                        'message' => 'User login successfully',
                        'token' => $token,
                        'user' => $user
                    ],200);
            }

            return response()->json([
                'success'=>false,
                'message'=>'OTP verified successfully, ples create new user',
            ],404);
        }

    }

    public function otpUserStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'mobile' => 'required|unique:users',
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' =>  $request->email,
            'mobile' =>  $request->mobile,
            'password' => Hash::make(Str::random(8)),
            'user_type' => 'user',
            'login_type' => 'otp'
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'User login successfully',
            'token' => $token,
            'user' => $user
        ],200);
    }
}
