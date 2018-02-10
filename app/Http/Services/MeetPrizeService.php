<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/2/11
 * Time: 0:15
 */

namespace App\Http\Services;


use App\Models\MeetPrize;
use App\Utils\DataStandard;
use Illuminate\Support\Facades\DB;

class MeetPrizeService extends CommonService
{

    public function create($input)
    {
        $meetPrize = new MeetPrize();
        $meetPrize->name = $input['prize_name'];
        $meetPrize->remark = $input['remark'];
        $meetPrize->prize_count = $input['prize_count'];
        $meetPrize->meet_id = $input['meet_id'];
        $meetPrize->creator = $this->user['uid'];
        $meetPrize->save();
    }

    public function update($input, $meetPrizeId)
    {
        $meetPrize = MeetPrize::where("id", $meetPrizeId)->first();
        if ($meetPrize) {
            $meetPrize->name = $input['prize_name'];
            $meetPrize->remark = $input['remark'];
            $meetPrize->prize_count = $input['prize_count'];
            $meetPrize->meet_id = $input['meet_id'];
            $meetPrize->save();
        }

    }

    public function show($prizeId)
    {
        $where = [
            "flag" => 0,
            "id" => $prizeId
        ];
        $meetPrize = MeetPrize::where($where)->first();
        return $meetPrize;
    }

    /**
     * @return array
     */
    public function getList()
    {
        $where = $this->getSearchWhere($this->searchs);
        $whereLimit = [
            'mp.flag' => 0
        ];

        //获取查询的记录数
        $total = DB::table("meet_prizes as mp")
            ->join("meets as meet", 'meet.id', '=', 'mp.meet_id')
            ->where($whereLimit)
            ->whereRaw($where)->count();
        //要查询的字段
        $select = [
            'mp.id', 'mp.remark', 'mp.name', 'mp.prize_count'
        ];
        $meetName = DB::raw("meet.name as meet_name");
        $prizeName = DB::raw("mp.name as prize_name");
        $userName = DB::raw("u.name as user_name");
        //array_push($select, $meetName, $prizeName, $userName);
        //获取查询结果
        $sortField = "mp.id";
        $sSortDir = "asc";
        $rows = DB::table("meet_prizes as mp")
            ->join("meets as meet", 'meet.id', '=', 'mp.meet_id')
            ->where($whereLimit)->whereRaw($where)
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
            array_push($where, "mp.meet_id=$meetId");
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