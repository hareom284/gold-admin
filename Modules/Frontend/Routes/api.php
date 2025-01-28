<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Frontend\Http\Controllers\Auth\AuthApiController;
use Modules\Frontend\Http\Controllers\Auth\OTPController;
use Modules\Frontend\Http\Controllers\Auth\AuthController;
use Modules\Frontend\Http\Controllers\DashboardController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/


Route::get('top-10-movie', [DashboardController::class, 'Top10Movies']);
Route::get('latest-movie', [DashboardController::class, 'LatestMovies']);
Route::get('fetch-languages', [DashboardController::class, 'FetchLanguages']);
Route::get('popular-movie', [DashboardController::class, 'PopularMovies']);
Route::get('top-channels', [DashboardController::class, 'TopChannels']);
Route::get('popular-tvshows', [DashboardController::class, 'PopularTVshows']);
Route::get('favorite-personality', [DashboardController::class, 'favoritePersonality']);
Route::get('free-movie', [DashboardController::class, 'FreeMovies']);
Route::get('get-gener', [DashboardController::class, 'GetGener']);
Route::get('get-video', [DashboardController::class, 'GetVideo']);
Route::get('base-on-last-watch-movie', [DashboardController::class, 'GetLastWatchContent']);
Route::get('most-like-movie', [DashboardController::class, 'MostLikeMoive']);
Route::get('most-view-movie', [DashboardController::class, 'MostviewMoive']);
Route::get('country-tranding-movie', [DashboardController::class, 'TrandingInCountry']);
Route::get('favorite-genres', [DashboardController::class, 'FavoriteGenres']);
Route::get('user-favorite-personality', [DashboardController::class, 'UserfavoritePersonality']);

Route::get('web-continuewatch-list', [DashboardController::class, 'ContinuewatchList']);


//google login api
Route::get('auth/google',[AuthApiController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback',[AuthApiController::class, 'handleGoogleCallbackApi'])->name('auth.google.callback');

//phone number login api
Route::post('/auth/otp-login-store', [AuthApiController::class, 'otpLoginStoreApi'])->name('auth.otp-login-store');
Route::get('/auth/check-user-exists', [AuthApiController::class, 'checkUserExistsApi'])->name('check.user.exists');



Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('logout', [AuthApiController::class, 'logoutApi'])->name("auth.logout");
});

