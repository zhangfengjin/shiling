<?php

namespace App\Http\Controllers\Home;

use App\Http\Services\DictService;
use App\Http\Services\GoodService;
use App\Http\Services\UploadService;
use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class GoodsController extends HomeController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dict = new DictService();
        $goodsTypes = $dict->getDictByType("good_type");
        $pages = [
            "goodsTypes" => $goodsTypes
        ];

        return view("goods.goods")->with($pages);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $goodService = new GoodService($request);
        $goodService->create($input);
        return DataStandard::getStandardData();
    }

    public function show(Request $request, $id)
    {
        $goodService = new GoodService($request);
        $goods = $goodService->show($id);
        return DataStandard::getStandardData($goods);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * @param Request $request
     * @param $id
     * @return array
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $goodService = new GoodService($request);
        $goodService->update($input, $id);
        return DataStandard::getStandardData();
    }

    /**
     * @param Request $request
     * @param $id
     * @return array
     */
    public function destroy(Request $request, $id)
    {
        $goodService = new GoodService($request);
        $goodService->delete($id);
        return DataStandard::getStandardData();
    }

    public function getList(Request $request)
    {
        $goodService = new GoodService($request);
        $goods = $goodService->getList();
        return DataStandard::getStandardData($goods);
    }


    /**
     * 上传
     * @param Request $request
     * @return array
     */
    public function upload(Request $request)
    {
        $upload = new UploadService();
        $action = $_GET ['action'];

        $file = $upload->uploadfile($action);
        echo json_encode($file);
        //return DataStandard::getStandardData();
    }

}
