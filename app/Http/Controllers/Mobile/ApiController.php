<?php

namespace App\Http\Controllers\Mobile;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Device;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\UserMultiProfile;
use Modules\Banner\Models\Banner;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Entertainment\Models\Like;
use Modules\Subscriptions\Models\Plan;
use Laravel\Socialite\Facades\Socialite;
use Modules\Entertainment\Models\Review;
use Illuminate\Support\Facades\Validator;
use Modules\Entertainment\Models\Watchlist;
use Modules\Entertainment\Models\UserReminder;
use Modules\Subscriptions\Models\Subscription;
use Modules\Entertainment\Models\ContinueWatch;
use Modules\Entertainment\Models\Entertainment;
use App\Http\Resources\Mobile\Genral\PlanResource;
use App\Http\Resources\Mobile\Home\BannerResource;
use App\Http\Resources\Mobile\Home\ItemListResource;
use App\Http\Resources\Mobile\Genral\WatchListResource;
use Modules\Entertainment\Models\EntertainmentDownload;
use Modules\User\Transformers\UserMultiProfileResource;
use App\Http\Resources\Mobile\Detail\MovieDetailResource;
use App\Http\Resources\Mobile\Detail\TvShowDetailResource;
use App\Http\Resources\Mobile\Home\ContinueWatchingResource;

