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
    /**
     * 获取学校枚举
     * @return \Illuminate\Support\Collection
     */
    public function getSchoolEnum()
    {
        $select = [
            'sch.id', "sch.name", "province_name", "city_name", "area_name"
        ];
        $schools = DB::table("schools as sch")
            ->join("areas as area", "area.id", "=", "sch.area_id")
            ->where("sch.flag", 0)->get($select);
        return $schools;
    }

    /**
     * 获取列表
     * @return array
     */
    public function getList()
    {
        $where = $this->getSearchWhere($this->searchs);
        //获取查询的记录数
        $total = DB::table("schools as sch")->whereRaw($where)->where("flag", 0)->count();
        //要查询的字段
        $select = [
            'sch.id', 'province_name', 'city_name', 'area_name'
        ];
        $name = DB::raw("CONCAT(name,'(',province_name,'-',city_name,'-',area_name,')') as name");
        $schoolName = DB::raw("name as school_name");
        array_push($select, $name, $schoolName);
        //获取查询结果
        $sortField = "sch.id";
        $sSortDir = "asc";
        $rows = DB::table("schools as sch")
            ->join("areas as area", "area.id", "=", "sch.area_id")
            ->where("sch.flag", 0)->whereRaw($where)->orderBy($sortField, $sSortDir)
            ->take($this->iDisplayLength)
            ->skip($this->iDisplayStart)->get($select);
        foreach ($rows as $row) {
            $row->id = strval($row->id);
        }
        return DataStandard::getListData($this->sEcho, $total, $rows);
    }

    public function update($input, $schoolId)
    {
        $school = School::find($schoolId);
        $school->area_id = $input["area_id"];
        $school->name = $input["schoolName"];
        $school->save();
        return true;
    }

    public function create($input)
    {
        $areaId = $input["area_id"];
        $schoolName = $input["schoolName"];
        $where = [
            "area_id" => $areaId,
            "name" => $schoolName,
        ];
        $school = School::where($where)->count();
        if (!$school) {
            $school = new School();
            $school->area_id = $areaId;
            $school->name = $schoolName;
            $school->save();
            return $school;
        }
        return false;
    }

    /**
     * 获取查询条件
     * @param $search
     * @return array|bool
     */
    private function getSearchWhere($searchs)
    {
        $sql = "1=1";
        if ((!count($searchs) || empty($searchs)) && empty($this->allInput)) {
            return $sql;
        }
        $where = [];
        $schoolName = isset($searchs["schoolName"]) ? trim($searchs["schoolName"])
            : (isset($this->allInput["schoolName"]) ? trim($this->allInput["schoolName"]) : "");//合同号
        if (!empty($schoolName)) {
            array_push($where, "sch.name like '%$schoolName%'");
        }
        $where = implode(" and ", $where);
        if (empty($where)) {
            return $sql;
        }
        return $where;
    }
}