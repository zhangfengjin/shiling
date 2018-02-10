<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/2/9
 * Time: 18:52
 */

namespace App\Http\Services;


use App\Utils\DataStandard;
use Illuminate\Support\Facades\DB;

class MeetUserService extends CommonService
{

    /**
     * @return array
     */
    public function getList()
    {
        $where = $this->getSearchWhere($this->searchs);
        //获取查询的记录数
        $total = DB::table("meet_users as mu")->whereRaw($where)->where("flag", 0)->count();
        //要查询的字段
        $select = [
            'mu.id', 'mu.meet_id', 'mu.user_id', 'u.phone', 'u.email', 'u.name'
        ];
        $inMeetStatus = DB::raw("case when mu.status=1 then '已付款' when mu.status=2 then '已退款' when mu.status=3 then '已签到' else '已报名' end as status");
        array_push($select, $inMeetStatus);
        //获取查询结果
        $sortField = "mu.id";
        $sSortDir = "asc";
        $rows = DB::table("meet_users as mu")
            ->leftJoin("users as u", 'u.id', '=', 'mu.user_id')
            ->leftJoin("meets as meet", 'meet.id', '=', 'mu.meet_id')
            ->where("mu.flag", 0)->whereRaw($where)
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
        if (!empty($meetId)) {
            array_push($where, "mu.meet_id=$meetId");
        }
        if (!empty($status)) {
            array_push($where, "mu.status=$status");
        }
        $where = implode(" and ", $where);
        if (empty($where)) {
            return $sql;
        }
        return $where;
    }
}