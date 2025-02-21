<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mobile\ApiController;
use App\Http\Controllers\Mobile\OTPController;


Route::group(['prefix'=>'v1'],function(){

    //google login api
    Route::get('auth/google',[ApiController::class, 'redirectToGoogle']);
    Route::get('auth/google/callback',[ApiController::class, 'handleGoogleCallback']);

    //otpp login api
    Route::post('send-otp',[OTPController::class,'sendOTP']);
    Route::post('verify-otp', [OTPController::class, 'verifyOTP']);
    Route::post('resend-otp', [OTPController::class, 'resendOTP']);
    Route::post('opt-user-store', [OTPController::class, 'otpUserStore']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        //logout api
        Route::post('logout', [ApiController::class, 'logout']);
        //continue watch api
        Route::post('save-continuewatch', [ApiController::class, 'saveContinueWatch']);
        Route::post('delete-continuewatch', [ApiController::class, 'deleteContinueWatch']);

        //home page api
        Route::get('home-banner',[ApiController::class,'HomeBanner']);
        Route::get('continue-watching',[ApiController::class,'ContinueWatching']);
        Route::get('recently-added',[ApiController::class,'RecentlyAdded']);
        Route::get('top-rated/{type}',[ApiController::class,'TopRatedItems']);
        Route::get('fetch-actor',[ApiController::class,'FetchActor']);

        //details page api
        Route::get('movie-details/{id}',[ApiController::class,'MovieDetails']);

        //reating and review
        Route::post('rating',[ApiController::class,'Rating']);
        Route::delete('rating/{id}',[ApiController::class,'DeleteRating']);

        //like and dislike
        Route::post('like-dislike/{id}',[ApiController::class,'LikeDislike']);

        //wathch list
        Route::get('watch-list',[ApiController::class,'WatchList']);
        Route::post('save-watchlist', [ApiController::class, 'saveWatchList']);
        Route::delete('delete-watchlist/{id}', [ApiController::class, 'deleteWatchList']);
    });
});
