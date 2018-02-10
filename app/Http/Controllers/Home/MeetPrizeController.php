<?php

namespace App\Http\Controllers\Home;

use App\Http\Services\MeetPrizeService;
use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MeetPrizeController extends HomeController
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

        return view("meets.meetprizeuser")->with($pages);
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
        $input=$request->all();
        $meetUserService = new MeetPrizeService($request);
        $meet = $meetUserService->create($input);
        return DataStandard::getStandardData($meet);
    }

    /**
     * @param Request $request
     * @param $meetId
     * @return array
     */
    public function show(Request $request, $muId)
    {
        $meetUserService = new MeetPrizeService($request);
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
        $meetUserService = new MeetPrizeService($request);
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
        $meetService = new MeetPrizeService($request);
        $list = $meetService->getList();
        return DataStandard::printStandardData($list);
    }
}
