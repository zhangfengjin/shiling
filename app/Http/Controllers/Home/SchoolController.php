<?php

namespace App\Http\Controllers\Home;

use App\Http\Services\AreaService;
use App\Http\Services\SchoolService;
use App\Http\Services\UploadService;
use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class SchoolController extends HomeController
{
    //
    public function index()
    {
        $areaService = new AreaService();
        $provinces = $areaService->getArea("province");
        $cities = $areaService->getArea("city");
        $areas = $areaService->getArea();
        $pages = [
            "provinces" => $provinces,
            "cities" => $cities,
            "areas" => $areas
        ];
        return view("schools.school")->with($pages);
    }


    public function show(Request $request, $schoolId)
    {
        $schoolService = new SchoolService();
        $school = $schoolService->show($schoolId);
        return DataStandard::getStandardData($school);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $schoolService = new SchoolService();
        $school = $schoolService->create($input);
        if ($school) {
            return DataStandard::getStandardData();
        }
        return DataStandard::getStandardData([], config('validator.621'), 621);
    }

    public function update(Request $request, $schoolId)
    {
        $input = $request->all();
        $schoolService = new SchoolService();
        $schoolService->update($input, $schoolId);
        return DataStandard::getStandardData();
    }

    public function getList(Request $request)
    {
        $schoolService = new SchoolService($request);
        $list = $schoolService->getList();
        return DataStandard::printStandardData($list);
    }

    public function import(Request $request)
    {
        $action = $request->input('action');
        $uploadService = new UploadService();
        //上传文件
        $file = $uploadService->uploadfile($action);
        //读取文件内容 导入数据
        if ($file) {
            $filePath = public_path() . $file["url"];
            $reader = Excel::selectSheets('user')->load($filePath)->getSheet(0);
            $rows = $reader->toArray();
            $titles = [
                "school" => "学校",
                "province" => "省",
                "city" => "市",
                "area" => "县区"
            ];
            $schoolService = new SchoolService();
            $areaService = new AreaService();
            $areas = $areaService->getArea();
            $len = count($rows);
            if ($len >= 2) {
                foreach ($titles as $key => &$title) {
                    if (($key = array_search($title, $rows[0])) === FALSE) {
                        return DataStandard::getStandardData([], config("validator.603"), 603);
                    }
                    $title = $key;
                }
                for ($idx = 1; $idx < $len; $idx++) {
                    $schoolName = $rows[$idx][$titles["school"]];
                    $province = $rows[$idx][$titles["province"]];
                    $city = $rows[$idx][$titles["city"]];
                    $area = $rows[$idx][$titles["area"]];
                    $areaId = 0;
                    foreach ($areas as $val) {
                        if ($val->area_name == $area &&
                            $val->province_name == $province &&
                            $val->city_name == $city
                        ) {
                            $areaId = $val->id;
                            break;
                        }
                    }
                    if ($areaId) {
                        $input["schoolName"] = $schoolName;
                        $input["area_id"] = $areaId;
                        $schoolService = new SchoolService();
                        $school = $schoolService->create($input);
                        if ($school) {
                            continue;
                        }
                        return DataStandard::getStandardData([], config('validator.621'), 621);
                    }
                    Log::info($areaId);
                    return DataStandard::getStandardData(["从第" . $idx . "行开始导入失败"], config("validator.622"), 622);
                }
            }
        }
    }
}
