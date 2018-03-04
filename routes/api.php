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
Route::post("im/sync", "IMController@sync");
Route::group(['domain' => '', 'middleware' => ['logger']], function () {
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

                Route::post("meet/signup", "MeetController@enroll");//报名
                Route::get("meet/list", "MeetController@getList");//会议列表
                Route::get("meet/{meetId}", "MeetController@show");//会议详情

                //Route::post("signin/code/{meetId}", "MeetController@signin");//二维码签到--会议二维码
                Route::get("qrcode", "MeetUserController@getQrcode");//获取会议二维码
                Route::post("meet/cancel", "MeetUserController@cancel");//取消报名
                Route::get("meetuser/list", "MeetUserController@getList");
                Route::put("meetuser/{muId}", "MeetUserController@update");

                Route::get("goods/type", "GoodsController@getGoodsType");
                Route::get("goods/list", "GoodsController@getList");//商品列表
                Route::get("goods/{goodsId}", "GoodsController@show");//商品详情

                Route::get("orders/list", "OrderController@getList");//商品列表
                Route::get("orders/{goodsId}", "OrderController@show");//商品详情
                Route::post("orders", "OrderController@store");//下单
                Route::put("orders/{ordersId}", "OrderController@update");//更新订单
                Route::get("bill/use", "OrderController@getBillUse");

                Route::get("news/list", "NewsController@getList");//新闻列表

                Route::group(['prefix' => 'pay'], function () {
                    Route::post("unifiedorder", "PayController@unifiedorder");
                });

                Route::get("school/list", "SchoolController@getList");//学校列表
                Route::get("role/list", "RoleController@getList");//角色列表
                Route::get("usertitle/list", "UserTitleController@getList");//职级列表


            });

        });
    });
    Route::post("signin/code", "Api\MeetUserController@userSignin");//二维码签到--会议用户个人二维码

});
