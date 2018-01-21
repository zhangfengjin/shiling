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
            Route::get("user/list", "UserController@getList");//用户列表
            Route::get("user/{userId}", "UserController@show");//获取用户信息
            Route::put("user/{userId}", "UserController@update");//更新用户信息
            Route::group(['prefix' => 'pay'], function () {
                Route::post("unifiedorder", "PayController@unifiedorder");
            });
            Route::post("signin/code", "MeetController@signin");//二维码签到

            Route::get("school/list", "SchoolController@getList");//学校列表
            Route::get("role/list", "RoleController@getList");//角色列表
        });
    });


});
