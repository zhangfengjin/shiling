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
use App\Models\MeetAtt;
use App\Models\MeetNotify;
use App\Models\MeetUser;
use App\Models\Order;
use App\Utils\DataStandard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MeetService extends CommonService
{
    public function create($input)
    {
        DB::beginTransaction();
        try {

            $meet = new Meet();
            $meet->name = $input['meetName'];
            $meet->keynote_speaker = $input['keynote_speaker'];
            $meet->limit_count = $input['limit_count'];
            $meet->begin_time = $input['begin_time'];
            $meet->end_time = $input['end_time'];
            $meet->to_object = $input['to_object'];
            $meet->in_price = $input['in_price'];
            $meet->area_id = $input['area_id'];
            $meet->addr = $input['addr'];
            $meet->abstract = $input['abstract'];
            $meet->keynote_speaker_id = 0;//主讲人封面id
            $meet->creator = $this->user['uid'];
            $meet->type = $input["type"];
            $meet->keynote_speaker_tel = $input["keynote_speaker_tel"];
            $meet->keynote_speaker_email = $input["keynote_speaker_email"];
            $meet->course_id = $input["course_id"];
            if (isset($input["icon_att_id"])) {
                $meet->icon_att_id = $input["icon_att_id"];
            }
            $meet->save();
            if (isset($input["meet_att_id"])) {
                $meetAtt = new MeetAtt();
                $meetAtt->meet_id = $meet->id;
                $meetAtt->att_id = $input["meet_att_id"];
                $meetAtt->creator = $this->user['uid'];
                $meetAtt->save();
            }
            DB::commit();
            $this->qrcode("meet", $meet->id);//生成会议签到二维码
            return true;
        } catch (\Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    /**
     * @param $input
     * @param $meetId
     * @return bool
     */
    public function update($input, $meetId)
    {
        DB::beginTransaction();
        try {
            $meet = Meet::find($meetId);
            $meet->name = $input['meetName'];
            $meet->keynote_speaker = $input['keynote_speaker'];
            $meet->limit_count = $input['limit_count'];
            $meet->begin_time = $input['begin_time'];
            $meet->end_time = $input['end_time'];
            $meet->to_object = $input['to_object'];
            $meet->in_price = $input['in_price'];
            $meet->area_id = $input['area_id'];
            $meet->addr = $input['addr'];
            $meet->abstract = $input['abstract'];
            $meet->keynote_speaker_id = 0;//主讲人封面id
            $meet->modifier = $this->user['uid'];
            $meet->type = $input["type"];
            $meet->keynote_speaker_tel = $input["keynote_speaker_tel"];
            $meet->keynote_speaker_email = $input["keynote_speaker_email"];
            $meet->course_id = $input["course_id"];
            if (isset($input["icon_att_id"])) {
                $meet->icon_att_id = $input["icon_att_id"];
            }
            $meet->save();
            if (isset($input["meet_att_id"])) {
                $where = [
                    "flag" => 0,
                    "meet_id" => $meetId
                ];
                $meetAtt = MeetAtt::where($where)->first();
                if (!$meetAtt) {
                    $meetAtt = new MeetAtt();
                    $meetAtt->meet_id = $meet->id;
                    $meetAtt->att_id = $input["meet_att_id"];
                    $meetAtt->creator = $this->user['uid'];
                } else {
                    $meetAtt->att_id = $input["meet_att_id"];
                    $meetAtt->creator = $this->user['uid'];
                }
                $meetAtt->save();
            }
            DB::commit();
            return true;
        } catch (\Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    /**
     * 生成会议签到二维码
     */
    private function qrcode($type, $meetId)
    {
        $codeImg = config('app.qrcode.path') . $type . '/' . $meetId . ".png";
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
            'meet.keynote_speaker', 'meet.limit_count', 'meet.to_object', 'meet.in_price',
            'meet.icon_att_id'
        ];
        $status = DB::raw("case when meet.status=1 then '已取消' else '正常' end status");
        $areaName = DB::raw("CONCAT(province_name,'-',city_name,'-',area_name) as pca_name");
        $userName = DB::raw("u.name as user_name");
        $type = DB::raw("case when meet.type=1 then '课程' else '会议' end type");
        $iconUrl = DB::raw("CONCAT(att1.diskposition,att1.filename) as icon_url");
        $meetAttId = DB::raw("att2.id as meet_att_id");
        $meetUrl = DB::raw("CONCAT(att2.diskposition,att2.filename) as meet_url");
        array_push($select, $status, $areaName, $userName, $type, $iconUrl, $meetAttId, $meetUrl);
        //获取查询结果
        $sortField = "meet.id";
        $sSortDir = "asc";
        $rows = DB::table("meets as meet")
            ->leftJoin("areas as area", "area.id", "=", "meet.area_id")
            ->leftJoin("users as u", 'u.id', '=', 'meet.creator')
            ->leftJoin("attachments as att1", 'att1.id', '=', 'meet.icon_att_id')
            ->leftJoin("meet_atts as ma", 'ma.meet_id', '=', 'meet.id')
            ->leftJoin("attachments as att2", 'att2.id', '=', 'ma.att_id')
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
            'meet.limit_count', 'meet.to_object', 'meet.creator', 'meet.abstract',
            'meet.area_id', 'meet.in_price', 'meet.icon_att_id',
            'area.area_name', 'area.province_code', 'area.province_name', 'area.city_code',
            'area.city_name', 'meet.keynote_speaker_tel', 'meet.keynote_speaker_email', 'meet.course_id'
        ];
        $creatorName = DB::RAW("u.name as creator_name");
        $meetName = DB::RAW("meet.name as meet_name");
        $iconUrl = DB::raw("CONCAT(att1.diskposition,att1.filename) as icon_url");
        $meetAttId = DB::raw("att2.id as meet_att_id");
        $meetUrl = DB::raw("CONCAT(att2.diskposition,att2.filename) as meet_url");
        array_push($select, $creatorName, $meetName, $iconUrl, $meetAttId, $meetUrl);
        $meet = DB::table("meets as meet")
            ->leftJoin("users as u", 'u.id', '=', 'meet.creator')
            ->leftJoin("areas as area", 'area.id', '=', 'meet.area_id')
            ->leftJoin("attachments as att1", 'att1.id', '=', 'meet.icon_att_id')
            ->leftJoin("meet_atts as ma", 'ma.meet_id', '=', 'meet.id')
            ->leftJoin("attachments as att2", 'att2.id', '=', 'ma.att_id')
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

    public function getMeet($input)
    {
        $where = [
            "status" => 0,
            "id" => $input['meetId']
        ];
        return Meet::where($where)->count();
    }

    /**
     * @param $input
     */
    public function enroll($input)
    {
        $userId = $input['userId'];
        $meetId = $input['meetId'];
        $meet = Meet::find($meetId);
        $inPrice = $meet->in_price;
        DB::beginTransaction();
        try {
            $users = $input['users'];
            //生成报名订单
            $order = new Order();
            $order->name = "用户" . $userId . "报名会议" . $meetId . "的支付订单";
            $order->code = "meet_" . $userId . "_" . $meetId . time();//订单号
            $order->status = 0;
            $order->place_order_people = $userId;
            $order->total_price = count($users) * $inPrice;
            $order->total = count($users);
            $order->save();
            $orderMeet = [];
            foreach ($users as $user) {
                $meetUser = new MeetUser();
                $meetUser->user_id = $user['user_id'];
                $meetUser->meet_id = $meetId;
                $meetUser->order_id = $order->id;
                $meetUser->save();
                $codeImg = config('app.qrcode.path') . 'meet_user/' . $meetUser->id . ".png";
                if (!file_exists($codeImg)) {
                    $sign = config('app.qrcode.usersign') . "?userId" . $meetUser->user_id . "&meetId" . $meetUser->meet_id;
                    QrCode::format('png')->size(300)->generate($sign, $codeImg);
                }
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            throw  $ex;
        }

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
        $meetName = isset($searchs["meetName"]) ? trim($searchs["meetName"])
            : (isset($this->allInput["meetName"]) ? trim($this->allInput["meetName"]) : "");//合同号
        $status = isset($searchs["status"]) ? trim($searchs["status"])
            : (isset($this->allInput["status"]) ? trim($this->allInput["status"]) : false);//合同号
        $courseId = isset($this->user["course_id"]) ? $this->user["course_id"] : 0;
        $type = isset($searchs["type"]) ? trim($searchs["type"])
            : (isset($this->allInput["type"]) ? trim($this->allInput["type"]) : false);
        if (!empty($meetName)) {
            array_push($where, "meet.name like '%$meetName%'");
        }
        if ($status !== false) {
            switch ($status) {
                case 1:
                    //已取消
                    array_push($where, "meet.status = $status");
                    break;
                case 3:
                    //正常
                    array_push($where, "(meet.status = 0 and meet.end_time>now())");
                    break;
                case 4:
                    //已结束
                    array_push($where, "(meet.status = 0 and meet.end_time<now())");
                    break;
                default:
                    array_push($where, "meet.status = $status");
                    break;
            }
        }
        if ($courseId) {
            array_push($where, "meet.course_id = $courseId");
        }
        if ($type !== false) {
            array_push($where, "meet.type = $type");
        }
        $where = implode(" and ", $where);
        if (empty($where)) {
            return $sql;
        }
        return $where;
    }
}