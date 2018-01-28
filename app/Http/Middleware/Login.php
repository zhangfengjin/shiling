<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Login
{
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * 退出
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     *
     */
    public function logout()
    {
        $this->guard()->logout();

        return redirect('/');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->guard()->guest()) {
            return redirect()->guest('auth/login');
        }
        $userInfo = Auth::user();
        $user = [
            "user" => [
                "uid" => $userInfo->id,
                "userName" => $userInfo->name,
                "avatar" => ""
            ]
        ];
        $request->attributes->add($user);
        return $next($request);
    }
}
