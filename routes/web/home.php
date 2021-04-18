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

use App\User;
use \Illuminate\Support\Facades\Gate;
Route::get('/', 'IndexController@index');
Route::get('/test' , function() {
    dd();
   $module = \Module::find('Discount');
   $module->enable();
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
Route::get('download/{user}/file' , function($file) {
    // query -> path ?
    // query -> user ?
   return \Illuminate\Support\Facades\Storage::download(request('path'));
})->name('download.file')->middleware('signed');

Route::middleware('auth')->group(function() {
    Route::prefix('profile')->namespace('Profile')->group(function() {
        Route::get('/' , 'IndexController@index')->name('profile');
        Route::get('twofactor' , 'TwoFactorAuthController@manageTwoFactor')->name('profile.2fa.manage');
        Route::post('twofactor' , 'TwoFactorAuthController@postManageTwoFactor');

        Route::get('twofacto/phone' , 'TokenAuthController@getPhoneVerify')->name('profile.2fa.phone');
        Route::post('twofacto/phone' , 'TokenAuthController@postPhoneVerify');

        Route::get('orders' , 'OrderController@index')->name('profile.orders');
        Route::get('orders/{order}' , 'OrderController@showDetails')->name('profile.orders.detail');
        Route::get('orders/{order}/payment' , 'OrderController@payment')->name('profile.orders.payment');
    });
    Route::post('comments' , 'HomeController@comment')->name('send.comment');
    Route::post('payment' , 'PaymentController@payment')->name('cart.payment');
    Route::get('payment/callback' , 'PaymentController@callback')->name('payment.callback');
});

Route::get('products' , 'ProductController@index');
Route::get('products/{product}' , 'ProductController@single');
