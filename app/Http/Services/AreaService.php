<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/1/30
 * Time: 11:08
 */

namespace App\Http\Services;


use App\Models\Area;

class AreaService extends CommonService
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function getArea($area = "")
    {
        $select = [
            "id", "province_code", "province_name", "city_code", "city_name", "area_code", "area_name"
        ];
        switch ($area) {
            case "province":
                $select = [
                    "province_code", "province_name"
                ];
                break;
            case "city":
                $select = [
                    "province_code", "city_code", "city_name"
                ];
                break;
        }
        return Area::where("flag", 0)->distinct()->get($select);
    }
}