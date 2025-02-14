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

        //get data from otp table by phone_or_email and otp
        $otp = Otp::where('phone_or_email', $request->phone_or_email)
        ->where('otp', $request->otp)->first();

        //check otp exist or not and expire_at is less than now
        if (!$otp || $otp->expire_at < now()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP',
            ], 422);
        }else{

            //change otp status to verified
            $otp->is_verified = true;
            $otp->save();

            //if otp is verified , get user by phone number
            $user = User::where('mobile', $request->phone_or_email)->where('login_type','otp')->with('subscriptionPackage')->first();

            //if user exist then login and return token
            if (!empty($user)) {
                    if($user->user_type !='user'){
                        return response()->json([
                            'success' => false,
                            'message'=>"Admin doesn't have access to login"
                        ],406);
                    }

                    $otp->delete();

                    $token = $user->createToken('auth_token')->plainTextToken;
                    return response()->json([
                        'success' => true,
                        'message' => 'User login successfully',
                        'token' => $token,
                        'user' => $user
                    ],200);
            }

            //if user not exist then create new user and login
            return response()->json([
                'success'=>false,
                'message'=>'OTP verified successfully, ples create new user',
            ],404);
        }

    }

    public function otpUserStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'mobile' => 'required|unique:users',
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);
        }

        $otpData = Otp::where('phone_or_email', $request->mobile)->where('is_verified', true)->first();

        if(!$otpData || $otpData->otp != $request->otp){
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP',
            ], 422);
        }

        $user = User::create([
            'username' => $request->username,
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
