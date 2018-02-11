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

    public function getQrcode(Request $request)
    {
        $input = $request->all();
        $meetUserService = new MeetUserService($request);
        $meetUser = $meetUserService->getSignInMeetUser($input);
        if ($meetUser) {
            $codeImg = config('app.qrcode.path') . 'meet_user/' . $meetUser->id . ".png";
            HttpHelper::download($codeImg);
        }
        return DataStandard::getStandardData([], config('validator.715'), 715);
    }

    public function userSignin(Request $request)
    {
        $input = $request->all();
        $meetUserService = new MeetUserService($request);
        $meetUser = $meetUserService->getSignInMeetUser($input);
        if ($meetUser) {
            switch ($meetUser->status) {
                case 1:
                    $meetUserService->userSignIn($input);
                    return DataStandard::getStandardData(["签到成功"]);
                    break;
                case 2:
                    return DataStandard::getStandardData([], config('validator.711'), 711);
                    break;
                case 3:
                    return DataStandard::getStandardData([], config('validator.712'), 712);
                    break;
                case 4:
                    return DataStandard::getStandardData([], config('validator.713'), 713);
                    break;
            }
            return DataStandard::getStandardData([], config('validator.714'), 714);
        }
        return DataStandard::getStandardData([], config('validator.710'), 710);
    }

    public function cancel(Request $request)
    {
        $input = $request->all();
        $meetUserService = new MeetUserService($request);
        $meetUserService->cancel($input);
        return DataStandard::getStandardData();
    }
}
