<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2017/2/22
 * Time: 14:18
 */

namespace App\Utils;


class DataStandard
{
    /**
     * 格式化列表数据
     * @param $sEcho
     * @param $totalCount
     * @param array $rows
     * @return array
     */
    public static function getListData($sEcho, $totalCount, $rows = [])
    {
        return [
            "sEcho" => $sEcho,
            "iTotalRecords" => $totalCount,
            "iTotalDisplayRecords" => $totalCount,
            "aaData" => $rows
        ];
    }

    /**
     * 格式化返回到前端的数据
     * @param $data
     * @param string $msg
     * @param int $code
     * @return array
     */
    public static function printStandardData($data = "", $msg = "", $code = 0)
    {
        return json_encode([
            "code" => $code,
            "data" => $data,
            "message" => $msg
        ]);
    }
}