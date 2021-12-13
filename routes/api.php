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

Route::namespace('Auth')->group(function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');

    Route::middleware('jwt.verify')->group(function () {
        Route::post('update-account', 'AuthController@updateAccount');
        Route::post('update-password', 'AuthController@updatePassword');
        Route::get('me', 'AuthController@getAuthenticatedUser');
    });
});
