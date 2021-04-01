<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);
Route::get('/auth/google' ,'Auth\GoogleAuthController@redirect')->name('auth.google');
Route::get('/auth/google/callback' ,'Auth\GoogleAuthController@callback');
Route::get('/auth/token' ,'Auth\AuthTokenController@getToken')->name('2fa.token');
Route::post('/auth/token' ,'Auth\AuthTokenController@postToken');


Route::get('/home', 'HomeController@index')->name('home');
Route::get('/secret' , function() {
    return 'secret';
})->middleware(['auth' , 'password.confirm']);


Route::prefix('profile')->namespace('Profile')->middleware('auth')->group(function() {
    Route::get('/' , 'IndexController@index')->name('profile');
    Route::get('twofactor' , 'TwoFactorAuthController@manageTwoFactor')->name('profile.2fa.manage');
    Route::post('twofactor' , 'TwoFactorAuthController@postManageTwoFactor');

    Route::get('twofacto/phone' , 'TokenAuthController@getPhoneVerify')->name('profile.2fa.phone');
    Route::post('twofacto/phone' , 'TokenAuthController@postPhoneVerify');
});

