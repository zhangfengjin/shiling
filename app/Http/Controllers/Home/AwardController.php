<?php

namespace App\Http\Controllers\Home;

use App\Http\Services\MeetPrizeService;
use App\Http\Services\MeetPrizeUserService;
use App\Http\Services\MeetUserService;
use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AwardController extends HomeController
{
    //
    public function index(Request $request)
    {
        $meetId = $request->input('meetId');
        $meetService = new MeetPrizeService($request);
        $prizeEnums = $meetService->prizeEnums($meetId);
        /*$meetUserService = new MeetUserService($request);
        $list = $meetUserService->getMeetUserList($meetId);*/
        $pages = [
            "prizes" => $prizeEnums
        ];
        return view("award.award")->with($pages);
    }

    public function getPrizeUser(Request $request)
    {
        $meetId = $request->input('meet_id');
        $prizeId = $request->input('prize_id');
        //会议奖项
        $mpService = new MeetPrizeService($request);
        $prizes = $mpService->prizeEnums($meetId);
        //当前会议已中奖人员
        $mpuService = new MeetPrizeUserService($request);
        $awardedCount = $mpuService->getMeetPrizeUserCount($meetId);//当前会议已中奖人员数量
        foreach ($prizes as $prize) {
            if ($prize->id == $prizeId) {//当前奖项开始抽奖
                if (($residue = $prize->prize_count - $awardedCount)) {
                    $awardedUsers = $mpuService->getMeetPrizeUserList($meetId);//当前会议已中奖人员列表
                    //获取当前会议签到的所有人员
                    $muService = new MeetUserService($request);
                    $dbUserList = $muService->getMeetUserList($meetId);
                    //前端参与本轮抽奖的人员
                    $userList = $request->input('user_list');
                    $awardUsers = [];//真正参与抽奖的人员
                    foreach ($dbUserList as $muser) {
                        if (in_array($muser->user_id, $userList)) {
                            $awarded = true;
                            foreach ($awardedUsers as $awardedUser) {
                                if ($awardedUser->user_id == $muser->user_id) {
                                    //该用户已中奖
                                    $awarded = false;
                                    break;
                                }
                            }
                            if ($awarded) {
                                $award = [
                                    'meet_user_id' => $muser->id,
                                    'user_id' => $muser->user_id,
                                    'prize_id' => $prizeId,
                                ];
                                $awardUsers[] = $award;
                            }
                        }
                    }
                    $award = array_rand($awardUsers, 1);//随机获取一个中奖人员
                    $mpuService->create($awardUsers[$award]);
                    return DataStandard::getStandardData($awardUsers[$award]);
                }else{
                    return DataStandard::getStandardData($awardUsers[$award]);
                }
            }
        }
    }
}
