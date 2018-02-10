<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\MeetUserService;
use App\Utils\DataStandard;
use App\Utils\HttpHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MeetUserController extends Controller
{
    //

    public function getQrcode(Request $request, $enroll)
    {
        $meetUserService = new MeetUserService($request);
        $meetUser = $meetUserService->getSignInMeetUser($enroll);
        if ($meetUser) {
            $codeImg = config('app.qrcode.path') . 'meet_user/' . $meetUser->id . ".png";
            HttpHelper::download($codeImg);
        }
        return DataStandard::getStandardData([], config('validator.710'), 710);
    }

    public function userSignin(Request $request, $enroll)
    {
        $meetUserService = new MeetUserService($request);
        $meetUser = $meetUserService->getSignInMeetUser($enroll);
        if ($meetUser) {
            switch ($meetUser->status) {
                case 1:
                    return DataStandard::getStandardData(["签到成功"]);
                    break;
                case 2:
                    return DataStandard::getStandardData([], config('validator.710'), 711);
                    break;
                case 3:
                    return DataStandard::getStandardData([], config('validator.711'), 712);
                    break;
                case 4:
                    return DataStandard::getStandardData([], config('validator.712'), 713);
                    break;
            }
            return DataStandard::getStandardData([], config('validator.714'), 714);
        }
        return DataStandard::getStandardData([], config('validator.710'), 710);
    }
}
