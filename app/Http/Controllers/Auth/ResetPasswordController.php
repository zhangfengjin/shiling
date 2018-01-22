<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Services\TelEmailService;
use App\Http\Services\UserService;
use App\Http\Services\VerifyService;
use App\User;
use App\Utils\DataStandard;
use App\Utils\RegHelper;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
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
        //$this->middleware('guest');
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

    public function index()
    {
        return view("auth.reset");
    }

    private $basicValidator = [
        'password' => 'required|min:6',
        'confirm' => 'required|min:6',
        'verify' => 'required|numeric|digits:6'
    ];

    /**
     * 修改密码
     * @param Request $request
     * @return array
     */
    public function reset(Request $request)
    {
        $input = $request->all();
        $validate = Validator::make($input, $this->basicValidator);
        if ($validate->fails()) {
            return DataStandard::getStandardData($validate->errors(), "参数输入错误", 10210);
        }
        $password = $request->input('password');
        $confirm = $request->input('confirm');
        if ($password == $confirm) {
            $tel = $request->input('phone');
            $email = $request->input('email');
            $userService = new UserService();
            $account = "";
            if (!empty($tel)) {
                if (RegHelper::validateTel($tel)) {
                    if ($userService->uniqueTel($tel) > 0) {
                        $account = $tel;
                    }
                } else {
                    return DataStandard::getStandardData([], "手机格式不正确", 205);
                }
            } else if (!empty($email)) {
                if (RegHelper::validateEmail($email)) {
                    if ($userService->uniqueEmail($email) > 0) {
                        $account = $email;
                    }
                } else {
                    return DataStandard::getStandardData([], "邮箱已被抢注", 204);
                }
            } else {
                return DataStandard::getStandardData([], "输入参数不正确", 12000);
            }
            if (empty($account)) {
                return DataStandard::getStandardData([], "账号未注册", 12001);
            }
            $code = $request->input('verify');
            $verifyService = new VerifyService();
            $msg = $verifyService->codeValidate($code, $account); // 验证手机邮箱验证码
            if (!$msg) { // 返回空字符串表示验证通过
                $userService = new UserService ();
                $user = new User ();
                if (RegHelper::validateEmail($account)) {
                    $user = $userService->resetPWD("email", $account, $password);
                } else if (RegHelper::validateTel($account)) {
                    $user = $userService->resetPWD("phone", $account, $password);
                }
                $this->guard()->login($user); // 登录
                return DataStandard::getStandardData();
            }
            return DataStandard::getStandardData([], $msg, 12002);
        }
        return DataStandard::getStandardData([], "两次输入的密码不一致", 12003);
    }

}
