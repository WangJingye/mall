<?php

namespace api\v1\controller;

use common\helper\Constant;
use common\service\OrderService;

class TestController extends BaseController
{
    public function init()
    {
        if (!APP_DEBUG) {
            throw new \Exception('非测试环境，停止调用！');
        }
        parent::init();
    }

    public function payAction()
    {
        $keyList = ['out_trade_no', 'transaction_id', 'total_fee'];
        $params = \App::$request->params->toArray();
        foreach ($keyList as $k) {
            if (!isset($params[$k])) {
                throw new \Exception('参数out_trade_no,transaction_id,total_fee必传');
            }
        }
        $order = \Db::table('Order')->where(['trade_no' => $params['out_trade_no']])->find();
        if ($order['status'] != Constant::ORDER_STATUS_CREATED) {
            throw new \Exception('交易失败');
        }
        $update['pay_time'] = time();
        $update['status'] = Constant::ORDER_STATUS_PAID;
        $update['transaction_id'] = $params['transaction_id'];
        $update['pay_method'] = 3;//线上微信
        $update['pay_money'] = $params['total_fee'] / 100;
        $extra = json_decode($order['extra'], true);
        //团购订单只有拼单的需要创建拼团记录
        if ($order['order_group'] == Constant::ORDER_GROUP_GROUPON && $extra['buy_type'] == 'together') {
            $groupon = \Db::table('Groupon')
                ->where(['id' => $extra['go_id']])
                ->find();
            //拼团人数大于1的需要创建拼团记录
            if ($groupon['group_user_number'] > 1) {
                //参加拼团
                if (!empty($extra['join_id'])) {
                    $join = \Db::table('GrouponJoin')
                        ->where(['join_id' => $extra['join_id']])
                        ->find();
                    $join['extra'] = $join['extra'] ? json_decode($join['extra'], true) : [];
                    $join['extra'][] = [
                        'user_id' => $order['user_id'],
                        'order_code' => $order['order_code'],
                    ];
                    if (count($join['extra']) >= $join['group_user_number']) {
                        if ($join['status'] == 1) {
                            //成团完毕15分钟内该团允许加入
                            $join['expire_time'] = time() + 15 * 60;
                        }
                        $join['status'] = 2;
                    }
                    if ($join['status'] == 2) {
                        $order['status'] = Constant::ORDER_STATUS_PENDING;
                    }
                    \Db::table('GrouponJoin')->where(['join_id' => $join['join_id']])->update($join);
                } else {//发起拼团
                    $join = [
                        'go_id' => $extra['go_id'],
                        'number' => $groupon['group_user_number'],
                        'start_userid' => $order['user_id'],
                        'extra' => json_encode([['user_id' => $order['user_id'], 'order_code' => $order['order_code']]])
                    ];
                    \Db::table('GrouponJoin')->insert($join);
                }
            }
        }
        \Db::table('Order')->where(['order_id' => $order['order_id']])->update($update);
        $s = new OrderService();
        $s->ordertrace('用户付款，付款金额' . $order['pay_money'], $order['order_id']);
        return $this->success('success');
    }
}