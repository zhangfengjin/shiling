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
            Route::get("/logout", "LoginController@logout");
            Route::post("/reset", "ResetPasswordController@reset");
        });

        Route::group(['namespace' => 'Api'], function () {
            Route::get("subject/list", "CourseController@getList");//科目列表
            Route::get("grade/list", "GradeController@getList");//科目列表
            Route::group(['middleware' => ['apiauth']], function () {
                Route::get("user/list", "UserController@getList");//用户列表
                Route::get("user/{userId}", "UserController@show");//获取用户信息
                Route::put("user/{userId}", "UserController@update");//更新用户信息


                //Route::post("signin/code/{meetId}", "MeetController@signin");//二维码签到--会议二维码
                Route::post("user/signin/code/{enroll}", "MeetUserController@userSignin");//二维码签到--会议用户个人二维码

                Route::post("meet/signup", "MeetController@enroll");
                Route::get("meet/list", "MeetController@getList");//会议列表

                Route::group(['prefix' => 'pay'], function () {
                    Route::post("unifiedorder", "PayController@unifiedorder");
                });

                Route::get("school/list", "SchoolController@getList");//学校列表
                Route::get("role/list", "RoleController@getList");//角色列表
                Route::get("usertitle/list", "UserTitleController@getList");//职级列表

            });

        });
    });


});
