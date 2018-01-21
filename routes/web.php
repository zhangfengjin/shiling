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
        Route::group(["namespace" => 'Auth', 'prefix' => 'auth'], function () {
            Route::get("/register", "RegisterController@index");
            Route::get("/login", "LoginController@index");
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
            Route::resource("user", "UserController");

            Route::group(['prefix' => 'pay'], function () {
                Route::post("unifiedorder", "PayController@unifiedorder");
            });
            Route::post("signin/code", "MeetController@signin");//二维码签到

            Route::get("school/list", "SchoolController@getList");//学校列表
            Route::get("role/list", "RoleController@getList");//角色列表

            Route::post("meet/upload", "MeetController@upload");
            Route::get("award","AwardController@index");

            Route::post("goods/upload", "GoodsController@upload");
            Route::resource("meet", "MeetController");
            Route::resource("goods", "GoodsController");
        });
    }
);
