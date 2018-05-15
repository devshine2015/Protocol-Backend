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

$resourcesRequireAuthToWrite = [
    'relations' => 'API\RelationController',
    'elements'  => 'API\ElementController',
    'bridges'   => 'API\BridgeController',
    'notes'     => 'API\NoteController',
];

$withAuthRouteOptions = [
    'only'          => ['store', 'update', 'destroy'],
    'middleware'    => ['auth:api'],
];

$withoutAuthRouteOptions = [
    'only'          => ['index', 'show']
];

foreach ($resourcesRequireAuthToWrite as $name => $controller) {
    Route::apiResource($name, $controller, $withAuthRouteOptions);
    Route::apiResource($name, $controller, $withoutAuthRouteOptions);
}

Route::post('/register', 'Api\UserController@register');
Route::post('/login', 'Api\UserController@login');
Route::post('/search/page', 'Api\PageController@search');

Route::group(['middleware' => ['web']], function () {
    // your routes here
    Route::get('login/google', 'Auth\LoginController@redirectToProvider');
    Route::get('login/google/callback', 'Auth\LoginController@handleProviderCallback');
});
