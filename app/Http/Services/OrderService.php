<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/2/11
 * Time: 14:38
 */

namespace App\Http\Services;


use App\Models\Goods;
use App\Models\Order;
use App\Models\OrderGoods;
use App\Utils\DataStandard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class OrderService extends CommonService
{
    public function create($input)
    {
        $inputFields = [
            'take_addr', 'take_tel', 'take_name', 'bill_type',
            'bill_title', 'pay_duty_code', 'bill_use_id'
        ];
        if (isset($input['goods'])) {
            DB::beginTransaction();
            try {
                $order = new Order();
                $order->name = "用户" . $this->user['uid'] . "的商品订单";
                $order->code = "goods_" . $this->user['uid'] . "_" . time();//订单号
                $order->status = 0;
                $order->place_order_people = $this->user['uid'];
                foreach ($inputFields as $inputField) {
                    if (isset($input[$inputField])) {
                        $order->$inputField = $input[$inputField];
                    }
                }
                $order->save();
                $goods = $input['goods'];
                foreach ($goods as $good) {
                    $goodsIds[] = $good["goods_id"];
                }
                if (!empty($goodsIds)) {
                    $dbGoods = Goods::whereIn("id", $goodsIds)
                        ->get(["id", "goods_residue_count", "name", "price", "status", "flag"]);
                    $orderGoods = [];
                    $totalPrice = 0;
                    foreach ($dbGoods as $dbGood) {
                        if ($dbGood->status != 1 || $dbGood->flag != 0) {
                            DataStandard::printStandardDataExit([$dbGood->name], config("validator.752"), 752);
                        }
                        foreach ($goods as $good) {
                            $goodsId = $good["goods_id"];
                            $goodsCount = $good["goods_count"];
                            //商品是否存在 且商品剩余数量大于等于购买数量
                            if ($dbGood->id == $goodsId
                            ) {
                                if ($dbGood->goods_residue_count >= $goodsCount) {
                                    $goodsPrice = $goodsCount * $dbGood->price;
                                    $totalPrice += $goodsPrice;
                                    $orderGoods[] = [
                                        "goods_id" => $goodsId,
                                        "goods_count" => $goodsCount,
                                        "goods_price" => $goodsPrice,
                                        "orders_id" => $order->id
                                    ];
                                    Goods::where("id", $dbGood->id)->update([
                                        "goods_residue_count" => ($dbGood->goods_residue_count - $good["goods_count"])
                                    ]);
                                    break;
                                } else {
                                    DataStandard::printStandardDataExit([$dbGood->name], config("validator.751"), 751);
                                }
                            }
                        }
                    }
                    if (!empty($orderGoods)) {
                        DB::table("order_goods")->insert($orderGoods);
                    }
                    $order->total_price = $totalPrice;
                    $order->save();
                    DB::commit();
                    return $order;
                } else {
                    DataStandard::printStandardDataExit([], config("validator.753"), 753);
                }
            } catch (\Exception $ex) {
                DB::rollback();
                throw $ex;
            }
        }
        DataStandard::printStandardDataExit([], config("validator.753"), 753);
    }

    public function update($input, $ordersId)
    {
        $inputFields = [
            'take_addr', 'take_tel', 'take_name', 'bill_type',
            'bill_title', 'pay_duty_code', 'bill_use_id'
        ];
        $where = [
            "id" => $ordersId,
            "place_order_people" => $this->user['uid'],
            "status" => 0,
            "flag" => 0
        ];
        $order = Order::where($where)->first();
        if ($order) {
            DB::beginTransaction();
            try {
                $goods = $input['goods'];
                foreach ($goods as $good) {
                    $goodsIds[] = $good["goods_id"];
                }
                if (!empty($goodsIds)) {
                    $ogWhere = [
                        "orders_id" => $ordersId,
                        "flag" => 0
                    ];
                    $dbOrderGoods = OrderGoods::where($ogWhere)->get(["goods_id", "goods_count", "goods_price"]);
                    //删除订单下的所有商品
                    OrderGoods::where($ogWhere)->update(["flag" => 1]);
                    $dbGoods = Goods::whereIn("id", $goodsIds)
                        ->get(["id", "goods_residue_count", "name", "price","status","flag"]);
                    $orderGoods = [];
                    $totalPrice = 0;
                    foreach ($dbGoods as $dbGood) {
                        if ($dbGood->status != 1 || $dbGood->flag != 0) {
                            DataStandard::printStandardDataExit([$dbGood->name], config("validator.752"), 752);
                        }
                        $residueCount = $dbGood->goods_residue_count;
                        //更新商品的剩余数量
                        foreach ($dbOrderGoods as $dbOrderGood) {
                            if ($dbOrderGood->goods_id == $dbGood->id) {
                                $residueCount = $dbGood->goods_residue_count + $dbOrderGood->goods_count;
                                break;
                            }
                        }
                        foreach ($goods as $good) {
                            $goodsId = $good["goods_id"];
                            $goodsCount = $good["goods_count"];
                            if ($dbGood->id == $goodsId
                            ) {//商品存在
                                if ($residueCount >= $goodsCount) {
                                    $goodsPrice = $goodsCount * $dbGood->price;
                                    $totalPrice += $goodsPrice;
                                    $orderGoods[] = [
                                        "goods_id" => $goodsId,
                                        "goods_count" => $goodsCount,
                                        "goods_price" => $goodsPrice,
                                        "orders_id" => $order->id
                                    ];
                                    Goods::where("id", $dbGood->id)->update([
                                        "goods_residue_count" => ($residueCount - $good["goods_count"])
                                    ]);
                                    break;
                                } else {
                                    DataStandard::printStandardDataExit([$dbGood->name], config("validator.751"), 751);
                                }
                            }
                        }
                    }
                    if (!empty($orderGoods)) {
                        DB::table("order_goods")->insert($orderGoods);
                    }
                    $order->total_price = $totalPrice;
                    foreach ($inputFields as $inputField) {
                        if (isset($input[$inputField])) {
                            $order->$inputField = $input[$inputField];
                        }
                    }
                    $order->save();
                    DB::commit();
                    return $order;
                } else {
                    DataStandard::printStandardDataExit([], config("validator.753"), 753);
                }
            } catch (\Exception $ex) {
                DB::rollback();
                throw $ex;
            }
        }
        DataStandard::printStandardDataExit([], config("validator.755"), 755);
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
        if ($order) {
            $goodSelect = [
                'g.id', 'g.name', 'g.goods_type_id', 'g.goods_count',
                'g.goods_residue_count', 'g.price', 'g.abstract'
            ];
            $goodsTypeName = DB::raw("dict.value as goods_type");
            $orderGoodsCount = DB::raw("og.goods_count as order_goods_count");
            $orderGoodsPrice = DB::raw("og.goods_price as order_goods_price");
            array_push($goodSelect, $goodsTypeName, $orderGoodsCount, $orderGoodsPrice);
            $goods = DB::table("order_goods as og")
                ->join("goods as g", 'g.id', '=', 'og.goods_id')
                ->leftJoin("dicts as dict", 'dict.id', '=', 'g.goods_type_id')
                ->where([
                    "og.orders_id" => $orderId,
                    "og.flag" => 0
                ])->get($goodSelect);
            $order->goods = $goods;
            foreach ($goods as &$good) {
                $gaWhere = [
                    "goods_id" => $good->id,
                    "ga.flag" => 0
                ];
                $goodAtts = DB::table("goods_atts as ga")
                    ->leftJoin("attachments as att", 'att.id', '=', 'ga.att_id')
                    ->where($gaWhere)
                    ->get(["att.diskposition", "att.filename", "att.id"]);
                $good->goodAtts = [];
                foreach ($goodAtts as $goodAtt) {
                    $att = [
                        "url" => $goodAtt->diskposition . $goodAtt->filename,
                        "id" => $goodAtt->id
                    ];
                    $good->goodAtts[] = $att;
                }
            }
        }
        return $order;
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

    public function export()
    {
        //构建查询条件
        $where = $this->getSearchWhere($this->searchs);
        //要查询的字段
        $select = [
            'o.code', 'o.place_order_time', 'o.pay_way', 'o.total_price',
            'o.take_tel', 'o.take_name'
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
            ->get($select)->toArray();
        //导出Excel的表头
        $title = [
            '订单号', '下单时间', '支付方式', '订单总额', '收货人手机', '收货人', '发票类型', '订单状态', '下单人'
        ];
        $rows = json_decode(json_encode($rows), true);
        array_unshift($rows, $title);
        $excelName = "Order_List_" . date("Y-m-d-H-i-s");
        Excel::create($excelName, function ($excel) use ($rows) {
            $excel->sheet('Order_List_', function ($sheet) use ($rows) {
                $sheet->rows($rows);
            });
        })->export('xlsx');
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