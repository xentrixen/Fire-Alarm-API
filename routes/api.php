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

Broadcast::routes(['middleware' => 'auth:api']);

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::get('verify/{token}', 'AuthController@verify');

    Route::group(['middleware' => ['api', 'multiauth:admin,citizen,fire-personnel']], function () {
        Route::post('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

Route::group(['middleware' => ['api', 'multiauth:admin']], function () {
    Route::resource('citizens', 'CitizenController')->only(['index', 'destroy']);
    Route::resource('login-histories', 'LoginHistoryController')->only(['index']);
    Route::resource('fire-report-histories', 'FireReportHistoryController')->only(['index', 'show', 'destroy']);
    Route::resource('fire-stations', 'FireStationController')->except(['index']);
    Route::resource('fire-hydrants', 'FireHydrantController')->except(['index']);
});

Route::group(['middleware' => ['api', 'multiauth:admin,citizen,fire-personnel']], function () {
    Route::resource('fire-stations', 'FireStationController')->only(['index']);
});

Route::group(['middleware' => ['api', 'multiauth:citizen']], function () {
    Route::resource('fire-reports', 'FireReportController')->only(['store']);
});

Route::group(['middleware' => ['api', 'multiauth:admin,fire-personnel']], function () {
    Route::resource('fire-reports', 'FireReportController')->only(['index', 'show', 'update', 'destroy']);
    Route::resource('fire-hydrants', 'FireHydrantController')->only(['index']);
});