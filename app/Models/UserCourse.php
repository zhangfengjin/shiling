<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCourse extends Model
{
    //
    protected $table = 'user_courses';
    protected $hidden = [
        'flag'
    ];
}
