<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\SchoolService;
use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SchoolController extends Controller
{
    //
    public function getList(Request $request)
    {
        $schoolService = new SchoolService($request);
        $list = $schoolService->getList();
        return DataStandard::printStandardData($list);
    }
}
