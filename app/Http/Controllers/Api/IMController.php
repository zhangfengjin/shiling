<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IMController extends ApiController
{
    //
    public function sync(Request $request)
    {
        $contentType = $request->header('content-type');
        $appKey = $request->header("AppKey");
        $curTime = $request->header("CurTime");
        $md5 = $request->header("MD5");
        $checkSum = $request->header("CheckSum");
        $input = $request->all();
        try {
            $chatService = new ChatService();
            $chatService->save($contentType, $appKey, $curTime, $md5, $checkSum, $input);
        } catch (\Exception $ex) {
            Log::info("\r\nIM SYNC:" . $contentType . "-" . $appKey . "-" . $curTime . "-" . $md5 . "-" . $checkSum . "-" . json_encode($input) . "\r\n");
        }
    }
}
