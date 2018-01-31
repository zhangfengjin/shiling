<?php

namespace App\Http\Controllers\Home;

use App\Http\Services\RoleService;
use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends HomeController
{
    //

    public function index(Request $request)
    {
        return view("roles.role");
    }


    public function getList(Request $request)
    {
        $roleService = new RoleService($request);
        $list = $roleService->getList();
        return DataStandard::printStandardData($list);
    }
}
