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

//linkteach.cn
Route::group(['domain' => ''],
    function () {
        Route::get("verify/code", 'Verify\VerifyController@code');
        Route::group(["namespace" => 'Auth', 'prefix' => 'auth'], function () {
            Route::get("/register", "RegisterController@index");
            Route::get("/login", "LoginController@index");
            Route::get("/logout", "LoginController@logout");
            Route::get("/reset", "ResetPasswordController@index");

            Route::post("/register", "RegisterController@register");
            Route::post("/login", "LoginController@login");
            Route::post("/reset", "ResetPasswordController@reset");

        });

        Route::group(['namespace' => 'Home', 'middleware' => ['login', 'auth2']], function () {
            Route::get('/', function () {
                return view('home');
            });
            Route::get("user/list", "UserController@getList");//用户列表
            Route::get("user/export", "UserController@export");//用户列表
            Route::post("user/import", "UserController@import");//用户列表
            Route::put("user/egis/{userId}", "UserController@egis");//用户通过
            Route::delete("user/stop/{userId}", "UserController@stop");//用户停用
            Route::resource("user", "UserController");

            Route::get("school", "SchoolController@index");
            Route::get("school/list", "SchoolController@getList");
            Route::post("school/import", "SchoolController@import");
            Route::get("school/{schoolId}", "SchoolController@show");
            Route::put("school/{schoolId}", "SchoolController@update");
            Route::post("school", "SchoolController@store");

            Route::get("role", "RoleController@index");
            Route::get("role/list", "RoleController@getList");//角色列表

            Route::post("meet/upload", "MeetController@upload");
            Route::get("meet/list", "MeetController@getList");
            Route::DELETE("meet/cancel/{meetId}", "MeetController@cancel");
            Route::PUT("meet/notify/{meetId}", "MeetController@notify");

            Route::get("meet/user/list", "MeetUserController@getList");
            Route::resource("meet", "MeetController");
            Route::get("award", "AwardController@index");//摇奖


            Route::post("goods/upload", "GoodsController@upload");
            Route::resource("goods", "GoodsController");

            Route::group(['prefix' => 'pay'], function () {
                Route::post("unifiedorder", "PayController@unifiedorder");
            });
            Route::post("signin/code", "MeetController@signin");//二维码签到
        });
    }
);
