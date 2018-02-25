<?php

namespace App\Http\Controllers\Home;

use App\Http\Services\OrderService;
use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends HomeController
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

        return view("orders.order")->with($pages);
    }

    public function getList(Request $request)
    {
        $goodService = new OrderService($request);
        $goods = $goodService->getList();
        return DataStandard::getStandardData($goods);
    }
}
