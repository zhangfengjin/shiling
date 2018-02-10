<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/2/9
 * Time: 18:52
 */

namespace App\Http\Services;


use App\Models\MeetUser;
use App\Utils\DataStandard;
use Illuminate\Support\Facades\DB;

class MeetUserService extends CommonService
{

    /**
     * @param $input
     * @return int
     */
    public function getMeetUser($input)
    {
        $where = [
            "meet_id" => $input['meetId'],
            "user_id" => $input['userId']
        ];
        return MeetUser::where($where)->whereRaw("status<>3")->count();
    }

    /**
     * @param $meetId
     * @return mixed
     */
    public function show($muId)
    {
        $select = [
            'mu.id', 'mu.meet_id', 'mu.user_id', 'mu.status',
        ];
        $inMeetStatus = DB::raw("case when mu.status=1 then '已付款' 
        when mu.status=2 then '退款中' 
        when mu.status=3 then '已退款' 
        when mu.status=4 then '已签到' 
        else '已报名' end as status_desc");
        $userName = DB::RAW("u.name as user_name");
        $meetName = DB::RAW("meet.name as meet_name");
        array_push($select, $userName, $meetName, $inMeetStatus);
        $meet = DB::table("meet_users as mu")
            ->leftJoin("meets as meet", 'meet.id', '=', 'mu.meet_id')
            ->leftJoin("users as u", 'u.id', '=', 'meet.creator')
            ->leftJoin("areas as area", 'area.id', '=', 'meet.area_id')
            ->where("mu.id", $muId)->get($select)->first();
        return $meet;
    }


    public function update($input, $id)
    {
        $ids = array_filter(explode(",", $id));
        $update = [
            "status" => $input['status']
        ];
        $meetUser = MeetUser::whereIn("id", $ids)->update($update);
    }

    /**
     * @return array
     */
    public function getList()
    {
        $where = $this->getSearchWhere($this->searchs);
        //获取查询的记录数
        $total = DB::table("meet_users as mu")
            ->leftJoin("meets as meet", 'meet.id', '=', 'mu.meet_id')
            ->whereRaw($where)->where("mu.flag", 0)->count();
        //要查询的字段
        $select = [
            'mu.id', 'mu.meet_id', 'mu.user_id', 'u.phone', 'u.email', 'u.name'
        ];
        $inMeetStatus = DB::raw("case when mu.status=1 then '已付款' 
        when mu.status=2 then '退款中' 
        when mu.status=3 then '已退款' 
        when mu.status=4 then '已签到' 
        else '已报名' end as status");
        $meetName = DB::raw("meet.name as meet_name");
        array_push($select, $inMeetStatus, $meetName);
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

        $meetName = isset($searchs["meet_name"]) ? trim($searchs["meet_name"])
            : (isset($this->allInput["meet_name"]) ? trim($this->allInput["meet_name"]) : "");//合同号
        $areaId = isset($searchs["area_id"]) ? trim($searchs["area_id"])
            : (isset($this->allInput["area_id"]) ? trim($this->allInput["area_id"]) : "");//合同号
        if (!empty($meetId)) {
            array_push($where, "mu.meet_id=$meetId");
        }
        if (!empty($status)) {
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