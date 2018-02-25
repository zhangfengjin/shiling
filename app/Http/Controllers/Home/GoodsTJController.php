<?php

namespace App\Http\Controllers\Home;

use App\Http\Services\GoodService;
use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodsTJController extends HomeController
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = [
            "goodsTypes" => []
        ];

        return view("goods.goodstj")->with($pages);
    }

    public function getList(Request $request)
    {
        $goodService = new GoodService($request);
        $goods = $goodService->getGoodsTjList();
        return DataStandard::getStandardData($goods);
    }
}
