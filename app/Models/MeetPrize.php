<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetPrize extends Model
{
    //
    protected $table = 'meet_prizes';
    protected $hidden = [
        'flag'
    ];
}
