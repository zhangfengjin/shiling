<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/1/15
 * Time: 11:23
 */

namespace App\Http\Services;


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
}