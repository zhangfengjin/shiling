<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/2/9
 * Time: 18:52
 */

namespace App\Http\Services;


use App\Models\MeetUser;
use App\Utils\DataStandard;
use App\Utils\HttpHelper;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class MeetUserService extends CommonService
{

    /**
     * @param $input
     * @return int
     */
    public function getMeetUser($input)
    {
        $where = [
            "meet_id" => $input['meetId'],
            "user_id" => $input['userId'],
            "flag" => 0
        ];
        return MeetUser::where($where)->whereRaw("status<>3")->count();
    }

    /**
     * @param $meetId
     * @return mixed
     */
    public function show($muId)
    {
        $select = [
            'mu.id', 'mu.meet_id', 'mu.user_id', 'mu.status',
        ];
        $inMeetStatus = DB::raw("case when mu.status=1 then '已付款' 
        when mu.status=2 then '退款中' 
        when mu.status=3 then '已退款' 
        when mu.status=4 then '已签到' 
        else '已报名' end as status_desc");
        $userName = DB::RAW("u.name as user_name");
        $meetName = DB::RAW("meet.name as meet_name");
        array_push($select, $userName, $meetName, $inMeetStatus);
        $meet = DB::table("meet_users as mu")
            ->leftJoin("meets as meet", 'meet.id', '=', 'mu.meet_id')
            ->leftJoin("users as u", 'u.id', '=', 'meet.creator')
            ->leftJoin("areas as area", 'area.id', '=', 'meet.area_id')
            ->where("mu.id", $muId)->get($select)->first();
        return $meet;
    }


    public function update($input, $id)
    {
        $ids = array_filter(explode(",", $id));
        $update = [
            "status" => $input['status']
        ];
        return MeetUser::whereIn("id", $ids)->update($update);
    }

    /**
     * @return array
     */
    public function getList()
    {
        $where = $this->getSearchWhere($this->searchs);
        //获取查询的记录数
        $total = DB::table("meet_users as mu")
            ->leftJoin("meets as meet", 'meet.id', '=', 'mu.meet_id')
            ->leftJoin("users as u", 'u.id', '=', 'mu.user_id')
            ->whereRaw($where)->where("mu.flag", 0)->count();
        //要查询的字段
        $select = [
            'mu.id', 'mu.meet_id', 'mu.user_id', 'u.phone', 'u.email', 'u.name'
        ];
        $inMeetStatus = DB::raw("case when mu.status=1 then '已付款' 
        when mu.status=2 then '退款中' 
        when mu.status=3 then '已退款' 
        when mu.status=4 then '已签到' 
        else '已报名' end as status");
        $inMeetStatusId = DB::raw("mu.status as status_id");
        $meetName = DB::raw("meet.name as meet_name");
        array_push($select, $inMeetStatus, $meetName, $inMeetStatusId);
        //获取查询结果
        $sortField = "mu.id";
        $sSortDir = "asc";
        $rows = DB::table("meet_users as mu")
            ->leftJoin("users as u", 'u.id', '=', 'mu.user_id')
            ->leftJoin("meets as meet", 'meet.id', '=', 'mu.meet_id')
            ->where("mu.flag", 0)->whereRaw($where)
            ->orderBy($sortField, $sSortDir)
            ->take($this->iDisplayLength)
            ->skip($this->iDisplayStart)->get($select);
        foreach ($rows as $row) {
            $row->id = strval($row->id);
        }
        return DataStandard::getListData($this->sEcho, $total, $rows);
    }


    public function export($type)
    {
        //构建查询条件
        $where = $this->getSearchWhere($this->searchs);
        //要查询的字段
        $select = [
            'u.name', 'u.phone', 'u.email',
        ];
        $inMeetStatus = DB::raw("case when mu.status=1 then '已付款' 
        when mu.status=2 then '退款中' 
        when mu.status=3 then '已退款' 
        when mu.status=4 then '已签到' 
        else '已报名' end as status");
        $meetName = DB::raw("meet.name as meet_name");
        array_push($select, $inMeetStatus, $meetName);
        //获取查询结果
        $sortField = "mu.id";
        $sSortDir = "asc";
        $rows = DB::table("meet_users as mu")
            ->leftJoin("users as u", 'u.id', '=', 'mu.user_id')
            ->leftJoin("meets as meet", 'meet.id', '=', 'mu.meet_id')
            ->where("mu.flag", 0)->whereRaw($where)
            ->orderBy($sortField, $sSortDir)
            ->take($this->iDisplayLength)
            ->skip($this->iDisplayStart)->get($select)->toArray();
        //导出Excel的表头
        $title = [
            '姓名', '手机号', '邮箱', '状态', '会议名称'
        ];
        switch ($type) {
            case "excel":
                $this->exportExcel($rows, $title);
                break;
            case "word":
                $this->exportWord($rows, $title);
                break;
        }
    }

    private function exportExcel($rows, $title)
    {
        $rows = json_decode(json_encode($rows), true);
        array_unshift($rows, $title);
        $excelName = "MeetUser_List_" . date("Y-m-d-H-i-s");
        Excel::create($excelName, function ($excel) use ($rows) {
            $excel->sheet('MeetUser_List_', function ($sheet) use ($rows) {
                $sheet->rows($rows);
            });
        })->export('xlsx');
    }

    private function exportWord($rows, $title)
    {
        $phpWord = new PhpWord();
        //设置默认样式
        $phpWord->setDefaultFontName('仿宋');//字体
        $phpWord->setDefaultFontSize(16);//字号

        //添加页面
        $section = $phpWord->createSection();

        //$section->addText('Hello PHP!');
        //$section->addTextBreak();//换行符

        //添加表格
        $styleTable = [
            'borderColor' => '006699',
            'borderSize' => 6,
            'cellMargin' => 50,
        ];
        $styleFirstRow = ['bgColor' => '66BBFF'];//第一行样式
        foreach ($rows as $row) {
            $phpWord->addTableStyle('myTable', $styleTable, $styleFirstRow);
            $table = $section->addTable('myTable');
            $table->addRow(400);//行高400
            foreach ($title as $value) {
                $table->addCell(2000)->addText($value);
            }
            $table->addRow(400);//行高400
            foreach ($row as $value) {
                $table->addCell(2000)->addText($value);
            }
            $section->addPageBreak();//分页符
        }
        //生成的文档为Word2007
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $docName = "MeetUser_word_" . date("Y-m-d-H-i-s") . ".docx";
        $doc = storage_path('exports/') . $docName;
        $writer->save($doc);
        HttpHelper::download($doc);
    }

    public function getSignInMeetUser($input)
    {
        $where = [
            "mu.user_id" => $input['userId'],
            "mu.meet_id" => $input['meetId'],
            "meet.status" => 0,
            "u.flag" => 0,
            "mu.flag" => 0
        ];
        return DB::table("meet_users as mu")
            ->join("meets as meet", 'meet.id', '=', 'mu.meet_id')
            ->join("users as u", 'u.id', '=', 'mu.user_id')
            ->where($where)->get(["mu.status", "mu.id"])->first();
    }

    /**
     * 签到
     * @param $input
     */
    public function userSignIn($input)
    {
        $where = [
            "user_id" => $input['userId'],
            "meet_id" => $input['meetId'],
            "status" => 1,
            "mu.flag" => 0
        ];
        MeetUser::where($where)->update(["status" => 4]);
    }

    /**
     * 取消报名
     * @param $input
     */
    public function cancel($input)
    {
        $where = [
            "mu.user_id" => $input['userId'],
            "mu.meet_id" => $input['meetId'],
            "mu.flag" => 0,
            "meet.flag" => 0
        ];
        //$meetUser = MeetUser::where($where)->first();
        $meetUser = DB::table("meet_users as mu")
            ->join("meets as meet", 'meet.id', '=', 'mu.meet_id')
            ->where($where)
            ->get(["mu.id", "meet.begin_time"])->first();
        if ($meetUser) {
            $nowTime = time();
            $beginTime = strtotime($meetUser->begin_time);
            $day = round(($beginTime - $nowTime) / 3600 / 24);
            if ($day >= 1) {
                MeetUser::where("id", $meetUser->id)->update(["flag" => 1]);
                return true;
            }
        }
        return false;
    }


    /**
     * 获取查询条件
     * @param $search
     * @return array|bool
     */
    private function getSearchWhere($searchs)
    {
        $sql = "1=1";
        if ((!count($searchs) || empty($searchs)) && empty($this->allInput)) {
            return $sql;
        }
        $where = [];
        $meetId = isset($searchs["meetId"]) ? trim($searchs["meetId"])
            : (isset($this->allInput["meetId"]) ? trim($this->allInput["meetId"]) : "");//合同号
        $status = isset($searchs["status"]) ? trim($searchs["status"])
            : (isset($this->allInput["status"]) ? trim($this->allInput["status"]) : "");//合同号

        $meetName = isset($searchs["meet_name"]) ? trim($searchs["meet_name"])
            : (isset($this->allInput["meet_name"]) ? trim($this->allInput["meet_name"]) : "");//合同号
        $areaId = isset($searchs["area_id"]) ? trim($searchs["area_id"])
            : (isset($this->allInput["area_id"]) ? trim($this->allInput["area_id"]) : "");//合同号

        $userName = isset($searchs["userName"]) ? trim($searchs["userName"])
            : (isset($this->allInput["userName"]) ? trim($this->allInput["userName"]) : "");//合同号
        if (!empty($meetId)) {
            array_push($where, "mu.meet_id=$meetId");
        }
        if ($status !== "") {
            array_push($where, "mu.status=$status");
        }
        if (!empty($meetName)) {
            array_push($where, "meet.name like '%$meetName%'");
        }
        if (!empty($areaId)) {
            array_push($where, "meet.area_id=$areaId");
        }
        if (!empty($userName)) {
            array_push($where, "u.name like '%$userName%'");
        }
        $where = implode(" and ", $where);
        if (empty($where)) {
            return $sql;
        }
        return $where;
    }
}