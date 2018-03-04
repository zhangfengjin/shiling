<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/3/4
 * Time: 15:29
 */

namespace App\Http\Services;


use App\Models\SysLog;
use Illuminate\Support\Facades\Log;

class SysLogService extends CommonService
{
    /**
     * 存储日志
     *
     * @param unknown $request
     * @param unknown $user
     * @throws Exception
     */
    public function save($request, $response = [], $user, $opt)
    {
        try {
            $yspLog = new SysLog();
            $yspLog->content_type = $request->header('content-type');
            $yspLog->app_key = $request->header("appKey");
            $yspLog->userid = $user ? $user['uid'] : 0;
            $yspLog->operoot = $request->root();
            $yspLog->operroute = $request->path();
            $yspLog->operajax = $request->ajax();
            $yspLog->opermethod = $request->method();
            $yspLog->actionargs = json_encode($request->all());
            if (!empty($response)) {
                $yspLog->result = $response->getContent();
            }
            $yspLog->areaip = $request->ip();
            $server = $request->server();
            $yspLog->useragent = $server ["HTTP_USER_AGENT"];
            $yspLog->operesult = $opt;
            $yspLog->save();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}