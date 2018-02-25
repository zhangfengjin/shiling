<?php

namespace App\Http\Controllers\Home;

use App\Http\Services\MeetPrizeService;
use App\Http\Services\MeetUserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AwardController extends HomeController
{
    //
    public function index(Request $request)
    {
        $meetId = $request->input('meetId');
        $meetService = new MeetPrizeService($request);
        $prizeEnums = $meetService->prizeEnums($meetId);
        /*$meetUserService = new MeetUserService($request);
        $list = $meetUserService->getMeetUserList($meetId);*/
        $pages = [
            "prizes" => $prizeEnums
        ];
        return view("award.award")->with($pages);
    }
}
