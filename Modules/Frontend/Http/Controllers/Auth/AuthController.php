<?php

namespace Modules\Frontend\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use App\Events\Frontend\UserRegistered;
use Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Redirect;
use Str;
use App\Models\Device;
use Modules\Frontend\Trait\LoginTrait;

class AuthController extends Controller
{
    use LoginTrait;
    /**
     * Display a listing of the resource.
     */
    public function loginForm()
    {
        return view('frontend::auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|exists:users,username',
            'password' => 'required',
        ]);

        if(Auth::attempt($request->only('username', 'password'))) {
            return redirect()->route('home')->with('message', 'Login successful');
        }else{
            return redirect()->back()->with('error', 'Invalid credentials');
        };
    }

    /**
     * Show the form for creating a new resource.
     */
    public function registration()
    {
        return view('frontend::auth.registration');
    }

    public function forgetpassword()
    {
        return view('frontend::auth.forgetpassword');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username'=> 'required|unique:users,username',
            'password' => 'required|min:6|confirmed',
        ]);
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'user_type' => 'user',
            'login_type' => 'username'
        ]);
        $user->assignRole('user');
        $user->createOrUpdateProfileWithAvatar();
        Auth::login($user);
        return redirect()->route('home')->with('message', 'Registration successful!');
    }

    public function Logout(Request $request){
        $user=Auth::user();

        Auth::logout();

        $this->removeDevice($user, $request);

        return redirect()->route('home');

    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('frontend::show');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('frontend::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }


     // Redirect to Google
     public function redirectToGoogle()
     {
         return Socialite::driver('google')->redirect();
     }

     // Handle Google Callback
     public function handleGoogleCallback(Request $request)
     {

         try {
             $googleUser = Socialite::driver('google')->stateless()->user();

             $user = User::where('email', $googleUser->getEmail())->first();

             if (!$user) {

                $fullName = $googleUser->getName();

                $nameParts = explode(' ', $fullName);

                $firstName = isset($nameParts[0]) ? $nameParts[0] : ''; // First part of the name
                $lastName = isset($nameParts[1]) ? $nameParts[1] : $firstName;  // Second part as last name


                $data = [
                    // 'first_name' => $firstName,
                    // 'last_name' => $lastName,
                    'username'=>$fullName,
                    'email' =>  $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(10)),
                    'user_type' => 'user',
                    'login_type' => 'google'
                ];

                $user = User::create($data);

                $request->session()->regenerate();

                $user->createOrUpdateProfileWithAvatar();

                $user->assignRole($data['user_type']);

                $user->save();
             }

             if($user->login_type == 'google'){

                $current_device=$request->has('device_id')?$request->device_id:$request->getClientIp();
                //clode device limit
                // $response=$this->CheckDeviceLimit($user, $current_device);

                if(isset($response['error'])) {
                    return Redirect::to('/login')->with('error', $response['error']);
                }

                 $this->setDevice($user,$request);
                 $user = Auth::login($user);
             }
             else
             {
                $user=Auth::user();
                Auth::logout();
                $this->removeDevice($user, $request);
                return Redirect::to('/login')->with('error', 'Something went wrong! During login');
             }

             return redirect()->intended('/'); // Redirect to intended page
         } catch (\Exception $e) {
             return Redirect::to('/login')->with('error', 'Something went wrong!');
         }
     }

     // Redirect to Apple
     public function redirectToApple()
     {
         return Socialite::driver('apple')->redirect();
     }

     // Handle Apple Callback
     public function handleAppleCallback(Request $request)
     {

         try {
             $appleUser = Socialite::driver('apple')->stateless()->user();
             $user = User::where('email', $appleUser->getEmail())->first();

             if (!$user) {

                $fullName = $appleUser->getName();

                // Split the full name into an array
                $nameParts = explode(' ', $fullName);

                // Handle different cases (when there's no last name or multiple names)
                $firstName = isset($nameParts[0]) ? $nameParts[0] : ''; // First part of the name
                $lastName = isset($nameParts[1]) ? $nameParts[1] : $firstName;  // Second part as last name


                $data = [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' =>  $appleUser->getEmail(),
                    'password' => Hash::make(Str::random(8)),
                    'user_type' => 'user',
                    'login_type' => 'google'
                ];

                $user = User::create($data);

                $request->session()->regenerate();


                $user->assignRole($data['user_type']);
                $user->createOrUpdateProfileWithAvatar();
                $user->save();
             }

             if($user->login_type == 'apple')
             {
                $current_device=$request->has('device_id')?$request->device_id:$request->getClientIp();
                $response=$this->CheckDeviceLimit($user, $current_device);

                if(isset($response['error'])) {

                    return Redirect::to('/login')->with('error', $response['error']);

                }
                 $this->setDevice($user, $request);
                 Auth::login($user);
             }
             else
             {
                $user=Auth::user();
                Auth::logout();
                $this->removeDevice($user, $request);
                return Redirect::to('/login')->with('error', 'Something went wrong! During login');
             }

             return redirect()->intended('/'); // Redirect to intended page
         } catch (\Exception $e) {
             return Redirect::to('/login')->with('error', 'Something went wrong!');
         }
     }


}
