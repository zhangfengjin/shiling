<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/1/19
 * Time: 14:38
 */

namespace App\Http\Services;


use Illuminate\Http\Request;

class CommonService
{
    protected $user = null;
    protected $sEcho = 0;
    protected $iDisplayStart = 0;
    protected $iDisplayLength = 500;
    protected $searchs = [];
    protected $allInput = [];
    protected $sortField = "id";
    protected $sSortDir = "asc";
    protected $switch;

    public function __construct(Request $request = null, $switch = false)
    {
        $this->switch = $switch;
        if ($switch) {

        }
        if ($request) {
            $this->user = $request->get("user");
            $this->sEcho = intval($request->input("sEcho"));//请求服务器次数 必须
            $this->iDisplayStart = intval($request->input("pageindex"));//开始index 从0开始
            $this->iDisplayLength = intval($request->input("pagelength"));//每页长度
            $this->sEcho = $this->sEcho ? $this->sEcho : 0;
            $this->iDisplayStart = $this->iDisplayStart ? $this->iDisplayStart : 0;
            $this->iDisplayLength = $this->iDisplayLength ? $this->iDisplayLength : 500;//默认最多500行记录
            $this->searchs = json_decode($request->input("searchs"), true);
            if (!count($this->searchs) || empty($this->searchs)) {
                $this->allInput = $request->all();
            }
        }
    }

    /**
     * @param $rows
     * @return array
     */
    public function switchOutKeys($rows)
    {
        if (!$this->switch) {
            return $rows;
        }
        $switchKeys = config("keys.$this->switch");
        $outRows = [];
        foreach ($rows as $row) {
            $outRow = [];
            foreach ($switchKeys as $oldKey => $switchKey) {
                $outRow[$switchKey] = $row->$oldKey;
            }
            $outRows[] = $outRow;
        }
        return $outRows;
    }
}