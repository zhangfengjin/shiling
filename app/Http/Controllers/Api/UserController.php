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
        'userName' => 'required|max:60'
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
        /*$validate = Validator::make($input, $this->basicValidator);
        if ($validate->fails()) {
            return DataStandard::getStandardData($validate->errors(),config("validator.100"), 100);
        }*/
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

    public function reset(Request $request)
    {
        $input = $request->all();
        $validate = Validator::make($input, [
            'password' => 'required',
            'confirm' => 'required',
        ]);
        if ($validate->fails()) {
            return DataStandard::getStandardData($validate->errors(), config("validator.100"), 100);
        }
        $password = $request->input('password');
        $confirm = $request->input('confirm');
        if ($password == $confirm) {
            $user = $request->get("user");
            $userService = new UserService();
            $userService->resetPWD("id", $user["uid"], $password);
            return DataStandard::getStandardData();
        }
        return DataStandard::getStandardData([], config("validator.121"), 121);
    }

}
