<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/1/25
 * Time: 17:38
 */

namespace App\Utils;


class DataEnum
{
    /**
     * 性别
     * @param int $idx
     * @return mixed|string
     */
    public static function getSex($idx = -1)
    {
        $sex = config("app.sex");
        if ($idx == -1) {
            return $sex;
        } else {
            return (isset($sex[$idx]) ? $sex[$idx] : "");
        }
    }
}