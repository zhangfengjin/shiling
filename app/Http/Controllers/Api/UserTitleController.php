<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\DictService;
use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserTitleController extends Controller
{
    //
    public function getList(Request $request)
    {
        $dictService = new DictService();
        $list = $dictService->getDictByType("user_title");
        return DataStandard::printStandardData($list);
    }
}
