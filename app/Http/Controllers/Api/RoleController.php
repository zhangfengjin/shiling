<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\RoleService;
use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    //
    public function getList(Request $request)
    {
        $schoolService = new RoleService($request);
        $list = $schoolService->getList();
        return DataStandard::printStandardData($list);
    }
}
