<?php

namespace App\Http\Controllers\Home;

use App\Http\Services\DictService;
use App\Http\Services\RoleService;
use App\Http\Services\SchoolService;
use App\Http\Services\UploadService;
use App\Http\Services\UserService;
use App\Utils\DataStandard;
use App\Utils\RegHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
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
        $userService = new UserService();
        $userService->delete($id);
        return DataStandard::getStandardData();
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
        $userService = new UserService($request);
        $userService->export();
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
                "grade" => "年级"
            ];
            $userService = new UserService();
            $len = count($rows);
            if ($len >= 2) {
                $dictService = new DictService();
                $courses = $dictService->getDictByType("course");
                $idx = 0;
                foreach ($titles as $key => &$title) {
                    if (!isset($rows[0][$idx]) || $title != $rows[0][$idx]) {
                        return DataStandard::getStandardData([], config("validator.603"), 603);
                    }
                    $title = $idx;
                    $idx++;
                }
                $importFaileds = [];
                for ($idx = 1; $idx < $len; $idx++) {
                    //科目
                    $courseIds = [];
                    $subjects = explode(";", trim($rows[$idx][$titles["subject"]]));
                    foreach ($subjects as $subject) {
                        foreach ($courses as $course) {
                            if ($course->value == $subject) {
                                $courseIds[]["subjectId"] = $course->id;
                            }
                        }
                    }
                    //年级
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
                        $roleId = 2;//通过导入的用户 角色统统为普通老师
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
