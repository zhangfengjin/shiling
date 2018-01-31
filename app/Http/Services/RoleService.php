<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/1/19
 * Time: 15:36
 */

namespace App\Http\Services;


use App\Models\Role;
use App\Utils\DataStandard;
use Illuminate\Support\Facades\DB;

class RoleService extends CommonService
{
    /**
     * @return $this
     */
    public function getRoleEnum()
    {
        $select = ["id", "name"];
        return Role::where("flag", 0)->get($select);
    }

    /**
     * 获取列表
     * @return array
     */
    public function getList()
    {
        $where = $this->getSearchWhere($this->searchs);
        //获取查询的记录数
        $total = DB::table("roles as role")->whereRaw($where)->where("flag", 0)->count();
        //要查询的字段
        $select = [
            'role.id', 'role.name', 'role.created_at'
        ];
        $creator = DB::raw("u.name as creator");
        array_push($select, $creator);
        //获取查询结果
        $sortField = "id";
        $sSortDir = "asc";
        $rows = DB::table("roles as role")
            ->leftJoin("users as u", 'u.id', '=', 'role.creator')
            ->whereRaw($where)->where("role.flag", 0)
            ->orderBy($sortField, $sSortDir)->take($this->iDisplayLength)
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
        $roleName = isset($searchs["roleName"]) ? trim($searchs["roleName"])
            : (isset($this->allInput["roleName"]) ? trim($this->allInput["roleName"]) : "");//合同号
        if (!empty($roleName)) {
            array_push($where, "role.name like '%$roleName%'");
        }
        $where = implode(" and ", $where);
        if (empty($where)) {
            return $sql;
        }
        return $where;
    }
}