<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/1/15
 * Time: 11:23
 */

namespace App\Http\Services;


use Illuminate\Support\Facades\Cache;

class VerifyService
{
    /**
     * 获取验证Code
     * @return string
     */
    public function getVerifyCode()
    {
        $code = "";
        for ($i = 0; $i < 6; $i++) {
            $code .= rand(1, 9);
        }
        return $code;
    }

    /**
     * @param $account
     */
    public function saveCode($code, $account)
    {
        $checkcode = [
            "code" => $code,
            "receiver" => $account,
            "time" => time()
        ];
        Cache::put($account, $checkcode);
    }

    /**
     * 验证码校验
     * @param $code
     * @param $account
     * @param int $minute
     * @return string
     */
    public function codeValidate($code, $account, $minute = 10)
    {
        $checkcode = Cache::get($account);
        $time = time();
        $msg = "";
        if (!empty ($checkcode)) { // 验证码
            if ($account == $checkcode ["receiver"]) {
                if (($time - $checkcode ["time"]) < ($minute * 60)) { // 有效验证时间为10分钟
                    if ($checkcode ["code"] == $code) {
                        Cache::forget($account);
                        return $msg;
                    }
                    $msg = '验证码不正确';
                } else {
                    Cache::forget($account);
                    $msg = "验证码已过期，有效期为{$minute}分钟，请重新获取验证码";
                }
            } else {
                $msg = "注册帐号已更改,请重新获取验证码";
            }
        } else
            $msg = "验证码已过期，有效期为{$minute}分钟，请重新获取验证码";
        return $msg;
    }
}