<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/1/17
 * Time: 11:09
 */

namespace App\Http\Services;


use App\Jobs\NotifyJob;
use App\Models\Meet;
use App\Models\MeetNotify;
use App\Utils\DataStandard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MeetService extends CommonService
{
    public function create($input)
    {
        $meet = new Meet();
        $meet->name = $input['meetName'];
        $meet->keynote_speaker = $input['keynote_speaker'];
        $meet->limit_count = $input['limit_count'];
        $meet->begin_time = $input['begin_time'];
        $meet->end_time = $input['end_time'];
        $meet->to_object = $input['to_object'];
        $meet->area_id = $input['area_id'];
        $meet->addr = $input['addr'];
        $meet->abstract = $input['abstract'];
        $meet->keynote_speaker_id = 0;//主讲人封面id
        $meet->creator = $this->user['uid'];
        $meet->save();
        $this->qrcode($meet->id);//生成签到二维码
        return true;
    }

    /**
     * @param $input
     * @param $meetId
     * @return bool
     */
    public function update($input, $meetId)
    {
        $meet = Meet::find($meetId);
        $meet->name = $input['meetName'];
        $meet->keynote_speaker = $input['keynote_speaker'];
        $meet->limit_count = $input['limit_count'];
        $meet->begin_time = $input['begin_time'];
        $meet->end_time = $input['end_time'];
        $meet->to_object = $input['to_object'];
        $meet->area_id = $input['area_id'];
        $meet->addr = $input['addr'];
        $meet->abstract = $input['abstract'];
        $meet->keynote_speaker_id = 0;//主讲人封面id
        $meet->modifier = $this->user['uid'];
        $meet->save();
        return true;
    }

    /**
     * 生成签到二维码
     */
    private function qrcode($meetId)
    {
        $codeImg = config('app.qrcode.path') . $meetId . ".png";
        if (!file_exists($codeImg)) {
            $sign = config('app.qrcode.sign') . "/$meetId";
            QrCode::format('png')->size(300)->generate($sign, $codeImg);
        }
        return true;
    }

    /**
     * 会议列表
     * @return array
     */
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
        $areaName = DB::raw("CONCAT(province_name,'-',city_name,'-',area_name) as pca_name");
        $userName = DB::raw("u.name as user_name");
        array_push($select, $status, $areaName, $userName);
        //获取查询结果
        $sortField = "meet.id";
        $sSortDir = "asc";
        $rows = DB::table("meets as meet")
            ->leftJoin("areas as area", "area.id", "=", "meet.area_id")
            ->leftJoin("users as u", 'u.id', '=', 'meet.creator')
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
     * @param $meetId
     * @return mixed
     */
    public function show($meetId)
    {
        $select = [
            'meet.id', 'meet.addr', 'meet.begin_time', 'meet.end_time', 'meet.keynote_speaker',
            'meet.limit_count', 'meet.to_object', 'meet.creator', 'meet.abstract', 'meet.area_id',
            'area.area_name', 'area.province_code', 'area.province_name', 'area.city_code', 'area.city_name'
        ];
        $creatorName = DB::RAW("u.name as creator_name");
        $meetName = DB::RAW("meet.name as meet_name");
        array_push($select, $creatorName, $meetName);
        $meet = DB::table("meets as meet")
            ->leftJoin("users as u", 'u.id', '=', 'meet.creator')
            ->leftJoin("areas as area", 'area.id', '=', 'meet.area_id')
            ->where("meet.id", $meetId)->get($select)->first();
        return $meet;
    }

    /**
     * 取消会议
     * @param $meetId
     * @return bool
     */
    public function cancel($input, $meetId)
    {
        $meet = Meet::find($meetId);
        if ($meet) {
            $meet->status = 1;
            $meet->reason = $input['reason'];
            $meet->save();
            //$input["meetId"] = $meetId;
            //dispatch((new NotifyJob($input))->onQueue("meet_cancel"));
            return true;
        }
        return false;
    }

    /**
     * @param $input
     * @param $meetId
     */
    public function notify($input, $meetId)
    {
        $input["meetId"] = $meetId;
        dispatch((new NotifyJob($input))->onQueue("meet_notify"));
    }

    /**
     * @param $input
     * @param $meetId
     */
    public function refund($input, $meetUserIds)
    {
        $ids = array_filter(explode(",", $meetUserIds));
        $res = DB::table("meet_users as mu")
            ->where("status", 1)->whereIn("id", $ids)
            ->update(["status" => 2]);
        //todo
        //获取退款人员订单号、退款金额等信息
        //将退款信息放入退款队列
        return true;
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