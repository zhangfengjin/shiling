<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\NewsService;
use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    //

    public function getList(Request $request)
    {
        $newService = new NewsService($request);
        $list = $newService->getList();
        return DataStandard::printStandardData($list);
    }
}
