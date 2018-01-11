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


Route::group(['domain' => 'lingshi.weibo.com', 'middleware' => ['login', 'auth2']],
    function () {
        Route::group(['middleware' => ['login', 'auth2']], function () {
            Route::get('/', function () {
                return view('home');
            });
            Route::group(['namespace' => 'Admin'], function () {
                Route::group(['prefix' => 'report'], function () {
                    Route::group(['prefix' => 'consume'], function () {
                        Route::get("", "ReportController@getConsume");
                        Route::get("dsp", "ReportController@getDspConsume");
                        Route::get("dsp/yesterday", "ReportController@getDspYesterdayConsume");
                    });
                    Route::get("dsp", "ReportController@getDspScatter");
                    Route::get("client", "ReportController@getClientScatter");
                    Route::get("creative", "ReportController@getCreativeScatter");
                });
            });
        });

        Route::group(["namespace" => 'Auth'], function () {
            Route::get("/login", "LoginController@index");
            Route::post("/login", "LoginController@index");
        });
    }
);
