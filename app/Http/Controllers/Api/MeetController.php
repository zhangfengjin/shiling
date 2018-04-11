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

    public function show(Request $request, $meetId)
    {
        $meetService = new MeetService($request);
        $meet = $meetService->show($meetId);
        return DataStandard::getStandardData($meet);
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
            if (isset($input['users'])) {
                if (!$userService->uniqueUid($input['userId'])) {//检查代报名用户
                    return DataStandard::getStandardData([], config('validator.126'), 126);
                }
                $users = $input['users'];
                foreach ($users as $user) {//检查需要报名的用户
                    $userId = $user['userId'];
                    if (!$userService->uniqueUid($userId)) {
                        return DataStandard::getStandardData([], config('validator.126'), 126);
                    }
                    $args = [
                        "meetId" => $input["meetId"],
                        "userId" => $userId
                    ];
                    $meetUser = $meetUserService->getMeetUser($args);
                    if ($meetUser) {
                        return DataStandard::getStandardData([], config('validator.703'), 703);
                    }
                }
                $ret = $meetService->enroll($input);
                return DataStandard::getStandardData($ret);
            }
            return DataStandard::getStandardData(["请求中不存在users参数"], config('validator.127'), 127);
        }
        return DataStandard::getStandardData([], config('validator.702'), 702);
    }

    public function getList(Request $request)
    {
        if (!$request->input('status')) {
            $request->offsetSet("status", 0);
        }
        $meetService = new MeetService($request);
        $list = $meetService->getList();
        return DataStandard::printStandardData($list);
    }

}
