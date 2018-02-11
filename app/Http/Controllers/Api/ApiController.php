<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    //
    public function validateAuth(Request $request)
    {
        $token = $request->input('token');
        $userService = new UserService();
        $user = $userService->getUserByToken($token);
        if ($user && in_array($user->role_id, [1, 4, 5])) {
            return true;
        }
        return false;
    }

}
