<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mobile\ApiController;
use App\Http\Controllers\Mobile\OTPController;


Route::group(['prefix'=>'v1'],function(){

    //test firebase
    Route::post('test-message',[ApiController::class, 'sendMessage']);
    //notification api
    Route::post('send-notification',[ApiController::class, 'sendNotification']);

    //normal login api
    Route::post('register',[ApiController::class, 'register']);
    Route::post('login',[ApiController::class, 'login']);
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


    Route::group(['middleware' => 'auth:sanctum'], function () {
        //logout api
        Route::post('logout', [ApiController::class, 'logout']);

        //home page api
        Route::get('home-banner',[ApiController::class,'HomeBanner']);
        Route::get('recently-added',[ApiController::class,'RecentlyAdded']);
        Route::get('top-rated/{type}',[ApiController::class,'TopRatedItems']);
        Route::get('fetch-actor',[ApiController::class,'FetchActor']);

        //search api
        Route::get('search',[ApiController::class,'Search']);
        Route::get('genres-list',[ApiController::class,'GenresList']);

        //continue watch api
        Route::get('continue-watching',[ApiController::class,'ContinueWatching']);
        Route::post('save-continuewatch', [ApiController::class, 'saveContinueWatch']);
        Route::post('delete-continuewatch', [ApiController::class, 'deleteContinueWatch']);

        //reating and review
        Route::post('rating',[ApiController::class,'Rating']);
        Route::delete('rating/{id}',[ApiController::class,'DeleteRating']);

        //like and dislike
        Route::post('like-dislike/{id}',[ApiController::class,'LikeDislike']);

        //wathch list
        Route::get('watch-list',[ApiController::class,'WatchList']);
        // Route::post('save-watchlist', [ApiController::class, 'saveWatchList']);
        Route::post('toggle-watchlist/{id}', [ApiController::class, 'toggleWatchList']);

        //downlaoded
        Route::post('save-download',[ApiController::class,'SaveDownload']);
        Route::get('download-list',[ApiController::class,'DownloadList']);
        Route::delete('delete-download/{id}',[ApiController::class,'DeleteDownload']);

        //subsciption
        Route::get('plans',[ApiController::class,'PlansList']);
        Route::post('subscription',[ApiController::class,'Subscription']);

        //profile
        Route::get('profile',[ApiController::class,'Profile']);
        Route::post('logout',[ApiController::class,'Logout']);
        Route::post('update-profile', [ApiController::class, 'updateProfile']);
        Route::post('delete-account',[ApiController::class,'deleteAccount']);

        //payment and subscription
        Route::post('get-qr',[ApiController::class,'GetQR']);
        Route::post('check-payment-stauts', [ApiController::class, 'checkPaymentStatus']);
        Route::get('payment-history', [ApiController::class, 'PaymentHistory']);

        //details page api
        Route::get('movie-details/{id}',[ApiController::class,'MovieDetails']);
        Route::get('tv-details/{id}',[ApiController::class,'TvShowDetails']);

        //notification api
        Route::get('notifications', [ApiController::class, 'Notifications']);
        Route::post('notification/{id}/read', [ApiController::class, 'MarkAsRead']);
        Route::post('notification/all-read', [ApiController::class, 'MarkAllAsRead']);
        Route::delete('notification/{id}/delete', [ApiController::class, 'DeleteNotification']);
        Route::delete('notification/all-delete', [ApiController::class, 'DeleteAllNotifications']);

        //fcm token update
        Route::post('update-fcm-token', [ApiController::class, 'UpdateFcmToken']);
    });

});
