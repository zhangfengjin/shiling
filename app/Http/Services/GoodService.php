<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/2/11
 * Time: 17:07
 */

namespace App\Http\Services;


use App\Models\Goods;
use App\Models\GoodsAtt;
use App\Utils\DataStandard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GoodService extends CommonService
{
    public function create($input)
    {
        DB::beginTransaction();
        try {
            $goods = new Goods();
            $goods->name = $input['goods_name'];
            $goods->series = $input['goods_name'];
            $goods->goods_type_id = $input['goods_type_id'];
            $goods->goods_count = $input['goods_count'];
            $goods->goods_residue_count = $input['goods_count'];
            $goods->price = $input['price'];
            $goods->abstract = $input['abstract'];
            $goods->goods_detail = $input['goods_detail'];
            $goods->creator = $this->user['uid'];
            $goods->status = $input['status'];
            $goods->save();
            if (isset($input['atts'])) {
                $goodAtts = [];
                foreach ($input['atts'] as $att) {
                    $goodAtts[] = [
                        "goods_id" => $goods->id,
                        "att_id" => $att,
                        "creator" => $this->user['uid']
                    ];
                }
                if (!empty($goodAtts)) {
                    DB::table("goods_atts")->insert($goodAtts);
                }
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    public function show($goodsId)
    {
        $where = [
            "g.id" => $goodsId,
            "g.flag" => 0
        ];
        $select = [
            'g.id', 'g.name', 'g.goods_type_id', 'g.goods_count',
            'g.goods_residue_count', 'g.price', 'g.abstract', 'goods_detail'
        ];
        $userName = DB::raw("u.name as user_name");
        $goodsTypeName = DB::raw("dict.value as goods_type");
        array_push($select, $userName, $goodsTypeName);
        $goods = DB::table("goods as g")
            ->leftJoin("users as u", 'u.id', '=', 'g.creator')
            ->leftJoin("dicts as dict", 'dict.id', '=', 'g.goods_type_id')
            ->where($where)->get($select)->first();
        $gaWhere = [
            "goods_id" => $goodsId,
            "ga.flag" => 0
        ];
        $goodAtts = DB::table("goods_atts as ga")
            ->leftJoin("attachments as att", 'att.id', '=', 'ga.att_id')
            ->where($gaWhere)
            ->get(["att.diskposition", "att.filename", "att.id"]);
        if ($goods) {
            $goods->goodAtts = [];
            foreach ($goodAtts as $goodAtt) {
                $att = [
                    "url" => $goodAtt->diskposition . $goodAtt->filename,
                    "id" => $goodAtt->id
                ];
                $goods->goodAtts[] = $att;
            }
        }

        return $goods;
    }

    /**
     * 更新
     * @param $input
     * @param $goodsId
     * @throws \Exception
     */
    public function update($input, $goodsId)
    {
        DB::beginTransaction();
        try {
            $goods = Goods::find($goodsId);
            $goods->name = $input['goods_name'];
            $goods->series = $input['goods_name'];
            $goods->goods_type_id = $input['goods_type_id'];
            $goods->goods_count = $input['goods_count'];
            $goods->price = $input['price'];
            $goods->abstract = $input['abstract'];
            $goods->goods_detail = $input['goods_detail'];
            $goods->status = $input['status'];
            $goods->save();
            $goodAtts = [];
            $atts = [];
            if (isset($input['atts'])) {
                $atts = $input['atts'];
            }
            $gaWhere = [
                "goods_id" => $goodsId,
                "flag" => 0
            ];
            if (empty($atts)) {
                DB::table("goods_atts")->where($gaWhere)->update(["flag" => 1]);
            } else {
                $dbAtts = GoodsAtt::where($gaWhere)->get(["att_id"]);
                $oldAtts = [];
                foreach ($dbAtts as $dbAtt) {
                    $oldAtts[] = $dbAtt->att_id;
                }
                foreach ($atts as $idx => $att) {
                    if (($key = array_search($att, $oldAtts)) === FALSE) {
                        $goodAtts[] = [
                            "goods_id" => $goodsId,
                            "att_id" => $att,
                            "creator" => $this->user['uid']
                        ];
                    } else {
                        array_splice($oldAtts, $key, 1); // 删除存在的
                    }
                }
                if (!empty ($oldAtts)) {
                    DB::table("goods_atts")->where($gaWhere)->whereIn("att_id", $oldAtts)->update(["flag" => 1]);
                }
                if (!empty($goodAtts)) {
                    DB::table("goods_atts")->insert($goodAtts);
                }
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    public function delete($goodsIds)
    {
        $ids = array_filter(explode(",", $goodsIds));
        if ($goodsIds) {
            $updateInfo = [
                //'flag' => 0
                'status' => 2
            ];
            DB::beginTransaction();
            try {
                Goods::whereIn("id", $ids)->update($updateInfo);
                GoodsAtt::whereIn("goods_id", $ids)->update(["flag" => 1]);
                DB::commit();
            } catch (\Exception $ex) {
                DB::rollback();
                throw $ex;
            }
        }
        return true;
    }


    public function getList()
    {
        $where = $this->getSearchWhere($this->searchs);
        //获取查询的记录数
        $total = DB::table("goods as g")->whereRaw($where)->where("flag", 0)->count();
        //要查询的字段
        $select = [
            'g.id', 'g.name', 'g.goods_type_id', 'g.goods_residue_count',
            'g.goods_count', 'g.price', 'g.abstract', 'g.series'
        ];
        $goodsSellCount = DB::raw("g.goods_count-g.goods_residue_count as goods_sell_count");
        $userName = DB::raw("u.name as user_name");
        $goodsTypeName = DB::raw("dict.value as goods_type");
        $goodsStatus = DB::raw("case when g.status=1 then '已上架' when g.status=2 then '已下架' else '暂存' end as status");
        array_push($select, $goodsSellCount, $userName, $goodsTypeName, $goodsStatus);
        //获取查询结果
        $sortField = "g.id";
        $sSortDir = "asc";
        $rows = DB::table("goods as g")
            ->leftJoin("users as u", 'u.id', '=', 'g.creator')
            ->leftJoin("dicts as dict", 'dict.id', '=', 'g.goods_type_id')
            ->where("g.flag", 0)->whereRaw($where)
            ->orderBy($sortField, $sSortDir)
            ->take($this->iDisplayLength)
            ->skip($this->iDisplayStart)->get($select);
        foreach ($rows as $row) {
            $row->id = strval($row->id);
        }
        return DataStandard::getListData($this->sEcho, $total, $rows);
    }

    public function getGoodsTjList()
    {
        $where = $this->getSearchWhere($this->searchs);
        //获取查询的记录数
        $total = DB::table("goods as g")->whereRaw($where)->where("flag", 0)->count();
        //要查询的字段
        $select = [
            'g.id', 'g.name', 'g.goods_type_id', 'g.goods_count', 'g.price', 'g.abstract'
        ];
        $userName = DB::raw("u.name as user_name");
        $goodsTypeName = DB::raw("dict.value as goods_type");
        $goodsStatus = DB::raw("case when g.status=1 then '已上架' when g.status=2 then '已下架' else '暂存' end as status");
        array_push($select, $userName, $goodsTypeName, $goodsStatus);
        //获取查询结果
        $sortField = "g.id";
        $sSortDir = "asc";
        $rows = DB::table("goods as g")
            ->leftJoin("users as u", 'u.id', '=', 'g.creator')
            ->leftJoin("dicts as dict", 'dict.id', '=', 'g.goods_type_id')
            ->leftJoin("")
            ->where("g.flag", 0)->whereRaw($where)
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
        $meetName = isset($searchs["goodsName"]) ? trim($searchs["goodsName"])
            : (isset($this->allInput["goodsName"]) ? trim($this->allInput["goodsName"]) : "");//合同号
        if (!empty($meetName)) {
            array_push($where, "g.name like '%$meetName%'");
        }
        $where = implode(" and ", $where);
        if (empty($where)) {
            return $sql;
        }
        return $where;
    }
}