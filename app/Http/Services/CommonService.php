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
    protected $user;
    protected $eEcho;
    protected $iDisplayStart;
    protected $iDisplayLength;
    protected $searchs = [];
    protected $allInput;
    protected $sortField;
    protected $sSortDir;

    public function __construct(Request $request = null)
    {
        if ($request) {
            $this->user = $request->get("user");
            $this->sEcho = 1;// $request->input("sEcho");//请求服务器次数 必须
            if (!empty($this->sEcho)) {
                $this->iDisplayStart = intval($request->input("pageindex"));//开始index 从0开始
                $this->iDisplayLength = intval($request->input("pagelength"));//每页长度
            }
            $this->iDisplayStart = $this->iDisplayStart ? $this->iDisplayStart : 0;
            $this->iDisplayLength = $this->iDisplayLength ? $this->iDisplayLength : 500;//默认最多500行记录
            $this->searchs = json_decode($request->input("searchs"), true);
            if (!count($this->searchs) || empty($this->searchs)) {
                $this->allInput = $request->all();
            }
        }
    }
}