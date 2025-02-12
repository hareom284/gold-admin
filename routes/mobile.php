<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mobile\ApiController;


Route::group(['prefix'=>'v1'],function(){
    //google login api
    Route::get('auth/google',[ApiController::class, 'redirectToGoogle']);
    Route::get('auth/google/callback',[ApiController::class, 'handleGoogleCallback']);

    //phone number login api
    Route::post('/auth/otp-login-store', [ApiController::class, 'otpLoginStore']);
    Route::get('/auth/check-user-exists', [ApiController::class, 'checkUserExists']);

    //logout api
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('logout', [ApiController::class, 'logout']);
    });

    //home page api
    Route::get('recently-added',[ApiController::class,'RecentlyAdded']);
    Route::get('home-banner',[ApiController::class,'HomeBanner']);
    Route::get('top-rated/{type}',[ApiController::class,'TopRatedItems']);
    Route::get('fetch-actor',[ApiController::class,'FetchActor']);
});
