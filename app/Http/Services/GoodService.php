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

class GoodService extends CommonService
{
    public function create($input)
    {
        DB::beginTransaction();
        try {
            $goods = new Goods();
            $goods->name = $input['goods_name'];
            $goods->goods_type_id = $input['goods_type_id'];
            $goods->goods_count = $input['goods_count'];
            $goods->price = $input['price'];
            $goods->abstract = $input['abstract'];
            $goods->goods_detail = $input['goods_detail'];
            $goods->creator = $this->user['uid'];
            $goods->save();
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
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    public function show($goodsId)
    {
        $where = [
            "id" => $goodsId,
            "flag" => 0
        ];
        $select = [
            'g.id', 'g.name', 'g.goods_type_id',
            'g.goods_count', 'g.price', 'g.abstract'
        ];
        $userName = DB::raw("u.name as user_name");
        $goodsTypeName = DB::raw("dict.value as goods_type");
        array_push($select, $userName, $goodsTypeName);
        $goods = DB::table("goods as g")
            ->leftJoin("users as u", 'u.id', '=', 'g.creator')
            ->leftJoin("dicts as dict", 'dict.id', '=', 'g.goods_type_id')
            ->where($where)->get($select)->first();
        $goodAtts = DB::table("goods_atts as ga")
            ->leftJoin("attachments as att", 'att.id', '=', 'ga.att_id')
            ->get(["att.diskposition", "att.filename"])->where(["goods_id"]);
        $goods->goodAtts = [];
        foreach ($goodAtts as $goodAtt) {
            $goods->goodAtts[] = $goodAtt->diskposition . $goodAtt->filename;
        }
        return $goods;
    }

    public function update($input, $goodsId)
    {
        DB::beginTransaction();
        try {
            $goods = Goods::find($goodsId);
            $goods->name = $input['goods_name'];
            $goods->goods_type_id = $input['goods_type_id'];
            $goods->goods_count = $input['goods_count'];
            $goods->price = $input['price'];
            $goods->abstract = $input['abstract'];
            $goods->goods_detail = $input['goods_detail'];
            $goods->save();
            $goodAtts = [];
            foreach ($input['atts'] as $att) {
                $goodAtts[] = [
                    "goods_id" => $goodsId,
                    "att_id" => $att,
                    "creator" => $this->user['uid']
                ];
            }
            if (!empty($goodAtts)) {
                DB::table("goods_atts")->insert($goodAtts);
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
                'flag' => 1
            ];
            DB::beginTransaction();
            try {
                Goods::whereIn("id", $ids)->update($updateInfo);
                GoodsAtt::whereIn("goods_id", $ids)->update($updateInfo);
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
            'g.id', 'g.name', 'g.goods_type_id',
            'g.goods_count', 'g.price', 'g.abstract'
        ];
        $userName = DB::raw("u.name as user_name");
        $goodsTypeName = DB::raw("dict.value as goods_type");
        array_push($select, $userName, $goodsTypeName);
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