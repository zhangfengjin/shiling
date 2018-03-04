<?php

namespace App\Http\Middleware;

use App\Http\Services\SysLogService;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class SysLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = $request->get("user");
            $slogService = new SysLogService();
            $slogService->save($request, [], $user, 0); // 成功日志
        } catch (\Exception $e) {
            Log::info("\r\nSysLog:" . json_encode($request->all()) . "\r\n");
        }
        return $next($request);
    }

    public function terminate($request, $response)
    {
        try {
            $user = $request->get("user");
            $slogService = new SysLogService();
            $slogService->save($request, $response, $user, 1); // 成功日志
        } catch (\Exception $e) {
            Log::info("\r\nSysLog:" . json_encode($response->getContent()) . "\r\n");
        }
    }
}
