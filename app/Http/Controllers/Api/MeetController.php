<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\MeetService;
use App\Http\Services\MeetUserService;
use App\Http\Services\UserService;
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

    /**
     * 报名
     * @param Request $request
     * @param $meetId
     * @return array
     */
    public function enroll(Request $request)
    {
        $input = $request->all();
        $meetService = new MeetService($request);
        $meet = $meetService->getMeet($input);
        if ($meet) {
            $meetUserService = new MeetUserService($request);
            $meetUser = $meetUserService->getMeetUser($input);
            if ($meetUser) {
                return DataStandard::getStandardData([], config('validator.703'), 703);
            }
            $userService = new UserService();
            if ($userService->uniqueUid($input['userId'])) {
                $ret = $meetService->enroll($input);
                return DataStandard::getStandardData();
            }
            return DataStandard::getStandardData([], config('validator.126'), 126);
        }
        return DataStandard::getStandardData([], config('validator.702'), 702);
    }

    public function getList(Request $request)
    {
        $meetService = new MeetService($request);
        $list = $meetService->getList();
        return DataStandard::printStandardData($list);
    }

}
