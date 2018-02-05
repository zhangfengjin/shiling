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
        $token = $request->input('token');//登录token 用户唯一标识
        if (empty($token)) {
            return DataStandard::printStandardData([], config("validator.112"), 112);
        }
        //todo
        if (Cache::has($token)) {//Cache::has($token)
            return $next($request);
        } else {
            return DataStandard::printStandardData([], config("validator.113"), 113);
        }
    }
}
