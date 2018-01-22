<?php

namespace App\Http\Middleware;

use App\Utils\DataStandard;
use Closure;

class Api
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
            return DataStandard::printStandardData([], config("validator.110"), 110);
        }
        //校验
        $appKey = $request->input('appKey');//appkey
        $appKeys = config("app.app_key");
        if (empty($appKey) || !isset($appKeys[$appKey])) {
            return DataStandard::printStandardData([], config("validator.111"), 111);
        }
        return $next($request);
    }
}