class ApiController extends Controller
{
        //normal login api
        public function login(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'username' => 'required|exists:users,username',
                'password' => 'required',
            ]);
        if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }
        $user = User::where('username', $request->username)->first();
        if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken('authToken')->plainTextToken;
                    return response()->json([
                        'success' => true,
                        'message' => 'User login successfully.',
                        'token' => $token,
                        'user' => $user,
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Password mismatch',
                    ], 422);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'User does not exist',
                ], 422);
            }
        }

        public function register(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'username' => 'required|unique:users,username',
                'password'=>'required|min:8|confirmed',
            ]);

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => $validator->errors()->first(),
                    ], 422);
                }
                $user = User::create([
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'user_type' => 'user',
                    'login_type' => 'username'
                ]);
                $user->assignRole('user');
                $token = $user->createToken('authToken')->plainTextToken;
                return response()->json([
                    'success' => true,
                    'message' => 'User created successfully.',
                    'token' => $token,
                    'user' =>$user
                ],200);
        }

        //test firebase connection
        public function sendMessage()
        {
            $database = app('firebase.database');
            $database->getReference('users/user2')->push([
                'name' => 'user2',
                'email' => 'john.doe@example.com',
            ]);
            return response()->json([
                'success'=>true,
                'message'=>'Message sent successfully',
            ],200);
        }

        // Redirect to Google
        public function redirectToGoogle()
        {
            return Socialite::driver('google')->stateless()->redirect();
        }

        //get firebase login user
        public function generateToken(){
            $auth = app('firebase.auth');
            $token =  $auth->createCustomToken('NcNS24KcdBRlDFk96jNF6f2ebN53');
            return response()->json([
                'success'=>true,
                'token'=>$token->toString(),
            ],200);
            try {
                $users = $auth->listUsers();
                $userList = [];

                foreach ($users as $user) {
                    $userList[] = [
                        'uid' => $user->uid,
                        'email' => $user->email,
                        'name' => $user->displayName,
                        'phone' => $user->phoneNumber,
                    ];
                }
                return response()->json([
                    'success' => true,
                    'users' => $userList
                ], 200);

            } catch (\Exception $e) {
                return response()->json([
                    'error' => $e->getMessage()
                ], 500);
            }
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

            $auth = app('firebase.auth');
            $verifiedIdToken =  $auth->verifyIdToken($request->callback_token);
            $email = $verifiedIdToken->claims()->get('email');
            $name = $verifiedIdToken->claims()->get('name');


           // Check if a user with the email exists
           $existingUser = User::where('email', $email)->first();

               if (!$existingUser) {
                   // Register new user
                   $data = [
                       'username' => $name,
                       'email' => $email,
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
       public function logout()
       {
           auth('sanctum')->user()->tokens()->delete();
           return response()->json([
               'success'=>true,
               'message'=>'Logout successfully',
           ],200);
       }

       //accoutn delete api
       public function deleteAccount()
       {
            $user = auth('sanctum')->user();
            Device::where('user_id', $user->id)->forceDelete();
            UserMultiProfile::where('user_id', $user->id)->forceDelete();
            Subscription::where('user_id', $user->id)->update(['status' => 'deactivated']);
            User::where('id', $user->id)->forceDelete();
            ContinueWatch::where('user_id', $user->id)->delete();
            Watchlist::where('user_id',$user->id)->delete();
            EntertainmentDownload::where('user_id',$user->id)->delete();
            UserReminder::where('user_id', $user->id)->delete();

            $user->forceDelete();

            return response()->json([
                'status' => true,
                'message' => 'Account deleted successfully',
            ], 200);
       }

       //HomeBanner
       public function HomeBanner()
       {
           $bannerList = Banner::where('status',1)->get();
          $banners = BannerResource::collection($bannerList);

            return response()->json([
                'success' => true,
                'message' => "Banner list reterived successfully",
                'data' => $banners
            ],200);
       }

       //continue watching
       public function ContinueWatching()
       {
            $user = User::where('id',auth('sanctum')->id())->first();
            if($user){
                $continueWatchList = ContinueWatch::where('user_id', 3)
                ->whereNotNull('watched_time')
                ->whereNotNull('total_watched_time')
                ->whereHas('entertainment', function ($query) {
                    $query->where('status', 1);
                })
                ->with(['entertainment', 'episode', 'video'])
                ->orderBy('id', 'desc')
                ->get();
                $continueWatch = ContinueWatchingResource::collection($continueWatchList);

                return response()->json([
                    'success' => true,
                    'message' => "Continue watching list reterived successfully",
                    'data' => $continueWatch
                ],200);
            }

            return response()->json([
                'success' => true,
                'message' => "Continue watching list reterived successfully",
                'data' => [],
            ],200);

       }

       //save contiue watch
       public function saveContinueWatch(Request $request)
        {
            $user = auth('sanctum')->user();
            if(!$user){
                return response()->json([
                    'success' => false,
                    'message' => 'No user found',
                ], 404);
            }
            // $user = User::where('id',15)->first();
            $watch_data = $request->all();
            $watch_data['total_watched_time'] = isset($watch_data['total_watched_time']) && substr_count($watch_data['total_watched_time'], ':') == 1 ? $watch_data['total_watched_time'] . ':00' : $watch_data['total_watched_time'];
            $watch_data['user_id'] = $user->id;

            $profile_id=$request->has('profile_id') && $request->profile_id
            ? $request->profile_id
            : getCurrentProfile($user->id, $request);

            $watch_data['profile_id'] =  $profile_id;

            $result = ContinueWatch::updateOrCreate(['entertainment_id' => $request->entertainment_id, 'user_id' => $user->id, 'entertainment_type' => $request->entertainment_type,'profile_id'=>$profile_id,'episode_id'=>$request->episode_id], $watch_data);

            return response()->json([
                'success' => true,
                'message' => "Continue watching saved successfully",
                'data' => $result
            ],200);
        }

        //delete continue wtch
        public function deleteContinueWatch(Request $request)
        {
            $continuewatch = ContinueWatch::where('id', $request->id)->first();

            if ($continuewatch == null) {
                return response()->json([
                    'success'=>false,
                    'message'=>"Continue watch not found",
                ],404);
            }
            $continuewatch->delete();
            return response()->json([
                'success' => true,
                'message' => "Continue watch deleted successfully",
            ],200);
        }

        //top rated movies
       public function TopRatedItems($type)
       {

            $topRatedItems = Entertainment::whereBetween('IMDb_rating',[6,10])->where(['status'=>1,'type'=>$type])->orderBy('IMDb_rating','desc')->inRandomOrder()->limit(15)->get();

            $top_rated = ItemListResource::collection($topRatedItems);

           return response()->json([
               "success"=>true,
               "message"=>"Top rated $type reterived successfully",
               "data"=>$top_rated,
           ],200);
       }

       //recently added movies
       public function RecentlyAdded()
       {
            $items = Entertainment::orderBy('created_at','desc')->where('status', 1)->take(20)->get();

            $recently_add = ItemListResource::collection($items);

           return response()->json([
               "success"=>true,
               "message"=>"Recently added items reterived successfully",
               "data"=>$recently_add,
           ],200);
       }

       //hit movies
    //    public function HitMovies($ref)
    //    {
    //         switch($ref){
    //                 case 'korea';
    //                     $tag_id = 1;
    //                     break;
    //                 case 'india';
    //                     $tag_id =2;
    //                     break;
    //                 case 'china';
    //                     $tag_id = 3;
    //                     break;
    //                 default:
    //                 return response()->json([
    //                     "success"=>false,
    //                     "message"=>"Invalid request",
    //                     "data"=>[],
    //                 ],400);
    //             }

    //             $hit_movie = Entertainment::whereHas('entertainmentTagMappings',function($query)use($tag_id){
    //                 $query->where('tag_id', $tag_id);
    //             })->take(10)->get();

    //             $hit_movie = MoviesResource::collection($hit_movie);

    //             return response()->json([
    //                 "success"=>true,
    //                 "message"=>"Hit movies reterived successfully",
    //                 "data"=>$hit_movie,
    //             ],200);

    //     }

    public function MovieDetails($id)
    {
        $movie = Entertainment::where('id', $id)
                ->with([
                    'entertainmentGenerMappings',
                    'plan',
                    'entertainmentReviews.user',
                    'entertainmentTalentMappings',
                    'entertainmentStreamContentMappings',
                    'entertainmentDownloadMappings'
                ])
                ->first();
            if(!$movie){
                return response()->json([
                    'success'=>false,
                    'message'=>'Movie not found',
                ],404);
            }

            $data = new MovieDetailResource($movie);
            return response()->json([
                'success'=>true,
                'message'=>'Movie details reterived successfully',
                'data'=>$data,
            ],200);
    }

    public function TvShowDetails($id)
    {
        $tvshow = Entertainment::where('id', $id)
            ->with('entertainmentGenerMappings', 'plan', 'entertainmentReviews', 'entertainmentTalentMappings', 'season', 'episode')
            ->first();

        if(!$tvshow){
            return response()->json([
                'success'=>false,
                'message'=>'Tvshow not found',
            ],404);
        }

         $data = new TvShowDetailResource($tvshow);
         return response()->json([
            'success'=>true,
            'message'=>'Tvshow details reterived successfully',
            'data'=>$data,
        ],200);
    }

    public function  Rating(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id'=>'nullable',
            'entertainment_id' => 'required',
            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'success'=>false,
                'message'=>$validator->errors(),
            ],422);
        }

        $user_id = auth('sanctum')->id();
        Review::updateOrCreate([
                'entertainment_id' => $request->entertainment_id,
                'user_id' => $user_id,
                'rating' => $request->rating,
                'review' => $request->review
            ]);
        return response()->json([
            'success'=>true,
            'message'=>'Rating added successfully',
        ],200);
    }

    public function DeleteRating($id)
    {
        $user_id = auth('sanctum')->id();
        $review = Review::where('id',$id)->where('user_id',$user_id)->first();
        if($review){
            $review->delete();
            return response()->json([
                'success'=>true,
                'message'=>'Rating deleted successfully',
            ],200);
        }
        return response()->json([
            'success'=>false,
            'message'=>'Rating not found',
        ],404);
    }

    public function WatchList()
    {
        $user_id = auth('sanctum')->id();
        $watchList = Watchlist::where('user_id', $user_id)
        ->orderBy('updated_at', 'desc')->get();
        // $entertainment = Entertainment::whereIn('id', $watchList->pluck('entertainment_id'))->get();
        // return $entertainment;
        $data = WatchListResource::collection($watchList);

        return response()->json([
            'success'=>true,
            'message'=>'Watchlist reterived successfully',
            'data'=>$data,
        ],200);

    }

    public function saveWatchList(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'entertainment_id' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'success'=>false,
                'message'=>$validator->errors(),
            ],422);
        }
        $entertainment = Entertainment::find($request->entertainment_id);
        if(!$entertainment){
            return response()->json([
                'success'=>false,
                'message'=>'Entertainment not found',
            ],404);
        }

        $user_id = auth('sanctum')->id();
        Watchlist::create([
            'entertainment_id' => $request->entertainment_id,
            'user_id' => $user_id,
        ]);

        return response()->json([
            'success'=>true,
            'message'=>'Watchlist added successfully',
        ],200);
    }

    public function deleteWatchList($id)
    {
        $user_id = auth('sanctum')->id();
        $watchlist = Watchlist::where('id',$id)->where('user_id',$user_id)->first();
        if($watchlist){
            $watchlist->forceDelete();
            return response()->json([
                'success'=>true,
                'message'=>'Watchlist deleted successfully',
            ],200);
        }
        return response()->json([
            'success'=>false,
            'message'=>'Watchlist not found',
        ],404);
    }

    public function  LikeDislike($entertainment_id)
    {
        $user_id = auth('sanctum')->id();
        $entertainment = Entertainment::where('id', $entertainment_id)->first();
        if(!$entertainment){
            return response()->json([
                'success'=>false,
                'message'=>'Entertainment not found',
            ],404);
        }

        $like = Like::where('entertainment_id', $entertainment_id)->where('user_id', $user_id)->first();
        if($like){
            $like->is_like = !$like->is_like;
            $like->save();
            return response()->json([
                'success'=>true,
                'message'=>'Like status updated successfully',
            ],200);
        }else{
            Like::create([
                'entertainment_id' => $entertainment_id,
                'user_id' => $user_id,
                'type' => $entertainment->type,
                'is_like' => 1,
            ]);
            return response()->json([
                'success'=>true,
                'message'=>'Like added successfully',
            ],200);
        }

    }

    public function PlansList()
    {
        $plans = Plan::all();
        $data = PlanResource::collection($plans);
        return response()->json([
            'success'=>true,
            'message'=>'Plans reterived successfully',
            'data'=>$data,
        ],200);
    }

    public function Subscription(Request $request)
    {
        $user_id = auth('sanctum')->id();
        $validator = Validator::make($request->all(),[
            'plan_id' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'success'=>false,
                'message'=>$validator->errors(),
            ],422);
        }
        $plan = Plan::find($request->plan_id);

        // Validate plan existence
        if (!$plan) {
            return response()->json([
                'success' => false,
                'message' => 'Plan not found',
            ], 404);
        }

        // Check existing subscription
        $subscription = Subscription::where('user_id', $user_id)->first();

        if ($subscription) {
            return $this->handleExistingSubscription($subscription, $plan);
        }

        // Create a new subscription if none exists
        return $this->createNewSubscription($user_id, $plan);

    }

    private function handleExistingSubscription($subscription, $plan)
    {
        if ($subscription->end_date > Carbon::now()) {
            return response()->json([
                'success' => false,
                'message' => 'You already have an active subscription.',
            ], 400);
        }

        // Update expired subscription
        $subscription->update([
            'plan_id' => $plan->id,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays($plan->duration_value),
            'amount' => $plan->price,
            'total_amount' => $plan->price,
            'duration' => $plan->duration_value,
            'status' => 'active',
        ]);
        auth('sanctum')->user()->update(['is_subscribe' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Subscription renewed successfully.',
        ], 200);
    }


    private function createNewSubscription($userId, $plan)
    {
        Subscription::create([
            'user_id' => $userId,
            'plan_id' => $plan->id,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays($plan->duration_value),
            'amount' => $plan->price,
            'total_amount' => $plan->price,
            'duration' => $plan->duration_value,
            'status' => 'active',
        ]);
        auth('sanctum')->user()->update(['is_subscribe' => true]);
    }

    public function profile()
    {
        $user =auth('sanctum')->user();

        $Profile=UserMultiProfile::where('user_id', $user->id)->get();

        $userProfile = UserMultiProfileResource::collection($Profile);

        return $userProfile;
    }

    public function Search(Request $request)
    {
        $entertainment = Entertainment::where('name', 'like', '%'.$request->key.'%')
        ->orWhere('description','like','%'.$request->key.'%')
        ->orWhereHas('entertainmentGenerMappings.genre', function ($query) use ($request) {
            $query->where('name', 'like', '%'.$request->key.'%');
        })
        ->orWhereHas('entertainmentTalentMappings.talentprofile',function($query) use($request){
            $query->where('name','like','%'.$request->key.'%');
        })
        ->orWhereHas('season', function ($query) use ($request) {
            $query->where('name', 'like', '%'.$request->key.'%');
        })
        ->orWhereHas('episode', function ($query) use ($request) {
            $query->where('name', 'like', '%'.$request->key.'%');
        })
        ->get();
        return  ItemListResource::collection($entertainment);
    }

}
