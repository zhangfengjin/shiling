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


Route::group(['domain' => 'lingshi.weibo.com'],
    function () {
        Route::group(["namespace" => 'Auth', 'prefix' => 'auth'], function () {
            Route::get("/register", "RegisterController@index");
            Route::get("/login", "LoginController@index");
            Route::get("/reset", "ResetPasswordController@index");

            Route::post("/register", "RegisterController@register");
            Route::post("/login", "LoginController@login");
            Route::post("/reset", "ResetPasswordController@reset");
        });

        Route::group(['middleware' => ['login', 'auth2']], function () {
            Route::get('/', function () {
                return view('home');
            });
        });
    }
);
