<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mobile\ApiController;
use App\Http\Controllers\Mobile\OTPController;


Route::group(['prefix'=>'v1'],function(){

    Route::group(['middleware' => 'auth:sanctum'], function () {
        //logout api
        Route::post('logout', [ApiController::class, 'logout']);
        //continue watch api
        Route::post('save-continuewatch', [ApiController::class, 'saveContinueWatch']);
        Route::post('delete-continuewatch', [ApiController::class, 'deleteContinueWatch']);
    });

    //google login api
    Route::get('auth/google',[ApiController::class, 'redirectToGoogle']);
    Route::post('auth/google/callback',[ApiController::class, 'handleGoogleCallback']);

    // //phone number login api
    // Route::post('/auth/otp-login-store', [ApiController::class, 'otpLoginStore']);
    // Route::get('/auth/check-user-exists', [ApiController::class, 'checkUserExists']);

    //otpp login api
    Route::post('send-otp',[OTPController::class,'sendOTP']);
    Route::post('verify-otp', [OTPController::class, 'verifyOTP']);
    Route::post('resend-otp', [OTPController::class, 'resendOTP']);
    Route::post('opt-user-store', [OTPController::class, 'otpUserStore']);


    //home page api
    Route::get('home-banner',[ApiController::class,'HomeBanner']);
    Route::get('continue-watching',[ApiController::class,'ContinueWatching']);
    Route::get('recently-added',[ApiController::class,'RecentlyAdded']);
    Route::get('top-rated/{type}',[ApiController::class,'TopRatedItems']);
    Route::get('fetch-actor',[ApiController::class,'FetchActor']);

});
