<?php

namespace App\Http\Controllers\Auth;

use App\Http\Services\UserService;
use App\Http\Services\VerifyService;
use App\User;
use App\Http\Controllers\Controller;
use App\Utils\DataStandard;
use App\Utils\RegHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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

    public function index()
    {
        return view("auth.register");
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
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
        'username' => 'required|max:60',
        'password' => 'required|min:6',
        'verify' => 'required|numeric|digits:6',
        'subject'=>'required|numeric'
    ];

    public function register(Request $request)
    {
        $input = $request->all();
        $validate = Validator::make($input, $this->basicValidator);
        if ($validate->fails()) {
            return DataStandard::getStandardData($validate->errors(), "参数输入错误", 10210);
        }
        $code = $request->input('verify');
        $tel = $request->input('phone');
        $email = $request->input('email');
        if (!empty($code) || !empty($tel) || !empty($email)) {
            $account = "";
            if (!empty($tel)) {
                $account = $tel;
            } else {
                $account = $email;
            }
            $verifyService = new VerifyService();
            $msg = $verifyService->codeValidate($code, $account); // 验证手机邮箱验证码
            if (!$msg) { // 返回空字符串表示验证通过
                $userService = new UserService ();
                if (!empty($tel)) {
                    if (RegHelper::validateTel($tel)) {
                        if ($userService->uniqueTel($tel) > 0) {
                            return DataStandard::getStandardData([], "手机已被抢注", 203);
                        }
                    } else {
                        return DataStandard::getStandardData([], "手机格式不正确", 205);
                    }
                }
                if (!empty($email)) {
                    if (RegHelper::validateEmail($email)) {
                        if ($userService->uniqueEmail($email) > 0) {
                            return DataStandard::getStandardData([], "邮箱已被抢注", 204);
                        }
                    } else {
                        return DataStandard::getStandardData([], "邮箱格式不正确", 206);
                    }
                }
                $user = $request->all();
                $user["role_id"] = 2;//普通教师 写死
                $user["account"] = $account;
                $user = $userService->create($user); // 注册
                if ($user) {
                    $this->guard()->login($user); // 登录
                    $token = empty($user['im_token']) ? DataStandard::getToken($user->id) : $user['im_token'];
                    Cache::put($token, $token, 60 * 24 * 365);
                    return DataStandard::getStandardData(["token" => $token]);
                }
            }
            return DataStandard::getStandardData([], $msg, 12002);
        }

        return DataStandard::getStandardData([], "注册失败", 210);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
