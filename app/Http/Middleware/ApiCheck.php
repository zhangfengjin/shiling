<?php

namespace App\Http\Middleware;

use App\Http\Services\UserService;
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
        $token = empty($request->header('token')) ? $request->input('token')
            : $request->header('token');//登录token 用户唯一标识
        if (empty($token)) {
            return DataStandard::printStandardData([], config("validator.112"), 112);
        }
        $userService = new UserService();
        $userInfo = $userService->getUserByToken($token);
        $user = [
            "user" => [
                "uid" => $userInfo->id,
                "userName" => $userInfo->name,
                "course_id" => $userInfo->course_id,
                "avatar" => ""
            ]
        ];
        $request->attributes->add($user);
        //todo
        if (Cache::has($token)) {//Cache::has($token)
            return $next($request);
        } else {
            return DataStandard::printStandardData([], config("validator.113"), 113);
        }
    }
}
