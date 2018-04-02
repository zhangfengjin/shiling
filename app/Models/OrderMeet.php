<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderMeet extends Model
{
    //
    protected $table = 'order_meets';
    protected $hidden = [
        'flag'
    ];
}
