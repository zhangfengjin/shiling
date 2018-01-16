<?php

namespace App\Http\Controllers\Api;

use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MeetController extends Controller
{
    //
    public function signin(Request $request, $meetId)
    {
        //QrCode::format('png')->size(300)->generate('Hello,LaravelAcademy!', config('app.qrcode.path') . $meetId . ".png");
        return DataStandard::getStandardData($meetId);
    }
}
