<?php

namespace App\Http\Controllers\Home;

use App\Http\Services\AreaService;
use App\Http\Services\MeetService;
use App\Http\Services\UploadService;
use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MeetController extends HomeController
{
    /**
     * @return
     */
    public function index()
    {
        //
        $areaService = new AreaService();
        $provinces = $areaService->getArea("province");
        $cities = $areaService->getArea("city");
        $areas = $areaService->getArea();
        $pages = [
            "provinces" => $provinces,
            "cities" => $cities,
            "areas" => $areas
        ];
        return view("meets.meet")->with($pages);
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
        $meetService = new MeetService($request);
        $meet = $meetService->create($input);
        if ($meet) {
            return DataStandard::getStandardData();
        }
        return DataStandard::getStandardData([], config('validator.621'), 621);
    }

    /**
     * @param Request $request
     * @param $meetId
     * @return array
     */
    public function show(Request $request, $meetId)
    {
        $meetService = new MeetService($request);
        $meet = $meetService->show($meetId);
        return DataStandard::getStandardData($meet);
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

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $meetService = new MeetService($request);
        $ret = $meetService->update($input, $id);
        if ($ret) {
            return DataStandard::getStandardData();
        }
        return DataStandard::getStandardData([], config('validator.621'), 621);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function cancel(Request $request, $meetId)
    {
        $meetService = new MeetService($request);
        $ret = $meetService->cancel($meetId);
        if ($ret) {
            return DataStandard::getStandardData();
        }
        return DataStandard::getStandardData([], config('validator.701'), 701);
    }

    /**
     * 通知
     * @param Request $request
     * @param $meetId
     * @return array
     */
    public function notify(Request $request, $meetId)
    {
        $input = $request->all();
        $meetService = new MeetService($request);
        $meetService->notify($input, $meetId);
        return DataStandard::getStandardData();
    }

    /**
     *  上传会议相关图片
     * @param Request $request
     * @return array
     */
    public function upload(Request $request)
    {
        $upload = new UploadService();
        $action = $_GET ['action'];
        $file = $upload->uploadfile($action);
        return json_encode($file);
    }


    public function getList(Request $request)
    {
        $meetService = new MeetService($request);
        $list = $meetService->getList();
        return DataStandard::printStandardData($list);
    }
}
