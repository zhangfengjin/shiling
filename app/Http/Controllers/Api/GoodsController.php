<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\DictService;
use App\Http\Services\GoodService;
use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodsController extends Controller
{
    //

    public function getList(Request $request)
    {
        $goodService = new GoodService($request);
        $list = $goodService->getList();
        return DataStandard::printStandardData($list);
    }

    public function show(Request $request, $meetId)
    {
        $goodService = new GoodService($request);
        $goods = $goodService->show($meetId);
        return DataStandard::getStandardData($goods);
    }

    public function getGoodsType(Request $request)
    {
        $dictService = new DictService($request);
        $dict = $dictService->getDictByType("good_type");
        return DataStandard::getStandardData($dict);
    }
}
