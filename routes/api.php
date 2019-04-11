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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::get('verify/{token}', 'AuthController@verify');
  
    // Route::group([
    //   'middleware' => 'auth:user'
    // ], function() {
    //     Route::post('logout', 'AuthController@logout');
    //     Route::get('user', 'AuthController@user');
    // });

    Route::group(['middleware' => ['api', 'multiauth:admin,citizen']], function () {
        Route::post('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

Route::group(['middleware' => ['api', 'multiauth:admin']], function () {
    Route::resource('citizens', 'CitizenController')->only(['index', 'destroy']);
    Route::resource('login-histories', 'LoginHistoryController')->only(['index']);
    Route::apiResource('fire-stations', 'FireStationController');
});