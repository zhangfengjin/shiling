<?php

namespace App\Http\Controllers\Home;

use App\Http\Services\MeetPrizeService;
use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MeetPrizeController extends HomeController
{



    public function getList(Request $request)
    {
        $meetService = new MeetPrizeService($request);
        $list = $meetService->getList();
        return DataStandard::printStandardData($list);
    }
}
