<?php

namespace App\Http\Controllers\Api;

use App\Utils\DataStandard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yansongda\Pay\Pay;

class PayController extends Controller
{
    //
    protected $config;

    protected $payType;

    public function __construct(Request $request)
    {
        $this->config = config("app.pay");
    }

    /**
     * 预下单结算
     * @param Request $request
     * @return array
     */
    public function unifiedorder(Request $request)
    {
        $payType = $request->input('payType');
        if (empty($this->config)) {
            return DataStandard::getStandardData([], config("validator.100"), 100);
        }
        $config_biz = [
            'body' => 'APP支付测试',
            'notify_url' => 'http://lingshi.weibo.com/pay/notify/' . $payType,
            'out_trade_no' => '1415659990',
            'total_fee' => '1',
            'trade_type' => 'APP'
        ];
        try {
            $pay = new Pay($this->config);
            $wxOrder = $pay->driver('wechat')->gateway('app')->pay($config_biz);
            return DataStandard::getStandardData($wxOrder);
        } catch (\Exception $e) {
            return DataStandard::getStandardData([],config("validator.201"), 201);
        }
    }

    /**
     * wechat 通知
     * @param Request $request
     */
    public function weChatPayNotify(Request $request)
    {
        $payType = "wechat";
        $pay = new Pay($this->config[$payType]);
        $verify = $pay->driver($payType)->gateway('app')->verify($request->all());
        //todo
        //注意：同样的通知可能会多次发送给商户系统。商户系统必须能够正确处理重复的通知。
        //商户系统对于支付结果通知的内容一定要做签名验证,并校验返回的订单金额是否与商户侧的订单金额一致，防止数据泄漏导致出现“假通知”，造成资金损失。
        return DataStandard::getStandardData();
    }

    /**
     * ali通知
     * @param Request $request
     */
    public function aliPayNotify(Request $request)
    {
        $payType = "alipay";
        $pay = new Pay($this->config[$payType]);
        $verify = $pay->driver($payType)->gateway('app')->verify($request->all());
        //todo
        //注意：同样的通知可能会多次发送给商户系统。商户系统必须能够正确处理重复的通知。
        //商户系统对于支付结果通知的内容一定要做签名验证,并校验返回的订单金额是否与商户侧的订单金额一致，防止数据泄漏导致出现“假通知”，造成资金损失。
        return DataStandard::getStandardData();
    }
}
