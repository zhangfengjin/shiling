<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetPrizeUser extends Model
{
    //
    protected $table = 'meet_prize_users';
    protected $hidden = [
        'flag'
    ];
}
