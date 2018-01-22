<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/1/11
 * Time: 15:58
 */

namespace App\Http\Services;


use App\User;
use App\Utils\DataStandard;
use App\Utils\WyIMHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class UserService extends CommonService
{
    /**
     * 判断email是否存在
     *
     * @param unknown $email
     */
    public function uniqueEmail($email)
    {
        return User::where("email", '=', $email)->count();
    }

    /**
     * 判断电话号码是否存在
     *
     * @param unknown $tel
     */
    public function uniqueTel($tel)
    {
        return User::where("tel", '=', $tel)->count();
    }


    /**
     * 重置密码
     *
     * @param unknown $account
     * @param unknown $reqaccount
     * @param unknown $password
     * @return unknown
     */
    public function resetPwd($account, $reqaccount, $password)
    {
        $user = User::where($account, '=', $reqaccount)->first();
        $user->password = bcrypt($password);
        $user->save();
        return $user;
    }

    /**
     * 添加用户
     * @param $input
     */
    public function create($input)
    {
        $input['im_token'] = "";
        $wyIM = new WyIMHelper();
        $ret = $wyIM->createUserId($input['phone']);
        if ($ret['code'] === 200) {
            $input['im_token'] = $ret["info"]["token"];
        } else {
            Log::info(json_encode($ret));
        }
        $user = new User();
        $user->tel = $input["phone"];
        $user->password = bcrypt($input["password"]);
        $user->im_token = $input["im_token"];
        $user->save();
        return $user;
    }

    /**
     * @param $input
     * @param $userId
     * @return mixed
     */
    public function update($input, $userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->name = $input["name"];
            $user->save();
            return $user;
        }
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function show($userId)
    {
        $user = User::find($userId);
        return $user;
    }

    /**
     * 获取列表
     * @return array
     */
    public function getList()
    {
        $where = $this->getSearchWhere($this->searchs);
        //获取查询的记录数
        $total = User::whereRaw($where)->count();
        //要查询的字段
        $select = [
            'u.id', 'u.name', 'u.tel', 'u.email'
        ];
        $roleName = DB::raw("role.name as role_name");
        array_push($select, $roleName);
        //获取查询结果
        $sortField = "id";
        $sSortDir = "asc";
        $rows = DB::table("users as u")
            ->join("roles as role", "role.id", "=", "u.role_id")
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
        $roleName = isset($searchs["role_name"]) ? trim($searchs["role_name"])
            : (isset($this->allInput["role_name"]) ? trim($this->allInput["role_name"]) : "");//合同号
        if (!empty($roleName)) {
            array_push($where, "name like '%$roleName%'");
        }
        $where = implode(" and ", $where);
        if (empty($where)) {
            return $sql;
        }
        return $where;
    }

    /**
     * 导出
     * @param $searchs
     */
    public function export()
    {
        //构建查询条件
        $where = $this->getSearchWhere($this->searchs);
        //要查询的字段
        $select = [
            'ID', 'name'
        ];
        //获取查询结果
        $sortField = "id";
        $sSortDir = "asc";//DB::table(" as pdb")
        $rows = User::whereRaw($where)->orderBy($sortField, $sSortDir)
            ->get($select)->toArray();
        foreach ($rows as & $row) {

        }
        //导出Excel的表头
        $title = [
            'id', '姓名'
        ];
        array_unshift($rows, $title);
        $excelName = "User_List_" . date("Y-m-d-H-i-s");
        Excel::create($excelName, function ($excel) use ($rows) {
            $excel->sheet('user_list', function ($sheet) use ($rows) {
                /*$sheet->getStyle('B')->getNumberFormat()
                    ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);*/
                $sheet->rows($rows);
            });
        })->export('xlsx');
    }

    public function import(){


    }
}