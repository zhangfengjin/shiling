<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/2/10
 * Time: 22:59
 */

namespace App\Http\Services;


use App\Utils\DataStandard;
use Illuminate\Support\Facades\DB;

class PrizeService extends CommonService
{

    /**
     * @return array
     */
    public function getPrizeUserList()
    {
        $where = $this->getSearchWhere($this->searchs);
        //获取查询的记录数
        $total = DB::table("meet_prize_users as mup")
            ->leftJoin("meet_users as mu", 'mu.id', '=', 'mup.meet_user_id')
            ->leftJoin("users as u", 'u.id', '=', 'mu.user_id')
            ->leftJoin("meets as meet", 'meet.id', '=', 'mu.meet_id')
            ->whereRaw($where)->count();
        //要查询的字段
        $select = [
            'mup.id', 'mp.remark', 'u.phone', 'u.email'
        ];
        $meetName = DB::raw("meet.name as meet_name");
        $prizeName = DB::raw("mp.name as prize_name");
        $userName = DB::raw("u.name as user_name");
        array_push($select, $meetName, $prizeName, $userName);
        //获取查询结果
        $sortField = "mup.id";
        $sSortDir = "asc";
        $rows = DB::table("meet_prize_users as mup")
            ->leftJoin("meet_users as mu", 'mu.id', '=', 'mup.meet_user_id')
            ->leftJoin("meet_prizes as mp", 'mp.id', '=', 'mup.prize_id')
            ->leftJoin("users as u", 'u.id', '=', 'mu.user_id')
            ->leftJoin("meets as meet", 'meet.id', '=', 'mu.meet_id')
            ->where([
                "mup.flag" => 0,
                "mu.status" => 4
            ])->whereRaw($where)
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
        $meetId = isset($searchs["meetId"]) ? trim($searchs["meetId"])
            : (isset($this->allInput["meetId"]) ? trim($this->allInput["meetId"]) : "");//合同号
        $status = isset($searchs["status"]) ? trim($searchs["status"])
            : (isset($this->allInput["status"]) ? trim($this->allInput["status"]) : "");//合同号

        $meetName = isset($searchs["meet_name"]) ? trim($searchs["meet_name"])
            : (isset($this->allInput["meet_name"]) ? trim($this->allInput["meet_name"]) : "");//合同号
        $areaId = isset($searchs["area_id"]) ? trim($searchs["area_id"])
            : (isset($this->allInput["area_id"]) ? trim($this->allInput["area_id"]) : "");//合同号
        if (!empty($meetId)) {
            array_push($where, "mu.meet_id=$meetId");
        }
        if ($status !== "") {
            array_push($where, "mu.status=$status");
        }
        if (!empty($meetName)) {
            array_push($where, "meet.name like '%$meetName%'");
        }
        if (!empty($areaId)) {
            array_push($where, "meet.area_id=$areaId");
        }
        $where = implode(" and ", $where);
        if (empty($where)) {
            return $sql;
        }
        return $where;
    }
}