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
Route::group(['domain' => '',], function () {
    Route::group(['middleware' => ['lsapi']], function () {
        Route::any('/', function () {
            return response('', 403);
        });
        Route::get("verify/code", 'Verify\VerifyController@code');

        Route::group(["namespace" => 'Auth', 'prefix' => 'auth'], function () {
            Route::post("/register", "RegisterController@register");
            Route::post("/login", "LoginController@login");
            Route::post("/reset", "ResetPasswordController@reset");
        });

        Route::group(['namespace' => 'Api', 'middleware' => ['apiauth']], function () {
            Route::get("user/list", "UserController@getList");
            Route::put("user/{userId}", "UserController@update");
            Route::get("user/{userId}", "UserController@show");
            Route::group(['prefix' => 'pay'], function () {
                Route::post("unifiedorder", "PayController@unifiedorder");
            });
            Route::post("signin/code", "MeetController@signin");

            Route::get("school/list", "SchoolController@getList");
            Route::get("role/list", "RoleController@getList");
        });
    });


});
