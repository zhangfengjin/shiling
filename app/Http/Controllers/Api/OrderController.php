<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\DictService;
use App\Http\Services\OrderService;
use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    //
    public function getList(Request $request)
    {
        $orderService = new OrderService($request);
        $list = $orderService->getList();
        return DataStandard::printStandardData($list);
    }

    public function show(Request $request, $meetId)
    {
        $orderService = new OrderService($request);
        $orders = $orderService->show($meetId);
        return DataStandard::getStandardData($orders);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $orderService = new OrderService($request);
        $order = $orderService->create($input);
        if ($order) {
            return DataStandard::getStandardData($order);
        } else {
            return DataStandard::getStandardData([], config('validator.754'), 754);
        }
    }

    public function update(Request $request, $orderId)
    {
        $input = $request->all();
        $orderService = new OrderService($request);
        $order = $orderService->update($input,$orderId);
        return DataStandard::getStandardData($order);
    }

    public function getBillUse(Request $request)
    {
        $dictService = new DictService($request);
        $dict = $dictService->getDictByType("bill_use");
        return DataStandard::getStandardData($dict);
    }
}
