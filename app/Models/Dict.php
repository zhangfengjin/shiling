<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dict extends Model
{
    //
    protected $table = 'dicts';
    protected $hidden = [
        'flag'
    ];
}
