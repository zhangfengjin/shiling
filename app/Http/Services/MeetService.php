<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/1/17
 * Time: 11:09
 */

namespace App\Http\Services;


use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MeetService
{
    public function store()
    {
        if ($this->qrcode()) {

        }
    }

    /**
     * 生成签到二维码
     */
    private function qrcode()
    {
        $codeImg = config('app.qrcode.path');
        if (!file_exists($codeImg)) {
            $sign = config('app.qrcode.sign');
            QrCode::format('png')->size(300)->generate($sign, $codeImg);
        }
        return true;
    }

    public function getList()
    {
        
    }
}