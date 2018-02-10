<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\MeetUserService;
use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MeetUserController extends Controller
{
    //

    public function userSignin(Request $request, $enroll)
    {
        $meetUserService = new MeetUserService($request);
        $meetUser = $meetUserService->userSignIn($enroll);
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
        return DataStandard::getStandardData([], config('validator.710'), 710);
    }
}
