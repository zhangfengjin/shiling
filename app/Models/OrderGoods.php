<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderGoods extends Model
{
    //
    protected $table = 'order_goods';
    protected $hidden = [
        'flag'
    ];
}
