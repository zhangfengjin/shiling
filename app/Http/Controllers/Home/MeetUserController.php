<?php

namespace App\Http\Controllers\Home;

use App\Http\Services\MeetUserService;
use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MeetUserController extends HomeController
{
    //

    public function getList(Request $request)
    {
        $meetService = new MeetUserService($request);
        $list = $meetService->getList();
        return DataStandard::printStandardData($list);
    }
}
