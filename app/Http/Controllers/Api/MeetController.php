<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\MeetService;
use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MeetController extends Controller
{
    /**
     * 会议签到
     * @param Request $request
     * @param $meetId
     * @return array
     */
    public function signin(Request $request, $meetId)
    {
        //
        return DataStandard::getStandardData($meetId);
    }

    public function getList(Request $request)
    {
        $meetService = new MeetService($request);
        $list = $meetService->getList();
        return DataStandard::printStandardData($list);
    }

}
