<?php


Route::get('/' , function() {
   return view('admin.index');
});

Route::resource('users', 'User\UserController');
Route::get('/users/{user}/permissions', 'User\PermissionController@create')->name('users.permissions')->middleware('can:staff-user-permissions');
Route::post('/users/{user}/permissions', 'User\PermissionController@store')->name('users.permissions.store')->middleware('can:staff-user-permissions');
Route::resource('permissions', 'PermissionController');
Route::resource('roles', 'RoleController');
Route::resource('products' , 'ProductController')->except(['show']);
Route::resource('products.gallery' , 'ProductGalleryController');



Route::post('attribute/values' , 'AttributeController@getValues');

Route::resource('orders' , 'OrderController');
Route::get('orders/{order}/orders', 'OrderController@payments')->name('orders.payments');

Route::get('comments/unapproved', 'CommentController@unapproved')->name('comments.unapproved');
Route::resource('comments' , 'CommentController')->only(['index' , 'update' , 'destroy']);
Route::resource('categories' , 'CategoryController');
