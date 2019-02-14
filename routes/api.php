<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return [
        'error_code'    => 0,
        'data'          => $request->user()
    ];
});
Route::get('/login_message','API\UserController@loginMessage');
$resourcesRequireAuthToWrite = [
    'relations'             => 'API\RelationController',
    'elements'              => 'API\ElementController',
    'bridges'               => 'API\BridgeController',
    'notes'                 => 'API\NoteController',
    'userFollow'            => 'API\UserFollowController',
    'elementFollow'         => 'API\ElementFollowController',
    'noteCategory'          => 'API\NoteCategoryController',
    'contentReport'         => 'API\ContentReportController',
    'categories'            => 'API\CategoryController',
    'sub-category'          => 'API\SubCategoryController',
    'sub-category'          => 'API\SubCategoryController',
    'trackSocialSiteCross'  => 'API\SocialTrackController',
    'lists'                 =>  'API\ListController'
];

$withAuthRouteOptions = [
    'only'          => ['store', 'update', 'destroy'],
    'middleware'    => ['auth:api'],
];

$withoutAuthRouteOptions = [
    'only'          => ['index','show']
];

foreach ($resourcesRequireAuthToWrite as $name => $controller) {
    Route::apiResource($name, $controller, $withAuthRouteOptions);
    Route::apiResource($name, $controller, $withoutAuthRouteOptions);
}

Route::post('/bridgeCross', 'API\BridgeController@bridgeCross');
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/elementData', 'API\ElementController@elementData');
    Route::post('/contentLike', 'API\UserFollowController@contentLike');
    Route::get('/categoryList', 'API\CategoryController@categoryList');
    Route::get('/login', 'API\UserController@login');
    Route::post('/sendMail', 'API\UserController@sendMail');
});
Route::post('/logout', 'API\UserController@logout');
Route::post('/register', 'API\UserController@register');
Route::post('/search/page', 'API\PageController@search');
Route::delete('/deleteElement/{id}', 'API\ElementController@deleteElement');
Route::post('/search/pages', 'API\PageController@batchSearch');

Route::group(['middleware' => ['web']], function () {
    Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider')->where([ 'provider' => 'facebook|google' ]);
    Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->where([ 'provider' => 'facebook|google' ]);
});
