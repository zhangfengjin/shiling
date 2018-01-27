<?php

namespace App\Http\Controllers\Verify;

use App\Http\Services\TelEmailService;
use App\Http\Services\UserService;
use App\Http\Services\VerifyService;
use App\Utils\DataStandard;
use App\Utils\RegHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class VerifyController extends Controller
{
    /**
     * 发送验证码--包括手机验证码和邮箱验证码
     * @return array
     */
    public function code(Request $request)
    {
        $tel = $request->input('phone');
        $email = $request->input('email');
        $telEmail = new TelEmailService();
        $verifyService = new VerifyService();
        $code = $verifyService->getVerifyCode();
        $account = "";
        if ($request->input('stop')) {
            $tel = config('app.stop_tel');
        }
        if (!empty($tel)) {
            if (RegHelper::validateTel($tel)) {
                $telEmail->sendTelVerify($code, $tel);
                $account = $tel;
            } else {
                return DataStandard::getStandardData([], config("validator.114"), 114);
            }
        } else if (!empty($email)) {
            if (RegHelper::validateEmail($email)) {
                $telEmail->sendEmailVerify($code, $email);
                $account = $email;
            } else {
                return DataStandard::getStandardData([], config("validator.115"), 115);
            }
        } else {
            return DataStandard::getStandardData([], config("validator.100"), 100);
        }
        $verifyService->saveCode($code, $account);//缓存code
        return DataStandard::printStandardData();
    }


}
