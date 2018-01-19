<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsAtt extends Model
{
    //
    protected $table = 'goods_atts';
    protected $hidden = [
        'flag'
    ];
}
