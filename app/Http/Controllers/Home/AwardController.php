<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AwardController extends HomeController
{
    //
    public function index(Request $request){
        return view("award.award");
    }
}
