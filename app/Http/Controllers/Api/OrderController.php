<?php

namespace App\Http\Controllers\Api;

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
}
