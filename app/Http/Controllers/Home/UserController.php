<?php

namespace App\Http\Controllers\Home;

use App\Http\Services\DictService;
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
        return view("account.user");
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
                "subject" => "科目"
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
                    $course_id = 0;
                    foreach ($courses as $course) {
                        if ($course->value == trim($rows[$idx][$titles["subject"]])) {
                            $course_id = $course->id;
                        }
                    }
                    if ($course_id) {
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
                        $input["username"] = $userName;
                        $input["account"] = $input["phone"] = $tel;
                        $input["email"] = $email;
                        $input["course_id"] = $course_id;
                        $input["password"] = $password;
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
