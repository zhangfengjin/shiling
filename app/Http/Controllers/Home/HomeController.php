<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
    public function validateAuth()
    {
        $user = Auth::user();
        if ($user->role_id == 1) {
            return true;
        }
        return false;
    }
}
