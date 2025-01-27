<?php


namespace Modules\Frontend\Http\Controllers\Auth;

use Str;
use Auth;
use Hash;
use App\Models\User;
use App\Models\Device;
use App\Models\Setting;
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
    use LoginTrait;

    public function otpLogin()
    {
        $userId = auth()->id();

        $settings = Setting::getAllSettings($userId);
        $isOtpLoginEnabled = Setting::where('name', 'is_otp_login')->value('val') == 1;

        // return $settings;

        return view('frontend::auth.otp_login', compact('settings', 'isOtpLoginEnabled'));
    }


    public function otpLoginStore(Request $request)
    {
        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' =>  $request->email,
            'mobile' =>  $request->mobile,
            'password' => Hash::make(Str::random(8)),
            'user_type' => 'user',
            'login_type' => 'otp'
        ];

        $user=User::where('email', $request->email)->first();

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

    public function otpLoginStoreApi(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'mobile' => 'required|numeric|digits:10',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            // Check if the user already exists
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                // Create a new user if not found
                $data = [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'password' => Hash::make(Str::random(8)), // Random password for OTP login
                    'user_type' => 'user',
                    'login_type' => 'otp',
                ];

                $user = User::create($data);

                // Optionally create profile and assign a role
                $user->createOrUpdateProfileWithAvatar(); // Ensure this function exists
                $user->assignRole($data['user_type']); // Ensure this role exists
            } elseif ($user->login_type !== 'otp') {
                // Block login if the user exists but doesn't use OTP for login
                return response()->json([
                    'error' => 'This email is already registered with a different login method.',
                ], 403);
            }

            // Log in the user and generate an access token
            $this->setDevice($user, $request); // Ensure device tracking is implemented
            $access_token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login Successfully',
                'access_token' => $access_token,
                'status' => 200,
            ]);

        } catch (\Exception $e) {
            // Catch and return errors
            return response()->json([
                'error' => $e->getMessage(),
                'status' => 500,
            ]);
        }
    }

    public function checkUserExists(Request $request)
    {
        $data = $request->all();

        $current_device=$request->has('device_id')?$request->device_id:$request->getClientIp();

        $flag = 0;
        $user = User::where('mobile', $request->mobile)->where('login_type','otp')->with('subscriptionPackage')->first();

        if(!empty($user))
        {

            if($user->user_type !='user'){

                return response()->json(['message'=>"Admin doesn't have access to login", 'status' => 406]);
            }

            $response=$this->CheckDeviceLimit($user, $current_device);

            if(isset($response['error'])) {

                return response()->json(['message'=>$response['error'], 'status' => 406]);
            }

            $this->setDevice($user, $request);

            Auth::login($user);
            $token = $user->createToken('auth_token')->plainTextToken;
            $flag = 1;
            return response()->json(['message'=>"Login Successfully",'is_user_exists' => $flag, 'status' => 200, 'access_token' => $token]);
        }

        return response()->json(['is_user_exists' => $flag, 'url' => route('user.login')]);
    }
}
