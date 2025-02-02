<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\BannerResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Modules\CastCrew\Models\CastCrew;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Api\MoviesResource;
use App\Http\Resources\Api\CastCrewResource;
use Modules\Banner\Models\Banner;
use Modules\Entertainment\Models\Entertainment;


class ApiController extends Controller
{
        // Redirect to Google
        public function redirectToGoogle()
        {
            return Socialite::driver('google')->stateless()->redirect();
        }

       //google callback
       public function handleGoogleCallback(Request $request)
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
                       'message' => 'Login successfully.',
                       'token' => $token,
                   ], 201);
               }

               // Prevent login if the user is login with a different method
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
                   'message' => 'Login successfully.',
                   'token' => $token,
               ], 200);
           } catch (\Exception $e) {
               return response()->json(["error" => $e->getMessage()], 500);
           }
       }

       //otp store
       public function otpLoginStore(Request $request)
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

       // check user exist
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

                   return response()->json([
                        'success'=>false,
                        'message'=>$response['error'],
                     ],403);
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

       //logout api
       public function logoutApi()
       {
           $user = User::where('id',auth('sanctum')->id)->first();
           $user->tokens()->delete();
           return response()->json([
                'success' => true,
                'message' => "Logout successfully"
           ],200);
       }

       //HomeBanner
       public function HomeBanner()
       {
           $user_id = auth()->id();
           $bannerList = Banner::where('status',1)->get();
           $banners = BannerResource::collection($bannerList->map(function ($banner) use ($user_id) {
                return new BannerResource($banner, $user_id);
            }));

            return response()->json([
                'success' => true,
                'message' => "Banner list reterived successfully",
                'data' => $banners
            ],200);
       }

       //top rated movies
       public function TopRatedMovies()
       {

            $topRatedMovies = Entertainment::whereNotNull('IMDb_rating')->orderBy('IMDb_rating','desc')->where('status', 1)->take(20)->get();

            $top_rated = MoviesResource::collection($topRatedMovies);

           return response()->json([
               "success"=>true,
               "message"=>"Top rated movies reterived successfully",
               "data"=>$top_rated,
           ],200);
       }

       //recently added movies
       public function RecentlyAddedMovies()
       {
            $topRatedMovies = Entertainment::orderBy('created_at','desc')->where('status', 1)->take(20)->get();

            $recently_add = MoviesResource::collection($topRatedMovies);

           return response()->json([
               "success"=>true,
               "message"=>"Recently added movies reterived successfully",
               "data"=>$recently_add,
           ],200);
       }

       //fetch actor
       public function FetchActor()
       {

            $fetch_actor = CastCrew::inRandomOrder()->take(20)->get();

            $fetch_actor = CastCrewResource::collection($fetch_actor);

            return response()->json([
                    "success"=>true,
                    "message"=>"Actor reterived successfully",
                    "data"=>$fetch_actor,
            ],200);
       }

       //hit movies
       public function HitMovies($ref)
       {

            switch($ref){
                    case 'korea';
                        $tag_id = 1;
                        break;
                    case 'india';
                        $tag_id =2;
                        break;
                    case 'china';
                        $tag_id = 3;
                        break;
                    default:
                    return response()->json([
                        "success"=>false,
                        "message"=>"Invalid request",
                        "data"=>[],
                    ],400);
                }

           $hit_movie = Entertainment::whereHas('entertainmentTagMappings',function($query)use($tag_id){
             $query->where('tag_id', $tag_id);
           })->take(10)->get();

           $hit_movie = MoviesResource::collection($hit_movie);

           return response()->json([
               "success"=>true,
               "message"=>"Hit movies reterived successfully",
               "data"=>$hit_movie,
           ],200);
        }

}
