<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/1/19
 * Time: 14:47
 */

namespace App\Http\Services;


use App\Models\School;
use App\Utils\DataStandard;
use Illuminate\Support\Facades\DB;

class SchoolService extends CommonService
{
    public function getList()
    {
        $where = $this->getSearchWhere($this->searchs);
        //获取查询的记录数
        $total = School::whereRaw($where)->count();
        //要查询的字段
        $select = [
            'sch.id'
        ];
        $schoolName = DB::raw("CONCAT(name,'(',province_name,'-',city_name,'-',area_name,')') as name");
        array_push($select, $schoolName);
        //获取查询结果
        $sortField = "sch.id";
        $sSortDir = "desc";
        $rows = DB::table("schools as sch")
            ->join("areas as area", "area.id", "=", "sch.area_id")
            ->whereRaw($where)->orderBy($sortField, $sSortDir)->take($this->iDisplayLength)
            ->skip($this->iDisplayStart)->get($select);
        foreach ($rows as $row) {
            $row->id = strval($row->id);
        }
        return DataStandard::getListData($this->sEcho, $total, $rows);
    }

    /**
     * 获取查询条件
     * @param $search
     * @return array|bool
     */
    private function getSearchWhere($searchs)
    {
        $sql = "1=1";
        if (!count($searchs) || empty($searchs) || empty($this->allInput)) {
            return $sql;
        }
        $where = [];
        $schoolName = isset($searchs["school_name"]) ? trim($searchs["school_name"])
            : (isset($this->allInput["school_name"]) ? trim($this->allInput["school_name"]) : "");//合同号
        if (!empty($schoolName)) {
            array_push($where, "name like '%$schoolName%'");
        }
        $where = implode(" and ", $where);
        if (empty($where)) {
            return $sql;
        }
        return $where;
    }
}