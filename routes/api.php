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
Route::group(['domain' => 'lingshi.weibo.com'], function () {
    Route::any('/', function () {
        return response('', 403);
    });
    Route::resource("verify/code", 'Verify\VerifyController');

    Route::group(['namespace' => 'Api', 'middleware' => ['apicheck']], function () {
        Route::resource("user", "UserController");
    });

});
