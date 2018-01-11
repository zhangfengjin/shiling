<?php

namespace App\Http\Controllers\Verify;

use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VerifyController extends Controller
{
    /**
     * 发送验证码--包括手机验证码和邮箱验证码
     * @return array
     */
    public function index()
    {
        return DataStandard::printStandardData([]);
    }

}
