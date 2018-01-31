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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
