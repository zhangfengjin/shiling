<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class Authenticate
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
        $user = $request->get("user");
        $auth = [
            "resources" => [],
            "menus" => Config::get("menu"),
            "user" => $user,
            "power" => [],
            "bodyNav" => "nav-md"//"nav-sm"
        ];
        view()->share("share", $auth);

        return $next($request);
    }
}
