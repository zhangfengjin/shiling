<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\UserService;
use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $basicValidator = [
        'username' => 'required|max:60',
        'password' => 'required|min:6|max:20',
        'email' => 'required|max:100',
    ];

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
     * 更新
     * @param Request $request
     * @param $userId
     * @return array
     */
    public function update(Request $request, $userId)
    {
        $input = $request->all();
        $validate = Validator::make($input, $this->basicValidator);
        if ($validate->fails()) {
            return DataStandard::getStandardData($validate->errors(), "参数输入错误", 10210);
        }
        $userService = new UserService();
        $userService->update($input, $userId);
        return DataStandard::getStandardData();
    }

    public function getList(Request $request)
    {
        $userService = new UserService($request);
        $list = $userService->getList();
        return DataStandard::printStandardData($list);
    }
}
