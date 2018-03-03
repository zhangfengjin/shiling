<?php
/**
 * Created by PhpStorm.
 * User: fengjin1
 * Date: 2018/2/26
 * Time: 10:45
 */

namespace App\Http\Services;


use App\Models\MeetPrizeUser;
use Illuminate\Support\Facades\DB;

class MeetPrizeUserService extends CommonService
{
    /**
     * 获取已中奖人员
     * @param $meetId
     * @return mixed
     */
    public function getMeetPrizeUserList($meetId)
    {
        $where = [
            'mu.meet_id' => $meetId,
            'mu.flag' => 0
        ];
        $select = [
            'mu.user_id', 'mpu.prize_id'
        ];
        $rows = DB::table("meet_prize_users as mpu")
            ->join("meet_users as mu", 'mu.id', '=', 'mpu.meet_user_id')
            ->join("meet_prizes as mp", 'mp.id', '=', 'mpu.prize_id')
            ->where($where)->get($select);
        return $rows;
    }

    /**
     * 获取已中奖人员
     * @param $meetId
     * @return mixed
     */
    public function getMeetPrizeUserCount($meetId, $prizeId)
    {
        $where = [
            'mu.meet_id' => $meetId,
            'mpu.prize_id' => $prizeId,
            'mu.flag' => 0
        ];
        $select = [
            'mu.user_id', 'mpu.prize_id'
        ];
        $rows = DB::table("meet_prize_users as mpu")
            ->join("meet_users as mu", 'mu.id', '=', 'mpu.meet_user_id')
            ->join("meet_prizes as mp", 'mp.id', '=', 'mpu.prize_id')
            ->where($where)->get($select)->count();
        return $rows;
    }

    public function create($awards)
    {
        $mpu = new MeetPrizeUser();
        $mpu->meet_user_id = $awards['meet_user_id'];
        $mpu->prize_id = $awards['prize_id'];
        $mpu->save();
    }
}