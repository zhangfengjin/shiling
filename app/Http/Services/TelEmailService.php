<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/1/11
 * Time: 17:25
 */

namespace App\Http\Services;


use App\Utils\EmailHelper;
use App\Utils\HttpHelper;
use Illuminate\Support\Facades\Log;

class TelEmailService
{
    private $minute = 10;

    /**
     * 发送邮件验证码
     *
     * @param unknown $code
     * @param unknown $receiver
     */
    public function sendEmailVerify($code, $receiver)
    {
        $content = "验证码：{$code}，此验证码{$this->minute}分钟内有效，请及时验证。如非本人操作，请忽略此短信！【领师APP】";
        EmailHelper::sendEmail($content, $receiver);
    }

    /**
     * 发送手机验证码
     *
     * @param unknown $code
     * @param unknown $receiver
     */
    public function sendTelVerify($code, $receiver)
    {
        $url = "https://api.netease.im/sms/sendcode.action";
        $post_data = [
            'mobile' => $receiver,
            'authCode' => $code
        ];
        $headers = $this->getSMSHeader();
        // 发送请求
        $ret = HttpHelper::http_post_curlcontents($url, $headers, $post_data);
        return $ret;
    }

    public function sendNotifySMS($phone, $content)
    {
        //todo 调试 修改templateid
        $url = "https://api.netease.im/sms/sendtemplate.action";
        $post_data = [
            'templateid' => '1',
            'mobile' => [$phone],
            'params' => [$content]
        ];
        $headers = $this->getSMSHeader();
        // 发送请求
        $ret = HttpHelper::http_post_curlcontents($url, $headers, $post_data);
        return $ret;
    }

    /**
     * 必须的消息头
     * @return array
     */
    private function getSMSHeader()
    {
        $appKey = config('app.wy.appkey');
        $appSecret = config('app.wy.secret');
        $curTime = time();
        $ymd = date('Ymd', time());
        $nonce = 'LS' . $ymd . substr(microtime(), 2, 8) . rand(10, 100);
        // 构建checksum
        $checksum = strtolower(sha1($appSecret . $nonce . $curTime));
        // 构建header
        return [
            "AppKey:$appKey",
            "CurTime:$curTime",
            "CheckSum:$checksum",
            "Nonce:$nonce",
            'Content-Type: application/x-www-form-urlencoded;charset=utf-8',
        ];
    }

}