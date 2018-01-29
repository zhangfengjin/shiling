<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/1/11
 * Time: 15:58
 */

namespace App\Http\Services;


use App\Models\UserCourse;
use App\Models\UserGrade;
use App\User;
use App\Utils\DataStandard;
use App\Utils\WyIMHelper;
use Illuminate\Http\Request;
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
        $where = [
            "email" => $email,
            "flag" => 0
        ];
        return User::where($where)->count();
    }

    /**
     * 判断电话号码是否存在
     *
     * @param unknown $tel
     */
    public function uniqueTel($tel)
    {
        $where = [
            "phone" => $tel,
            "flag" => 0
        ];
        return User::where($where)->count();
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
        $where = [
            "$account" => $reqaccount,
            "flag" => 0
        ];
        $user = User::where($where)->first();
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
        $ret = $wyIM->createUserId($input['account']);
        if ($ret['code'] === 200) {
            $input['im_token'] = $ret["info"]["token"];
        } else {
            Log::info(json_encode($ret));
        }
        DB::beginTransaction();
        try {
            $user = new User();
            $keys = [
                "phone" => "phone",
                "email" => "email",
                "username" => "name",
                "role_id" => "role_id",
                "unum" => "unum",
                "age" => "age",
                "sex" => "sex",
                "seniority" => "seniority",
                "user_title_id" => "user_title_id",
                "school_id" => "school_id",
                "address" => "address",
                "status" => "status"
            ];
            foreach ($keys as $key => $val) {
                if (isset($input[$key])) {
                    $user->$val = $input[$key];
                }
            }
            $user->password = bcrypt($input["password"]);
            $user->im_token = $input["im_token"];
            $user->save();
            $userId = $user->id;
            if (isset($input['subject'])) {
                $subjects = [];
                foreach ($input['subject'] as $subject) {
                    $subjects[] = [
                        'user_id' => $userId, 'course_id' => $subject['subjectId']
                    ];
                }
                if (!empty($subjects)) {
                    DB::table('user_courses')->insert($subjects);
                }
            }
            if (isset($input['grade'])) {
                $grades = [];
                foreach ($input['grade'] as $grade) {
                    $grades[] = [
                        'user_id' => $userId, 'grade_id' => $grade['gradeId']
                    ];
                }
                if (!empty($subjects)) {
                    DB::table('user_grades')->insert($grades);
                }
            }
            DB::commit();
            return $user;
        } catch (\Exception $ex) {
            DB::rollback();
            throw $ex;
        }
    }

    /**
     * @param $input
     * @param $userId
     * @return mixed
     */
    public function update($input, $userId)
    {
        $where = [
            "id" => $userId,
            "flag" => 0
        ];
        $user = User::where($where)->find($userId);
        if ($user) {
            DB::beginTransaction();
            try {
                $user->name = $input["userName"];
                /*$user->email = $input["email"];*/
                $user->unum = $input["unum"];
                $user->age = $input["age"];
                $user->seniority = $input["seniority"];
                $user->role_id = $input["roles"];
                $user->sex = $input["sex"];
                $user->user_title_id = $input["userTitle"];
                $user->school_id = $input["school"];
                $user->address = $input["address"];
                $user->save();

                $linkWhere = [
                    "user_id" => $userId,
                    "flag" => 0
                ];
                //科目
                $inputCourses = $input['courses'];
                if (empty($inputCourses)) {
                    UserCourse::where("user_id", $userId)->delete();
                } else {
                    $courses = UserCourse::where($linkWhere)->distinct()->get(["course_id"]);
                    $delCourses = [];
                    foreach ($courses as $course) {
                        $courseId = $course->course_id;
                        if (($key = array_search($courseId, $inputCourses)) === FALSE) {
                            array_push($delCourses, $courseId);//需要删除的
                        } else {
                            array_splice($inputCourses, $key, 1); //原先存在且现在仍属于该科目的，不做处理，估删除
                        }
                    }
                    if ($inputCourses) {//剩余的即为需要插入的
                        $addCourses = [];
                        foreach ($inputCourses as $courseId) {
                            $addCourses[] = [
                                "course_id" => $courseId,
                                "user_id" => $userId
                            ];
                        }
                        UserCourse::insert($addCourses);//插入原先不存在的科目
                    }
                    if ($delCourses) {//删除
                        UserCourse::whereIn("course_id", $delCourses)->where("user_id", $userId)->delete();
                    }
                }

                //年级
                $inputGrades = $input['grades'];
                if (empty($inputGrades)) {
                    UserGrade::where("user_id", $userId)->delete();
                } else {
                    $grades = UserGrade::where($linkWhere)->distinct()->get(["grade_id"]);
                    $delGrades = [];
                    foreach ($grades as $grade) {
                        $gradeId = $grade->grade_id;
                        if (($key = array_search($gradeId, $inputGrades)) === FALSE) {
                            array_push($delGrades, $gradeId);//需要删除的
                        } else {
                            array_splice($inputGrades, $key, 1); //原先存在且现在仍属于该科目的，不做处理，估删除
                        }
                    }
                    if ($inputGrades) {//剩余的即为需要插入的
                        $addGrades = [];
                        foreach ($inputGrades as $gradeId) {
                            $addGrades[] = [
                                "grade_id" => $gradeId,
                                "user_id" => $userId
                            ];
                        }
                        UserGrade::insert($addGrades);//插入原先不存在的科目
                    }
                    if ($delGrades) {//删除
                        UserGrade::whereIn("grade_id", $delGrades)->where("user_id", $userId)->delete();
                    }
                }

                DB::commit();
            } catch (\Exception $ex) {
                DB::rollback();
                throw $ex;
            }
            return $user;
        }
    }

    /**
     * @param $input
     * @param $userId
     */
    public function egis($input, $userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->status = 0;
            $user->save();
        }
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function show($userId)
    {
        $where = [
            "user.id" => $userId,
            "user.flag" => 0
        ];
        $select = [
            "user.id", "user.name", "phone", "email", "role_id", "unum", "sex",
            "age", "seniority", "user_title_id", "address", "school_id"
        ];
        $schoolName = DB::raw("sch.name as school_name");
        $roleName = DB::raw("role.name as role_name");
        $userTitleName = DB::raw("dict.value as user_title_name");
        array_push($select, $schoolName, $roleName, $userTitleName);
        $user = DB::table("users as user")
            ->leftJoin("schools as sch", "sch.id", "=", "user.school_id")
            ->leftJoin("roles as role", "role.id", "=", "user.role_id")
            ->leftJoin("dicts as dict", "dict.id", "=", "user.user_title_id")
            ->where($where)->get($select)->first();
        if ($user) {
            $where = [
                "user_id" => $userId,
                "user.flag" => 0
            ];
            //科目
            $select = [
                "course_id"
            ];
            $courseName = DB::raw("dict.value as course_name");
            array_push($select, $courseName);
            $user->courses = DB::table("user_courses as user")
                ->join("dicts as dict", "dict.id", "=", "user.course_id")
                ->where($where)->distinct()->get($select);
            //年级
            $select = [
                "grade_id"
            ];
            $gradeName = DB::raw("dict.value as grade_name");
            array_push($select, $gradeName);
            $user->grades = DB::table("user_grades as user")
                ->join("dicts as dict", "dict.id", "=", "user.grade_id")
                ->where($where)->distinct()->get($select);
            return $user;
        }
        return [];
    }


    /**
     * @param $userIds
     * @return bool
     */
    public function delete($userIds)
    {
        $ids = array_filter(explode(",", $userIds));
        if ($userIds) {
            $updateInfo = [
                'flag' => 1
            ];
            DB::beginTransaction();
            try {
                User::whereIn("id", $ids)->update($updateInfo);
                UserCourse::whereIn("id", $ids)->update($updateInfo);
                UserGrade::whereIn("id", $ids)->update($updateInfo);
                DB::commit();
            } catch (\Exception $ex) {
                DB::rollback();
                throw $ex;
            }
        }
        return true;
    }

    /**
     * 获取列表
     * @return array
     */
    public function getList()
    {
        $where = $this->getSearchWhere($this->searchs);
        //获取查询的记录数
        $total = DB::table("users as u")->whereRaw($where)->count();
        //要查询的字段
        $select = [
            'u.id', 'u.name', 'u.phone', 'u.email', 'u.age', 'u.sex', 'u.unum'
        ];
        $roleName = DB::raw("role.name as roleName");
        $schoolName = DB::raw("sch.name as schoolName");
        $userTitleName = DB::raw("dict.value as userTitleName");
        $statusName = DB::raw("case when (u.flag=0 and u.status=0) then '启用' when (u.flag=0 and u.status=1) then '待审核' else '已停用' end status");
        array_push($select, $roleName, $schoolName, $userTitleName, $statusName);
        //获取查询结果
        $sortField = "id";
        $sSortDir = "asc";
        //->where("u.flag", 0)
        $rows = DB::table("users as u")
            ->join("roles as role", "role.id", "=", "u.role_id")
            ->leftJoin("schools as sch", "sch.id", "=", "u.school_id")
            ->leftJoin("dicts as dict", "dict.id", "=", "u.user_title_id")
            ->whereRaw($where)
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
        $userName = isset($searchs["userName"]) ? trim($searchs["userName"])
            : (isset($this->allInput["userName"]) ? trim($this->allInput["userName"]) : "");//姓名
        $phone = isset($searchs["phone"]) ? trim($searchs["phone"])
            : (isset($this->allInput["phone"]) ? trim($this->allInput["phone"]) : "");//phone
        $email = isset($searchs["email"]) ? trim($searchs["email"])
            : (isset($this->allInput["email"]) ? trim($this->allInput["email"]) : "");
        $unum = isset($searchs["unum"]) ? trim($searchs["unum"])
            : (isset($this->allInput["unum"]) ? trim($this->allInput["unum"]) : "");//继教号
        $status = isset($searchs["status"]) ? trim($searchs["status"])
            : (isset($this->allInput["status"]) ? trim($this->allInput["status"]) : "");//status
        if (!empty($userName)) {
            array_push($where, "u.name like '%$userName%'");
        }
        if (!empty($phone)) {
            array_push($where, "u.phone like '%$phone%'");
        }
        if (!empty($email)) {
            array_push($where, "u.email like '%$email%'");
        }
        if (!empty($unum)) {
            array_push($where, "u.unum like '%$unum%'");
        }
        if (!empty($status)) {
            switch ($status) {
                case 1://启用
                    array_push($where, "u.status = 0 and u.flag=0");
                    break;
                case 2://待审核
                    array_push($where, "u.status = 1 and u.flag=0");
                    break;
                case 3:
                    //已停用
                    array_push($where, "u.flag=1");
                    break;
            }
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

    /**
     * @param $file
     */
    public function import($file)
    {

        return true;
    }
}