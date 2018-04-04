<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\OrderService;
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
     * 微信：调用微信生成预付单，然后返回结果对预付单进行签名，最后返回给客户端
     * 支付宝：直接生成签名信息，返回给客户端
     * @param Request $request
     * @return array
     */
    public function unifiedorder(Request $request)
    {
        $payType = $request->input('pay_type');
        if (empty($this->config)) {
            return DataStandard::getStandardData([], config("validator.100"), 100);
        }
        //订单号
        $tradeCode = $request->input("trade_code");
        $orderService = new OrderService();
        $order = $orderService->getOrderByTradeCode($tradeCode);
        if ($order) {
            if (in_array($order->status, [1, 2, 3])) {
                return DataStandard::getStandardData([], config("validator.808"), 808);
            }
            if (in_array($order->status, [4])) {
                return DataStandard::getStandardData([], config("validator.807"), 807);
            }
            //todo
            //根据用户传递的订单号等信息 获取订单数据
            $input = [
                "status" => 5,
                "pay_way" => $payType,
                "pay_app_id" => $this->config[$payType][$payType]["appid"]
            ];
            $order = $orderService->updateOrderPayInfo($input, $order->id);//更新订单支付状态、方式、app_id信息
            try {
                $wxOrder = [];
                switch ($payType) {
                    case "wechat":
                        $wxOrder = $this->preWechatOrder($order, $payType);
                        break;
                    case "alipay":
                        $wxOrder = $this->preWechatOrder($order, $payType);
                        break;
                }
                if (empty($wxOrder)) {
                    return DataStandard::getStandardData($wxOrder);
                }
            } catch (\Exception $e) {
                return DataStandard::getStandardData([], config("validator.201"), 201);
            }
        } else {
            return DataStandard::getStandardData([], config("validator.755"), 755);
        }
    }

    /**
     * 微信：调用微信生成预付单，然后返回结果对预付单进行签名，最后返回给客户端
     * @param $order
     * @param $payType
     * @return mixed
     */
    protected function preWechatOrder($order, $payType)
    {
        $config_biz = [
            'body' => '微信订单支付信息',
            'out_trade_no' => $order->code,//订单号
            'total_fee' => round($order->total_price * 100),//金额
            'trade_type' => 'APP'//app支付
        ];
        $pay = new Pay($this->config);
        return $pay->driver('wechat')->gateway('app')->pay($config_biz);
    }

    /**
     * 支付宝：直接生成签名信息，返回给客户端
     * @param $order
     * @param $payType
     * @return mixed
     */
    protected function preAliOrder($order, $payType)
    {
        $config_biz = [
            'subject' => '阿里订单支付信息',
            'out_trade_no' => $order->code,//订单号
            'total_amount' => round($order->total_price, 2),//金额
        ];
        $pay = new Pay($this->config);
        return $pay->driver('wechat')->gateway('app')->pay($config_biz);
    }

    /**
     * wechat 订单状态通知--客户端触发
     * @param Request $request
     */
    public function weChatOrderQuery(Request $request)
    {
        $payType = "wechat";
        $pay = new Pay($this->config[$payType]);
        //签名校验
        $verify = $pay->driver($payType)->gateway()->verify($request->all());
        if ($verify) {
            //校验微信支付返回结果
            $result = $this->resolveWechat($verify);
            $orderService = new OrderService();
            $orderService->updateOrderPay($result);
            return DataStandard::getStandardData();
        } else {
            return DataStandard::getStandardData([], config("validator.802"), 802);
        }
    }

    /**
     * ali 订单状态通知--客户端触发
     * @param Request $request
     */
    public function aliOrderQuery(Request $request)
    {
        $payType = "alipay";
        $pay = new Pay($this->config[$payType]);
        $verify = $pay->driver($payType)->gateway()->verify($request->all());
        if ($verify) {
            //校验阿里支付返回结果
            $result = $this->resolveAli($verify);
            $orderService = new OrderService();
            $orderService->updateOrderPay($result);
            return DataStandard::getStandardData();
        } else {
            return DataStandard::getStandardData([], config("validator.802"), 802);
        }
    }

    /**
     * wechat 异步通知
     * @param Request $request
     */
    public function weChatPayNotify(Request $request)
    {
        $payType = "wechat";
        $pay = new Pay($this->config[$payType]);
        $verify = $pay->driver($payType)->gateway('app')->verify($request->all());
        if ($verify) {
            $result = $this->resolveWechat($verify);
            $orderService = new OrderService();
            $orderService->updateOrderPay($result, false);
            echo "success";
        }
        echo "failed";
    }

    /**
     * ali 异步通知
     * @param Request $request
     */
    public function aliPayNotify(Request $request)
    {
        $payType = "alipay";
        $pay = new Pay($this->config[$payType]);
        $verify = $pay->driver($payType)->gateway('app')->verify($request->all());
        if ($verify) {
            $result = $this->resolveAli($verify);
            $orderService = new OrderService();
            $orderService->updateOrderPay($result, false);
            echo "success";
        }
        echo "failed";
    }


    /**
     * 解析-微信
     * @param $verify
     * @return array
     */
    protected function resolveWechat($verify)
    {
        $ret = [
            "pay_way" => "wechat"
        ];
        //"openid",
        $checkInfo = [
            "code" => "out_trade_no",
            "total_price" => "total_fee",
            "pay_app_id" => "appid",
            "pay_code" => "transaction_id"
        ];
        foreach ($checkInfo as $key => $info) {
            if ($key == "total_price") {
                $ret[$key] = round($verify[$info] / 100, 2);
            } else {
                $ret[$key] = $verify[$info];
            }
        }
        return $ret;
    }

    /**
     * 解析-阿里
     * @param $verify
     * @return array
     */
    protected function resolveAli($verify)
    {
        $ret = [
            "pay_way" => "alipay"
        ];
        //"seller_id",
        $checkInfo = [
            "code" => "out_trade_no",
            "total_price" => "total_amount",
            "pay_app_id" => "app_id",
            "pay_code" => "trade_no"
        ];
        foreach ($checkInfo as $key => $info) {
            $ret[$key] = $verify[$info];
        }
        return $ret;
    }
}
