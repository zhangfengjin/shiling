<?php

namespace App\Http\Controllers\Verify;

use App\Http\Services\TelEmailService;
use App\Http\Services\UserService;
use App\Http\Services\VerifyService;
use App\Utils\DataStandard;
use App\Utils\RegHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VerifyController extends Controller
{
    /**
     * 发送验证码--包括手机验证码和邮箱验证码
     * @return array
     */
    public function code(Request $request)
    {
        $tel = $request->input('tel');
        $email = $request->input('email');
        $userService = new UserService ();
        $telEmail = new TelEmailService();
        $verifyService = new VerifyService();
        $code = $verifyService->getVerifyCode();
        if (!empty($tel)) {
            if (RegHelper::validateTel($tel)) {
                if ($userService->uniqueTel($tel) > 0) {
                    //todo
                    // 发送手机验证码
                    $telEmail->sendTelVerify($code, $tel);
                }
            } else {
                return DataStandard::getStandardData([], "手机格式不正确", 205);
            }
        } else if (!empty($email)) {
            if (RegHelper::validateEmail($email)) {
                if ($userService->uniqueEmail($email) > 0) {
                    //todo
                    //发送邮箱验证码
                    $telEmail->sendTelVerify($code, $email);
                }
            } else {
                return DataStandard::getStandardData([], "邮箱已被抢注", 204);
            }
        } else {
            return DataStandard::getStandardData([], "输入参数不正确", 12000);
        }


        return DataStandard::printStandardData();
    }

}
