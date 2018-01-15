<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\WxPay;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WxPayController extends Controller
{
    public function unifiedorder()
    {

        $appId = "wx426b3015555a46be";
        $mchId = "1900009851";
        $notifyUrl = "";
        $key = "8934e7d15453e97507ef794cf7b0519d";
        $wechatAppPay = new WxPay($appId, $mchId, $notifyUrl, $key);
        $params['body'] = '商品描述';                       //商品描述
        $params['out_trade_no'] = 'O20160617021323-001';    //自定义的订单号
        $params['total_fee'] = '100';                       //订单金额 只能为整数 单位为分
        $params['trade_type'] = 'APP';                      //交易类型 JSAPI | NATIVE | APP | WAP
        $result = $wechatAppPay->unifiedOrder($params);
        print_r($result); // result中就是返回的各种信息信息，成功的情况下也包含很重要的prepay_id
        die;
    }
}
