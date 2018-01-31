<?php

namespace App\Http\Controllers\Home;

use App\Http\Services\DictService;
use App\Http\Services\RoleService;
use App\Http\Services\SchoolService;
use App\Http\Services\UploadService;
use App\Http\Services\UserService;
use App\Http\Services\VerifyService;
use App\Utils\DataEnum;
use App\Utils\DataStandard;
use App\Utils\RegHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends HomeController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $roleService = new RoleService();
        $roles = $roleService->getRoleEnum();//获取角色

        $dictService = new DictService();
        $titles = $dictService->getDictByType("user_title");//用户职级
        $courses = $dictService->getDictByType("course");
        $grades = $dictService->getDictByType("grade");

        $schoolService = new SchoolService();
        $schools = $schoolService->getSchoolEnum();

        $pages = [
            "roles" => $roles,
            "titles" => $titles,
            "courses" => $courses,
            "grades" => $grades,
            "schools" => $schools,
        ];
        return view("account.user")->with($pages);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @param $id
     * @return array
     */
    public function show($id)
    {
        $userService = new UserService();
        $user = $userService->show($id);
        return DataStandard::getStandardData($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 更新
     * @param Request $request
     * @param $userId
     * @return array
     */
    public function update(Request $request, $userId)
    {
        $input = $request->all();
        $userService = new UserService();
        $userService->update($input, $userId);
        return DataStandard::getStandardData();
    }

    /**
     * @param $id
     * @return array
     */
    public function destroy($id)
    {
        //
        if (parent::validateAuth()) {
            $userService = new UserService();
            $userService->delete($id);
            return DataStandard::getStandardData();
        }
        return DataStandard::getStandardData([], config('validator.301'), 301);
    }

    /**
     * @param Request $request
     * @param $userId
     * @return array
     */
    public function egis(Request $request, $userId)
    {
        $input = $request->all();
        $userService = new UserService();
        $userService->egis($input, $userId);
        return DataStandard::getStandardData();
    }

    /**
     * @param Request $request
     * @param $userId
     * @return array
     */
    public function stop(Request $request, $userId)
    {
        if (parent::validateAuth()) {
            $code = $request->input('code');
            $account = config('app.stop_tel');
            $verifyService = new VerifyService();
            $msg = $verifyService->codeValidate($code, $account); // 验证手机邮箱验证码
            if (!$msg) { // 返回空字符串表示验证通过
                $userService = new UserService();
                $userService->delete($userId);
                return DataStandard::getStandardData();
            }
            return DataStandard::getStandardData([], config("validator.119"), 119);
        }
        return DataStandard::getStandardData([], config('validator.301'), 301);
    }


    public function getList(Request $request)
    {
        $userService = new UserService($request);
        $list = $userService->getList();
        return DataStandard::printStandardData($list);
    }

    /**
     * 导出
     * @param Request $request
     */
    public function export(Request $request)
    {
        if (parent::validateAuth()) {
            $userService = new UserService($request);
            $userService->export();
        }
        return DataStandard::getStandardData([], config('validator.301'), 301);
    }

    /**
     * @param Request $request
     */
    public function import(Request $request)
    {
        $action = $request->input('action');
        $uploadService = new UploadService();
        //上传文件
        $file = $uploadService->uploadfile($action);
        //读取文件内容 导入数据
        if ($file) {
            $filePath = public_path() . $file["url"];
            $reader = Excel::selectSheets('user')->load($filePath)->getSheet(0);
            $rows = $reader->toArray();
            $titles = [
                "username" => "姓名",
                "phone" => "手机号",
                "email" => "邮箱",
                "subject" => "科目",
                "grade" => "年级",
                "unum" => "继教号",
                "age" => "年龄",
                "seniority" => "工龄",
                "role" => "角色",
                "sex" => "性别",
                "user_title" => "职级",
                "address" => "地址",
                "school" => "学校",
                "province" => "省",
                "city" => "市",
                "area" => "县区"
            ];
            $userService = new UserService();
            $len = count($rows);
            if ($len >= 2) {
                $roleService = new RoleService();
                $roles = $roleService->getRoleEnum();
                $dictService = new DictService();
                $courses = $dictService->getDictByType("course");
                $schoolService = new SchoolService();
                $schools = $schoolService->getSchoolEnum();
                $sexs = DataEnum::getSex();
                $userTitles = $dictService->getDictByType("user_title");
                foreach ($titles as $key => &$title) {
                    if (($key = array_search($title, $rows[0])) === FALSE) {
                        return DataStandard::getStandardData([], config("validator.603"), 603);
                    }
                    $title = $key;
                }
                $importFaileds = [];
                for ($idx = 1; $idx < $len; $idx++) {
                    //学校
                    $schoolName = $rows[$idx][$titles["school"]];
                    $province = $rows[$idx][$titles["province"]];
                    $city = $rows[$idx][$titles["city"]];
                    $area = $rows[$idx][$titles["area"]];
                    $schoolId = 0;
                    if (!empty($schoolName)) {
                        foreach ($schools as $school) {
                            if ($school->name == $schoolName &&
                                $school->province_name == $province &&
                                $school->city_name == $city &&
                                $school->area_name == $area
                            ) {
                                $schoolId = $school->id;
                            }
                        }
                    }
                    $age = $rows[$idx][$titles["age"]];
                    $seniority = $rows[$idx][$titles["seniority"]];
                    $address = $rows[$idx][$titles["address"]];
                    $unum = $rows[$idx][$titles["unum"]];
                    $userTitleName = $rows[$idx][$titles["user_title"]];
                    $userTitleId = 0;
                    if (!empty($userTitleName)) {
                        foreach ($userTitles as $userTitle) {
                            if ($userTitle->value == $userTitleName) {
                                $userTitleId = $userTitle->id;
                            }
                        }
                    }
                    //性别
                    $sexName = $rows[$idx][$titles["sex"]];
                    $sexId = 0;
                    if (!empty($sexName)) {
                        foreach ($sexs as $key => $sex) {
                            if ($sex == $sexName) {
                                $sexId = $key;
                            }
                        }
                    }

                    //组织角色
                    $roleId = 2;//通过导入的用户 角色默认为普通老师
                    $roleName = $rows[$idx][$titles["role"]];
                    if (empty($roleName)) {
                        return DataStandard::getStandardData(["从第" . $idx . "行开始导入失败"], config("validator.605"), 605);
                    } else {
                        foreach ($roles as $role) {
                            if ($role->name == $rows[$idx][$titles["role"]]) {
                                $roleId = $role->id;
                            }
                        }
                    }
                    //组织科目
                    $courseIds = [];
                    $subjects = explode(";", trim($rows[$idx][$titles["subject"]]));
                    foreach ($subjects as $subject) {
                        foreach ($courses as $course) {
                            if ($course->value == $subject) {
                                $courseIds[]["subjectId"] = $course->id;
                            }
                        }
                    }
                    //组织年级
                    $dictGrades = $dictService->getDictByType("grade");
                    $grades = explode(";", trim($rows[$idx][$titles["grade"]]));
                    $gradeIds = [];
                    foreach ($grades as $grade) {
                        foreach ($dictGrades as $dict) {
                            if ($dict->value == $grade) {
                                $gradeIds[]["gradeId"] = $dict->id;
                            }
                        }
                    }
                    if ($courseIds) {
                        $input = [];
                        $userName = $rows[$idx][$titles["username"]];
                        $tel = $rows[$idx][$titles["phone"]];
                        $email = $rows[$idx][$titles["email"]];
                        $password = 111111;//密码默认为111111
                        if (!empty($tel)) {
                            if (RegHelper::validateTel($tel)) {
                                if ($userService->uniqueTel($tel) > 0) {
                                    return DataStandard::getStandardData(["从第" . $idx . "行开始导入失败"], config("validator.117"), 117);
                                }
                            } else {
                                return DataStandard::getStandardData(["从第" . $idx . "行开始导入失败"], config("validator.114"), 114);
                            }
                        }
                        if (!empty($email)) {
                            if (RegHelper::validateEmail($email)) {
                                if ($userService->uniqueEmail($email) > 0) {
                                    return DataStandard::getStandardData(["从第" . $idx . "行开始导入失败"], config("validator.116"), 116);
                                }
                            } else {
                                return DataStandard::getStandardData(["从第" . $idx . "行开始导入失败"], config("validator.115"), 115);
                            }
                        }
                        $input["password"] = $password;
                        $input["username"] = $userName;
                        $input["account"] = $input["phone"] = $tel;
                        $input["email"] = $email;
                        $input["subject"] = $courseIds;
                        $input["subject"] = $courseIds;
                        $input["grade"] = $gradeIds;
                        $input["role_id"] = $roleId;
                        $input["sex"] = $sexId;
                        $input["age"] = $age;
                        $input["seniority"] = $seniority;
                        $input["user_title_id"] = $userTitleId;
                        $input["address"] = $address;
                        $input["school_id"] = $schoolId;
                        $input["unum"] = $unum;
                        $userService->create($input);
                    } else {
                        return DataStandard::getStandardData(["从第" . $idx . "行开始导入失败"], config("validator.604"), 604);
                    }
                }
                return DataStandard::getStandardData();
            }
            return DataStandard::getStandardData([], config("validator.602"), 602);
        }
        return DataStandard::getStandardData([], config("validator.601"), 601);
    }
}
