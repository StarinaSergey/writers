<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// CLIENT AUTH ROUTES
Route::group([ 'prefix' => 'auth', 'middleware' => 'api', 'namespace' => 'Auth'], function(){

    Route::post('/login', 'Login');
//    Route::post('/logout', 'LogoutApi')->middleware('auth:api');
//    Route::post('/refresh-token', 'RefreshToken')->middleware('auth:api');
//    Route::post('/register', 'Register');
//    Route::post('/register-simple', 'RegisterSimple');
//
//    Route::post('/send-reset-password-email', 'SendResetPasswordEmail');
//    Route::post('/validate-reset-token', 'ValidateResetToken');
//    Route::put('/set-new-password', 'SetNewPassword');
});
