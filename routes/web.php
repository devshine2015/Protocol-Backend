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
        return redirect('/search');
});
Route::group(['namespace' => 'Auth'], function () {

        Route::group(['middleware' => 'web'], function () {
        	Route::group(['middleware' => 'guest'], function () {
        		Route::get('weblogin/{provider}','LoginController@redirectToProvider')->where([ 'provider' => 'facebook|google' ])->name('weblogin');
    			Route::get('login/{provider}/callback', 'LoginController@handleProviderCallback')->where([ 'provider' => 'facebook|google' ]);	
        	});
        	Route::group(['middleware' => 'auth'], function() {
            });
			 Route::get('logout', "LoginController@logout")->name('logout');

        });
});
Route::get('logoutWeb', "Auth\LoginController@logoutWeb")->name('logoutWeb');
Route::get('register/{code}', "Auth\RegisterController@checkCode");
Route::get("checkLogin","Admin\UserController@checkLogin")->name('checkLogin')->middleware('auth:api');
Route::group(['namespace' => 'Admin'], function () {
    Route::group(['middleware' => 'web'], function () {
        Route::get("search","SearchController@search")->name('search');
        Route::get("{name}/profile/{id}","UserController@userData");
        Route::post("profile","UserController@updateuserData")->name('update-profile');
        Route::post("search","SearchController@searchData")->name('search');
        Route::get("searchData","SearchController@searchData")->name('searchData');
        Route::get("{name}/dashboard","UserController@dashboard")->name('dashboard');
        Route::post("followUser","UserController@followUser")->name('followUser');
        Route::post("updatenotify","UserController@updateNotification")->name('updatenotify');
        //meassge data
        Route::resource('messages','AdminController');
        Route::get('message-list', ['as' => 'message.data', 'uses' => 'AdminController@anyData']);
        Route::post("checkDate","AdminController@checkDate")->name('checkDate');
        //inbox data
        Route::get('share-list', ['as' => 'share.data', 'uses' => 'ShareController@anyData']);
        //category data
        Route::resource('subCategories','SubCategoryController');
        Route::get('category-list', ['as' => 'category.data', 'uses' => 'SubCategoryController@anyData']);
        Route::get('change-status/{id}','SubCategoryController@changeStatus');
        //relation data
        Route::resource('relations','RelationController');
        Route::get('relation-list', ['as' => 'relation.data', 'uses' => 'RelationController@anyData']);
        Route::get('relation-status/{id}','RelationController@changeStatus');
        //Route::resource('share','ShareController');
        Route::get('bridges/{id}','ShareController@shareBridge');
        Route::get('notes/{id}','ShareController@shareNote');
        Route::get('elements/{id}','ShareController@shareElement');
        Route::get('lists/{id}','ShareController@shareList');
        Route::resource('elements','ElementController');
    });
});
Route::post('api/login','API\UserController@login');

Auth::routes();
