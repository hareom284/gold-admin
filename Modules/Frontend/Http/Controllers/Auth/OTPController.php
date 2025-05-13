<?php


namespace Modules\Frontend\Http\Controllers\Auth;

use Str;
use Auth;
use Hash;
use Exception;
use App\Models\Otp;
use App\Models\User;
use App\Models\Device;
use App\Models\Setting;
use App\Trait\OtpTrait;
use Twilio\Rest\Client;
use App\Mail\DeviceEmail;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Models\UserMultiProfile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Frontend\Trait\LoginTrait;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use GPBMetadata\Google\Api\Auth as ApiAuth;


class OTPController extends Controller
{
    use LoginTrait,OtpTrait;

    public function otpLogin()
    {
        $userId = auth()->id();

        $settings = Setting::getAllSettings($userId);
        $isOtpLoginEnabled = Setting::where('name', 'is_otp_login')->value('val') == 1;

        // return $settings;

        return view('frontend::auth.otp_login', compact('settings', 'isOtpLoginEnabled'));
    }

    public function sendOTP(Request $request)
    {
        return $this->sendOtpAndSave($request->phone_or_email);
    }

    public function resendOTP(Request $request)
    {
        return $this->resendOtpAndSave($request->phone_or_email);
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
                'message' => $validator->errors()->first(),
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

                    //delete otp and login user
                    $otp->delete();

                    Auth::login($user);

                    return response()->json([
                        'success' => true,
                        'is_user_exists' => 1,
                         'url' => route('home'),
                        ]);
            }

            //if user not exist then create new user and login
            return response()->json([
                'success' => true,
                'is_user_exists' => 0,
                 'url' => route('login'),
                ]);
        }
    }

    public function otpLoginStore(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'mobile' => 'required',
            'otp' => 'required',
        ]);

        $data = [
            'username' => $request->username,
            'mobile' =>  $request->mobile,
            'password' => Hash::make(Str::random(8)),
            'otp'=> $request->otp,
            'user_type' => 'user',
            'login_type' => 'otp'
        ];

        $otpData = Otp::where('phone_or_email', $request->mobile)->where('is_verified', true)->first();

        if(!$otpData || $otpData->otp != $request->otp){
            return redirect()->back();
        }

        $user = User::create($data);

        $request->session()->regenerate();

        $user->createOrUpdateProfileWithAvatar();

        $user->assignRole($data['user_type']);

        $user->save();

        if($user->login_type == 'otp' )
        {
            Auth::login($user);
            $this->setDevice($user, $request);
        }
        else
        {
            $user=Auth::user();
            Auth::logout();
            $this->removeDevice($user, $request);
           return Redirect::to('/login')->with('error', 'Something went wrong! During login');
        }

        return redirect('/'); // Redirect to intended page
    }
}
