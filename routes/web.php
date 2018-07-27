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
				Route::get('logout', "LoginController@logout")->name('logout');
        	});
            
		});
});
Route::group(['namespace' => 'Admin'], function () {
    Route::group(['middleware' => 'web'], function () {
        Route::get("search","SearchController@search")->name('search');
        Route::get("{name}/profile/{id}","UserController@userData");
        Route::post("profile","UserController@updateuserData")->name('update-profile');
        Route::post("search","SearchController@searchData")->name('search');
        Route::get("searchData","SearchController@searchData")->name('searchData');
        Route::get("{name}/dashboard","UserController@dashboard")->name('dashboard');
        Route::post("followUser","UserController@followUser")->name('followUser');
        
    });
});
Auth::routes();
