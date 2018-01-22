<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\DictService;
use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {
        $dictService = new DictService();
        $list = $dictService->getDictByType("course");
        return DataStandard::printStandardData($list);
    }
}
