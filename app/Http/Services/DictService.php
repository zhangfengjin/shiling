<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/1/22
 * Time: 10:25
 */

namespace App\Http\Services;


use App\Models\Dict;

class DictService extends CommonService
{
    /**
     * 获取字典
     * @param $type
     * @return $this
     */
    public function getDictByType($type)
    {
        $where = [
            "type" => $type,
            "flag" => 0
        ];
        $dict = Dict::where($where)->get();
        return parent::switchOutKeys($dict);
    }
}