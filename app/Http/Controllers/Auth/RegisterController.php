<?php

namespace App\Http\Controllers\Auth;

use App\Http\Services\DictService;
use App\Http\Services\UserService;
use App\Http\Services\VerifyService;
use App\User;
use App\Http\Controllers\Controller;
use App\Utils\DataStandard;
use App\Utils\HttpHelper;
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
        $dictService = new DictService();
        $courses = $dictService->getDictByType("course");
        $pages = [
            "courses" => $courses
        ];
        return view("auth.register")->with($pages);
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
        'subject' => 'required'
    ];

    public function register(Request $request)
    {
        $input = $request->all();
        $validate = Validator::make($input, $this->basicValidator);
        if ($validate->fails()) {
            return DataStandard::getStandardData($validate->errors(), config("validator.100"), 100);
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
                            return DataStandard::getStandardData([], config("validator.117"), 117);
                        }
                    } else {
                        return DataStandard::getStandardData([], config("validator.114"), 114);
                    }
                }
                if (!empty($email)) {
                    if (RegHelper::validateEmail($email)) {
                        if ($userService->uniqueEmail($email) > 0) {
                            return DataStandard::getStandardData([], config("validator.116"), 116);
                        }
                    } else {
                        return DataStandard::getStandardData([], config("validator.115"), 115);
                    }
                }
                $user = $request->all();
                $user["status"] = 0;
                if (isset($user["role_id"]) && ($user["role_id"] == 2 || $user["role_id"] == 3)) {
                    if ($user["role_id"] == 3) {//教研员
                        $user["status"] = 1;
                    }
                } else {
                    $user["role_id"] = 2;//普通教师 写死
                }
                $user["account"] = $account;
                $user = $userService->create($user); // 注册
                if ($user) {
                    //$this->guard()->login($user); // 登录
                    $token = empty($user['im_token']) ? DataStandard::getToken($user->id) : $user['im_token'];
                    Cache::put($token, $token, 60 * 24 * 365);
                    $meetId = $request->input("meet_id");//带有meet_id字段的表示需要直接报名
                    if ($meetId) {
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
            }
            return DataStandard::getStandardData([], $msg, 123);
        }

        return DataStandard::getStandardData([], config("validator.119"), 119);
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
