<?php

namespace App\Http\Controllers\Home;

use App\Http\Services\AreaService;
use App\Http\Services\MeetUserService;
use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MeetUserController extends HomeController
{

    /**
     * @return
     */
    public function index()
    {
        $areaService = new AreaService();
        $provinces = $areaService->getArea("province");
        $cities = $areaService->getArea("city");
        $areas = $areaService->getArea();
        $pages = [
            "provinces" => $provinces,
            "cities" => $cities,
            "areas" => $areas,
            "status" => []
        ];

        return view("meets.meetuser")->with($pages);
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

    }

    /**
     * @param Request $request
     * @param $meetId
     * @return array
     */
    public function show(Request $request, $muId)
    {
        $meetUserService = new MeetUserService($request);
        $meet = $meetUserService->show($muId);
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
        $meetUserService = new MeetUserService($request);
        $ret = $meetUserService->update($input, $id);
        return DataStandard::getStandardData();
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

    public function getList(Request $request)
    {
        $meetService = new MeetUserService($request);
        $list = $meetService->getList();
        return DataStandard::printStandardData($list);
    }

    public function export(Request $request)
    {
        $meetService = new MeetUserService($request);
        $meetService->export($request['type']);
    }
}
