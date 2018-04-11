<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Services\VerifyService;
use App\Utils\DataStandard;
use App\Utils\HttpHelper;
use App\Utils\RegHelper;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
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

    public function index(Request $request)
    {
        $pages = [
            "meetId" => $request->input('meet_id')
        ];
        return view("auth.login")->with($pages);
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
            $meetId = $request->input("meet_id");//带有meet_id字段的表示需要直接报名
            if (!empty($request->input("bs"))) {
                $code = $request->input('verify');
                $verifyService = new VerifyService();
                $msg = $verifyService->codeValidate($code, $account); // 验证手机邮箱验证码
                if ($msg) {
                    return DataStandard::getStandardData([], $msg, 123);
                }
                if (!$meetId) {
                    $credentials["role_id"] = $bsLimit;//后台登录 只允许管理员
                }
                $bs = true;
            }
            $credentials["flag"] = 0;
            if ($this->guard()->attempt($credentials, true)) {
                $user = Auth::user();
                $token = empty($user->im_token) ? DataStandard::getToken($user->id) : $user->im_token;
                Cache::put($token, $token, 60 * 24 * 365);
                if ($meetId) {
                    //todo
                    //报名
                    $url = config("app.enroll.url");
                    $headers = [
                        'Content-Type:application/json;charset=utf-8',
                        'appKey:' . config("app.sys_app_key"),
                        'token:' . $token
                    ];
                    $post_data = [
                        "meetId" => $meetId,
                        "userId" => $user->id,
                        "users" => [
                            ["userId" => $user->id]
                        ]
                    ];
                    $url = $url . "?" . http_build_query($post_data);
                    // 发送请求
                    $ret = HttpHelper::http_post_curlcontents($url, $headers, []);
                    $ret = json_decode($ret, true);
                    if (isset($ret['code']) && $ret['code'] === 0) {
                        //报名成功 退出登录状态
                        $this->guard()->logout();
                        return DataStandard::getStandardData([], config("validator.651"), 651);
                    }
                    return DataStandard::getStandardData([], isset($ret["message"]) ? $ret["message"] : config("validator.652"), 652);
                }
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
            $bs = true;//pc端
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
