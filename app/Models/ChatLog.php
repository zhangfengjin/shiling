<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatLog extends Model
{
    //
    protected $table = 'chat_logs';
    protected $hidden = [
        'flag'
    ];
}
