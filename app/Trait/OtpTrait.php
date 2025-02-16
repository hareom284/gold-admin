<?php

namespace App\Trait;

use App\Models\Otp;
use Exception;
use Nette\Utils\Random;
use Twilio\Rest\Client;

trait OtpTrait
{
   public function generateToken($length = 5)
   {
       return Random::generate($length, '0-9');
   }

   public function sendOtpAndSave($phone_or_email)
   {
        $otp = $this->generateToken();
        $expire_at = now()->addMinutes(1);

        $checkOtp = Otp::where('phone_or_email', $phone_or_email)->first();
        if ($checkOtp) {
            if($checkOtp->expire_at < now()){
                $checkOtp->update([
                    'otp' => $otp,
                    'expire_at' => $expire_at,
                    'is_verified' => false,
                ]);
                 return $this->callOTPService($phone_or_email, $otp);
            }

            return response()->json([
                'success'=>false,
                'message' => 'You have already sent an OTP. Please wait for 1 minutes before sending another OTP.',
            ], 422);

        }

        Otp::create([
            'phone_or_email' => $phone_or_email,
            'otp' => $otp,
            'expire_at' => $expire_at,
        ]);
        return $this->callOTPService($phone_or_email, $otp);

   }

   public function resendOtpAndSave($phone_or_email)
   {
       $token = $this->generateToken();
       $expire_at = now()->addMinutes(1);

       $otp = Otp::where('phone_or_email', $phone_or_email)->first();
       if ($otp) {
           if ($otp->expire_at < now()) {
               $otp->update([
                   'otp' => $token,
                   'expire_at' => $expire_at,
               ]);
               return $this->callOTPService($phone_or_email, $token);
           } else {
               return response()->json([
                   'success' => false,
                   'message' => 'You have already sent an OTP. Please wait for 1 minutes before sending another OTP.',
               ], 422);
           }
       }

        return response()->json([
            'success' => false,
            'message' => 'Your phone number is not registered with us.',
        ], 404);
    }




   public function callOTPService($phone_or_email, $otp)
   {
         //for local testing
        // return response()->json([
        //     'success' => true,
        //     'message' => 'OTP sent successfully'
        // ],200);

        try{
            $sid = env('TWILIO_SID');
            $token = env('TWILIO_TOKEN');
            $client = new Client($sid, $token);
            $client->messages->create(
                $phone_or_email,
                [
                    'from' => env('TWILIO_FROM'),
                    'body' => "Gold channel OTP: $otp. It will expire in 5 minutes.",
                ]
            );
            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully'
            ],200);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ],500);
        }
   }
}
