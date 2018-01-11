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
        $contentType = $request->header('content-type');
        $contentType = strtolower($contentType);
        if (strpos($contentType, 'application/json') === false) {
            return $this->output("验证失败", 110);
        }
        //校验token或者user_id
        $token = $request->input('token');
        if (empty($token)) {
            return $this->output("验证失败", 111);
        }
        return $next($request);
    }

    public function output($retMsg, $errCode)
    {
        $output = [
            'code' => $errCode,
            'data' => [],
            'message' => $retMsg
        ];
        return response()->json($output);
    }
}
