<?php

use Illuminate\Support\Facades\Route;
use Modules\Frontend\Http\Controllers\Auth\OTPController;
use Modules\Frontend\Http\Controllers\Auth\AuthController;

Route::group(['middleware'=>'guest'],function(){


    // Login with OTP
    // Route::get('/login', [OTPController::class, 'otpLogin'])->name('login');
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login-form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/register', [AuthController::class, 'registration'])->name('register-page');
    Route::post('/store-user', [AuthController::class, 'store'])->name('store-user');
    Route::get('/forget-password', [AuthController::class, 'forgetpassword'])->name('forget-password');
    Route::post('/send-otp',[OTPController::class,'sendOTP'])->name('send.otp');
    Route::post('/verify-otp', [OTPController::class, 'verifyOTP'])->name('verify.otp');
    Route::post('/resend-otp', [OTPController::class, 'resendOTP'])->name('resend.otp');
    Route::post('/auth/otp-login-store', [OTPController::class, 'otpLoginStore'])->name('auth.otp-login-store');
    Route::get('/auth/check-user-exists', [OTPController::class, 'checkUserExists'])->name('check.user.exists');


    // Login with Google
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});



