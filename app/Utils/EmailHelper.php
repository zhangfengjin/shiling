<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/1/11
 * Time: 17:26
 */

namespace App\Utils;


use Illuminate\Support\Facades\Mail;

class EmailHelper
{
    public static function sendEmail($content, $receiver)
    {
        Mail::raw($content, function ($message) use ($receiver) {
            /*$username = config('app.mail_username');
            $message->from($username, '领师APP');*/
            $message->to($receiver)->subject("领师APP用户验证码");
        });
    }
}