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
        $orderService = new OrderService($request);
        $order = $orderService->getList();
        return DataStandard::getStandardData($order);
    }

    public function show(Request $request, $orderId)
    {
        $orderService = new OrderService($request);
        $order = $orderService->show($orderId);
        return DataStandard::getStandardData($order);
    }

    public function update(Request $request, $orderId)
    {
        $input = $request->all();
        $orderService = new OrderService($request);
        $ret = $orderService->update($input, $orderId);
        if ($ret) {
            return DataStandard::getStandardData();
        }
    }

    public function export(Request $request)
    {
        $orderService = new OrderService($request);
        $orderService->export();
    }
}
