<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/2/11
 * Time: 14:38
 */

namespace App\Http\Services;


use App\Models\Order;
use App\Utils\DataStandard;
use Illuminate\Support\Facades\DB;

class OrderService extends CommonService
{
    public function create($input)
    {

    }


    public function show($orderId)
    {
        $where = [
            'o.id' => $orderId,
            'o.flag' => 0
        ];
        $select = [
            'o.id', 'o.code', 'o.place_order_time', 'o.pay_way', 'o.pay_code',
            'o.total_price', 'o.take_tel', 'o.take_name', 'o.status', 'o.place_order_people',
            'o.take_addr', 'o.bill_type', 'o.bill_title', 'o.pay_duty_code', 'o.bill_use_id'
        ];
        $billType = DB::raw("case when o.bill_type=1 then '电子' when o.bill_type=2 then '纸质' else '无' end as bill_type_desc");
        $orderStatus = DB::raw("case when o.status=1 then '待发货' when o.status=2 then '已发货' when o.status=3 then '已签收' when o.status=4 then '已取消' else '未支付' end as status_desc");
        $userName = DB::raw("u.name as user_name");
        array_push($select, $billType, $orderStatus, $userName);
        $order = DB::table("orders as o")
            ->leftJoin("users as u", 'u.id', '=', 'o.place_order_people')
            ->leftJoin("dicts as dict", 'dict.id', '=', 'o.bill_use_id')
            ->where($where)->get($select)->first();
        return $order;
    }

    public function update($input, $orderId)
    {
        $order = Order::find($orderId);
        $order->status = $input['status'];
        $order->save();
        return true;
    }

    public function getList()
    {
        $where = $this->getSearchWhere($this->searchs);
        //获取查询的记录数
        $total = DB::table("orders as o")->whereRaw($where)->where("flag", 0)->count();
        //要查询的字段
        $select = [
            'o.id', 'o.code', 'o.place_order_time', 'o.pay_way',
            'o.pay_code', 'o.total_price', 'o.take_tel', 'o.take_name'
        ];
        $billType = DB::raw("case when o.bill_type=1 then '电子' when o.bill_type=2 then '纸质' else '无' end as bill_type");
        $orderStatus = DB::raw("case when o.status=1 then '待发货' when o.status=2 then '已发货' when o.status=3 then '已签收' when o.status=4 then '已取消' else '未支付' end as status");
        $userName = DB::raw("u.name as user_name");
        array_push($select, $billType, $orderStatus, $userName);
        //获取查询结果
        $sortField = "o.id";
        $sSortDir = "asc";
        $rows = DB::table("orders as o")
            ->leftJoin("users as u", 'u.id', '=', 'o.place_order_people')
            ->leftJoin("dicts as dict", 'dict.id', '=', 'o.bill_use_id')
            ->where("o.flag", 0)->whereRaw($where)
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
        $orderCode = isset($searchs["order_code"]) ? trim($searchs["order_code"])
            : (isset($this->allInput["order_code"]) ? trim($this->allInput["order_code"]) : "");//合同号
        $startTime = isset($searchs["start_time"]) ? trim($searchs["start_time"])
            : (isset($this->allInput["start_time"]) ? trim($this->allInput["start_time"]) : "");//合同号
        $endTime = isset($searchs["end_time"]) ? trim($searchs["end_time"])
            : (isset($this->allInput["end_time"]) ? trim($this->allInput["end_time"]) : "");//合同号
        $status = isset($searchs["status"]) ? trim($searchs["status"])
            : (isset($this->allInput["status"]) ? trim($this->allInput["status"]) : "");//合同号
        if (!empty($orderCode)) {
            array_push($where, "o.code like '%$orderCode%'");
        }
        if ($startTime && $endTime) {
            array_push($where, "(o.place_order_time >= '$startTime' and o.place_order_time <= '$endTime')");
        }
        if ($status !== "") {
            array_push($where, "o.status = $status");
        }
        $where = implode(" and ", $where);
        if (empty($where)) {
            return $sql;
        }
        return $where;
    }
}