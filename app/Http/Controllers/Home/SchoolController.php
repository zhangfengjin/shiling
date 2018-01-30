<?php

namespace App\Http\Controllers\Home;

use App\Http\Services\AreaService;
use App\Http\Services\SchoolService;
use App\Http\Services\UploadService;
use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class SchoolController extends Controller
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
            $len = count($rows);
            if ($len >= 2) {
                for ($idx = 1; $idx < $len; $idx++) {

                }
            }
        }
    }
}
