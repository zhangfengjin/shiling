<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/1/11
 * Time: 15:58
 */

namespace App\Http\Services;


use App\User;

class UserService
{
    /**
     * 添加用户
     * @param $input
     */
    public function add($input)
    {
        $user = new User();
        $user->tel = 15510239712;
        $user->save();
    }
}