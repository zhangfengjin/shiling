<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Services\VerifyService;
use App\Utils\DataStandard;
use App\Utils\RegHelper;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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
        return "phone";
    }

    /**
     * @return mixed
     */
    protected function guard()
    {
        return Auth::guard();
    }

    private $basicValidator = [
        'password' => 'required'
    ];

    /**
     * 登录
     * @param Request $request
     * @return array
     */
    public function login(Request $request)
    {
        $input = $request->all();
        $validate = Validator::make($input, $this->basicValidator);
        if ($validate->fails()) {
            return DataStandard::getStandardData($validate->errors(), config("validator.100"), 100);
        }
        $account = $request->input('phone');
        $login = false;
        $credentials = [];
        if (!empty($account)) {
            if (RegHelper::validateTel($account)) {
                $credentials["phone"] = $account;
                $login = true;
            }
        } else {
            $account = $request->input('email');
            if (RegHelper::validateEmail($account)) {
                $credentials["email"] = $account;
                $login = true;
            }
        }
        if ($login) {
            $credentials ["password"] = $request->input('password');
            $bsLimit = [1, 4, 5];//
            $bs = false;
            if (!empty($request->input("bs"))) {
                $code = $request->input('verify');
                $verifyService = new VerifyService();
                $msg = $verifyService->codeValidate($code, $account); // 验证手机邮箱验证码
                if ($msg) {
                    return DataStandard::getStandardData([], $msg, 123);
                }
                $credentials["role_id"] = $bsLimit;//后台登录 只允许管理员
                $bs = true;
            }
            $credentials["flag"] = 0;
            if ($this->guard()->attempt($credentials, true)) {
                $user = Auth::user();
                $token = empty($user->im_token) ? DataStandard::getToken($user->id) : $user->im_token;
                Cache::put($token, $token, 60 * 24 * 365);
                return DataStandard::getStandardData(["token" => $token]);
            }
            return DataStandard::getStandardData([], config("validator.124"), 124);
        }
        return DataStandard::getStandardData([], config("validator.125"), 125);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $bs = false;
        if (!empty($request->input("bs"))) {
            $token = empty($user->im_token) ? DataStandard::getToken($user->id) : $user->im_token;
            $bs = true;
        } else {
            $token = $request->input('token');
        }
        Cache::forget($token);
        $this->guard()->logout();
        if ($bs) {
            $request->session()->invalidate();
            return redirect('/');
        }
        return DataStandard::getStandardData();
    }


}
