<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/3/4
 * Time: 16:32
 */

namespace App\Http\Services;


use App\Models\ChatLog;

class ChatService extends CommonService
{
    public function save($contentType, $appKey, $curTime, $md5, $checkSum, $input)
    {
        $chat = new ChatLog();
        $chat->content_type = $contentType;
        $chat->app_key = $appKey;
        $chat->curtime = $curTime;
        $chat->md5 = $md5;
        $chat->checkSum = $checkSum;
        $chat->info = json_encode($input);
        $chat->save();
    }
}