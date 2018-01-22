<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Utils\DataStandard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
    }

    public function index()
    {
        return view("auth.login");
    }

    /**
     * @return string
     */
    public function username()
    {
        return "tel";
    }

    /**
     * @return mixed
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * 登录
     * @param Request $request
     * @return array
     */
    public function login(Request $request)
    {
        $account = $request->input('phone');
        $credentials = array(
            "tel" => $account
        );
        $credentials ["password"] = $request->input('password');
        if ($this->guard()->attempt($credentials, true)) {
            $user = Auth::user();
            $token = empty($user->im_token) ? DataStandard::getToken($user->id) : $user->im_token;
            Cache::put($token, $token, 60 * 24 * 365);
            return DataStandard::getStandardData(["token" => $token]);
        }
        return DataStandard::getStandardData('', "登录失败", 201);
    }


}
