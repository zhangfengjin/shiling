<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/1/19
 * Time: 14:47
 */

namespace App\Http\Services;


use App\Models\School;
use Illuminate\Support\Facades\DB;

class SchoolService
{
    public function getList()
    {
        $where = $this->getSearchWhere($this->searchs);
        //获取查询的记录数
        $total = School::whereRaw($where)->count();;
        //要查询的字段
        $select = [
            'contract_no', 'adid', 'dspid', 'impressions', 'start_time',
            'end_time', 'cid', 'add_time', 'op_user', 'name'
        ];
        $pdbId = DB::raw("pdb.id id");
        $pdbStatus = DB::raw("pdb.status status");
        array_push($select, $pdbId, $pdbStatus);
        //获取查询结果
        $sortField = "add_time";
        $sSortDir = "desc";
        $rows = DB::table("wax_pdb_detail as pdb")
            ->join("wax_dsp as dsp", "dsp.id", "=", "pdb.dspid")
            ->whereRaw($where)->orderBy($sortField, $sSortDir)->take($this->iDisplayLength)
            ->skip($this->iDisplayStart)->get($select);
        $subLen = 15;
        foreach ($rows as $row) {
            $row->status = DataEnum::getAuditStatus("pdb", $row->status);
            $row->adid = strval($row->adid);
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
        if (!count($searchs) || empty($searchs) || empty($this->allInput)) {
            return $sql;
        }
        $where = [];
        $schoolName = isset($searchs["school_name"]) ? trim($searchs["school_name"])
            : (isset($this->allInput["school_name"]) ? trim($this->allInput["school_name"]) : "");//合同号
        if (!empty($schoolName)) {
            array_push($where, "name like '%$schoolName%'");
        }
        $where = implode(" and ", $where);
        if (empty($where)) {
            return $sql;
        }
        return $where;
    }
}