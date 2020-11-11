<?php

namespace common\service;

use common\extend\wechat\WechatPay;
use common\helper\Constant;

class PayService extends BaseService
{
    public function do($order)
    {
        $no = WechatPay::instance()->generateTradeNo();
        \Db::table('Order')->where(['order_code' => $order['order_code']])->update(['trade_no' => $no]);
        return WechatPay::instance()->createPreOrder($order['order_title'], $no, $order['money']);
    }

    public function notify($xml)
    {
        $res = WechatPay::instance()->notify($xml);
        $order = \Db::table('Order')->where(['trade_no' => $res['out_trade_no']])->find();
        if ($order['status'] != Constant::ORDER_STATUS_CREATED) {
            throw new \Exception('交易失败');
        }
        $update['pay_time'] = time();
        $update['status'] = Constant::ORDER_STATUS_PAID;
        $update['transaction_id'] = $res['transaction_id'];
        $update['pay_method'] = 3;//线上支付宝
        $update['pay_money'] = $res['total_fee'] / 100;
        \Db::table('Order')->where(['order_id' => $res['order_id']])->update($update);
        $s = new OrderService();
        $s->ordertrace('用户付款，付款金额' . $order['pay_money'], $order['order_id']);
    }
}