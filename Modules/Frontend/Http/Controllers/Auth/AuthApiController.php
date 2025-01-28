<?php

namespace Modules\Frontend\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Str;
use App\Models\Device;
use Modules\Frontend\Trait\LoginTrait;

class AuthApiController extends Controller
{

    // Redirect to Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    //for mobile api
    public function handleGoogleCallbackApi(Request $request)
    {
    try {
        // Validate request
        $validator = Validator::make($request->all(), [
            'callback_token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success"=>false,
                "message" => $validator->errors()
            ], 422);
        }

        // Retrieve Google user using the callback token
        $googleUser = Socialite::driver('google')->userFromToken($request->callback_token);

        // Check if a user with the email exists
        $existingUser = User::where('email', $googleUser->getEmail())->first();

            if (!$existingUser) {
                // Register new user
                $fullName = $googleUser->getName();
                $nameParts = explode(' ', $fullName);
                $firstName = $nameParts[0] ?? '';
                $lastName = $nameParts[1] ?? $firstName;

                $data = [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(8)), // Generate a random password
                    'user_type' => 'user',
                    'login_type' => 'google',
                ];

                $newUser = User::create($data);
                $newUser->assignRole($data['user_type']); // Optional: assign role if using Spatie
                $newUser->save();

                // Generate auth token
                $token = $newUser->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'success'=>true,
                    'message' => 'User registered successfully.',
                    'token' => $token,
                ], 201);
            }

            // Prevent login if the user is registered with a different method
            if ($existingUser->login_type !== 'google') {
                return response()->json([
                    'success'=>false,
                    'message' => 'This email is already registered. Please use the original login method.',
                ], 403);
            }

            // Check device limit if applicable
            if ($request->has('device_id')) {
                $response = $this->CheckDeviceLimit($existingUser, $request->device_id);

                if (isset($response['error'])) {
                    return response()->json(['error' => $response['error']], 500);
                }
            }

            // Generate auth token
            $token = $existingUser->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success'=>true,
                'message' => 'User logged in successfully.',
                'token' => $token,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
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
                return response()->json([
                    'success'=>false,
                    'message' => $validator->errors()
                ], 422);
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
                    'success'=>false,
                    'message' => 'This email is already registered with a different login method.',
                ], 403);
            }

            // Log in the user and generate an access token
            $this->setDevice($user, $request); // Ensure device tracking is implemented
            $access_token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'succcess'=>true,
                'message' => 'Login Successfully',
                'token' => $access_token,
            ],200);

        } catch (\Exception $e) {
            // Catch and return errors
            return response()->json([
                'success'=>false,
                'message' => $e->getMessage(),
            ],500);
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

                return response()->json([
                    'success'=>false,
                    'message'=>"Admin doesn't have access to login",
                   ],403);
            }

            $response=$this->CheckDeviceLimit($user, $current_device);

            if(isset($response['error'])) {

                return response()->json(['message'=>$response['error'], 'status' => 406]);
            }

            $this->setDevice($user, $request);

            $token = $user->createToken('auth_token')->plainTextToken;
            $flag = 1;
            return response()->json([
                  'success'=>true,
                  'message' => 'User logged in successfully.',
                  'is_user_exists' => $flag,
                  'token' => $token
                ],200);
        }

        return response()->json([
             'success'=>true,
             'is_user_exists' => $flag,
            ]);
    }

    public function logoutApi()
    {
        $user = User::where('id',auth('sanctum')->id)->first();
        $user->tokens()->delete();
        return response()->json(['status' => true, 'message' => "Logout successfully"],200);
    }
}
