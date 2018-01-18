<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/1/11
 * Time: 15:58
 */

namespace App\Http\Services;


use App\User;
use App\Utils\WyIMHelper;
use Illuminate\Support\Facades\Log;

class UserService
{
    /**
     * 判断email是否存在
     *
     * @param unknown $email
     */
    public function uniqueEmail($email)
    {
        return User::where("email", '=', $email)->count();
    }

    /**
     * 判断电话号码是否存在
     *
     * @param unknown $tel
     */
    public function uniqueTel($tel)
    {
        return User::where("tel", '=', $tel)->count();
    }


    /**
     * 重置密码
     *
     * @param unknown $account
     * @param unknown $reqaccount
     * @param unknown $password
     * @return unknown
     */
    public function resetPwd($account, $reqaccount, $password)
    {
        $user = User::where($account, '=', $reqaccount)->first();
        $user->password = bcrypt($password);
        $user->save();
        return $user;
    }

    /**
     * 添加用户
     * @param $input
     */
    public function create($input)
    {
        $input['im_token'] = "";
        $wyIM = new WyIMHelper();
        $ret = $wyIM->createUserId($input['tel']);
        if ($ret['code'] === 200) {
            $input['im_token'] = $ret["info"]["token"];
        } else {
            Log::info(json_encode($ret));
        }
        $user = new User();
        $user->tel = $input["tel"];
        $user->password = bcrypt($input["password"]);
        $user->im_token = $input["im_token"];
        $user->save();
        return $user;
    }
}