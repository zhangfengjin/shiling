<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGrade extends Model
{
    //
    protected $table = 'user_grades';
    protected $hidden = [
        'flag'
    ];
}
