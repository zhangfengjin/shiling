<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/1/17
 * Time: 11:09
 */

namespace App\Http\Services;


use App\Utils\DataStandard;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MeetService extends CommonService
{
    public function store()
    {
        if ($this->qrcode()) {

        }
    }

    /**
     * 生成签到二维码
     */
    private function qrcode()
    {
        $codeImg = config('app.qrcode.path');
        if (!file_exists($codeImg)) {
            $sign = config('app.qrcode.sign');
            QrCode::format('png')->size(300)->generate($sign, $codeImg);
        }
        return true;
    }

    public function getList()
    {
        $where = $this->getSearchWhere($this->searchs);
        //获取查询的记录数
        $total = DB::table("meets as meet")->whereRaw($where)->where("flag", 0)->count();
        //要查询的字段
        $select = [
            'meet.id', 'meet.name', 'meet.addr', 'meet.begin_time', 'meet.end_time',
            'meet.keynote_speaker', 'meet.limit_count', 'meet.to_object'
        ];
        $status = DB::raw("case when meet.status=1 then '已取消' else '正常' end status");
        $areaName = DB::raw("CONCAT(province_name,'-',city_name,'-',area_name) as area_name");
        $userName = DB::raw("u.name as user_name");
        array_push($select, $status, $areaName, $userName);
        //获取查询结果
        $sortField = "meet.id";
        $sSortDir = "asc";
        $rows = DB::table("meets as meet")
            ->join("areas as area", "area.id", "=", "meet.area_id")
            ->join("users as u", 'u.id', '=', 'meet.creator')
            ->where("meet.flag", 0)->whereRaw($where)
            ->orderBy($sortField, $sSortDir)
            ->take($this->iDisplayLength)
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
        if ((!count($searchs) || empty($searchs)) && empty($this->allInput)) {
            return $sql;
        }
        $where = [];
        $schoolName = isset($searchs["meetName"]) ? trim($searchs["meetName"])
            : (isset($this->allInput["meetName"]) ? trim($this->allInput["meetName"]) : "");//合同号
        if (!empty($schoolName)) {
            array_push($where, "meet.name like '%$schoolName%'");
        }
        $where = implode(" and ", $where);
        if (empty($where)) {
            return $sql;
        }
        return $where;
    }
}