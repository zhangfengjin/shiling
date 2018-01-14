<?php

namespace App\Http\Middleware;

use App\Utils\DataStandard;
use Closure;
use Illuminate\Support\Facades\Cache;

class ApiCheck
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
        $sign = $request->input('sign');//签名
        $token = $request->input('token');//登录token 用户唯一标识
        if (empty($token)) {
            return DataStandard::printStandardData([],"验证失败", 111);
        }
        if (Cache::has($token)) {
            return $next($request);
        } else {
            return DataStandard::printStandardData([],"登录超时", 112);
        }
    }
}
