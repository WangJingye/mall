<?php

namespace admin\erp\service;

use common\helper\Constant;
use common\extend\excel\SpreadExcel;

class OrderService extends \common\service\OrderService
{

    /**
     * @param $params
     * @return array|\Service
     * @throws \Exception
     */
    public function getList($params, $ispage = true)
    {
        $selector = \Db::table('Order');

        if (isset($params['order_id']) && $params['order_id'] != '') {
            $selector->where(['order_id' => $params['order_id']]);
        }
        if (isset($params['order_type']) && $params['order_type'] != '') {
            $selector->where(['order_type' => $params['order_type']]);
        }
        if (isset($params['order_code']) && $params['order_code'] != '') {
            $selector->where(['order_code' => ['like', '%' . $params['order_code'] . '%']]);
        }
        if (isset($params['user_id']) && $params['user_id'] != '') {
            $selector->where(['user_id' => $params['user_id']]);
        }
        if (isset($params['order_title']) && $params['order_title'] != '') {
            $selector->where(['order_title' => ['like', '%' . $params['order_title'] . '%']]);
        }
        if (isset($params['money']) && $params['money'] != '') {
            $selector->where(['money' => $params['money']]);
        }
        if (isset($params['product_money']) && $params['product_money'] != '') {
            $selector->where(['product_money' => $params['product_money']]);
        }
        if (isset($params['rate_money']) && $params['rate_money'] != '') {
            $selector->where(['rate_money' => $params['rate_money']]);
        }
        if (isset($params['freight_money']) && $params['freight_money'] != '') {
            $selector->where(['freight_money' => $params['freight_money']]);
        }
        if (isset($params['pay_money']) && $params['pay_money'] != '') {
            $selector->where(['pay_money' => $params['pay_money']]);
        }
        if (isset($params['receiver_name']) && $params['receiver_name'] != '') {
            $selector->where(['receiver_name' => ['like', '%' . $params['receiver_name'] . '%']]);
        }
        if (isset($params['receiver_mobile']) && $params['receiver_mobile'] != '') {
            $selector->where(['receiver_mobile' => ['like', '%' . $params['receiver_mobile'] . '%']]);
        }
        if (isset($params['receiver_address']) && $params['receiver_address'] != '') {
            $selector->where(['receiver_address' => ['like', '%' . $params['receiver_address'] . '%']]);
        }
        if (isset($params['transport_id']) && $params['transport_id'] != '') {
            $selector->where(['transport_id' => $params['transport_id']]);
        }
        if (isset($params['transport_order']) && $params['transport_order'] != '') {
            $selector->where(['transport_order' => ['like', '%' . $params['transport_order'] . '%']]);
        }
        if (isset($params['pay_time']) && $params['pay_time'] != '') {
            $selector->where(['pay_time' => $params['pay_time']]);
        }
        if (isset($params['deliver_time']) && $params['deliver_time'] != '') {
            $selector->where(['deliver_time' => $params['deliver_time']]);
        }
        if (isset($params['receive_time']) && $params['receive_time'] != '') {
            $selector->where(['receive_time' => $params['receive_time']]);
        }
        if (isset($params['remark']) && $params['remark'] != '') {
            $selector->where(['remark' => ['like', '%' . $params['remark'] . '%']]);
        }
        if (isset($params['status']) && $params['status'] != '') {
            $selector->where(['status' => $params['status']]);
        } else {
            $selector->where(['status' => ['!=', 0]]);
        }
        if (isset($params['create_time_start']) && $params['create_time_start'] != '') {
            $selector->where(['create_time' => ['>=', strtotime($params['create_time_start'])]]);
        }
        if (isset($params['create_time_end']) && $params['create_time_end'] != '') {
            $selector->where(['create_time' => ['<', strtotime($params['create_time_end']) + 24 * 3600]]);
        }
        if (isset($params['nickname']) && $params['nickname'] != '') {
            $userList = \Db::table('User')->field(['user_id'])
                ->where(['nickname' => $params['nickname']])
                ->findAll();
            $userIdList = array_column($userList, 'user_id');
            $selector->where(['user_id' => ['in', $userIdList]]);
        }
        $selector->order('order_id desc');
        if ($ispage) {
            return $this->pagination($selector, $params);
        }
        return $selector->findAll();
    }


    /**
     * @param $data
     * @throws \Exception
     */
    public function deleteOrder($data)
    {
        $order = \Db::table('Order')->where(['order_id' => $data['id']])->find();
        if ($order['status'] != Constant::ORDER_STATUS_CLOSE) {
            throw new \Exception('只有关闭的订单才可以删除');
        }
        \Db::table('Order')->where(['order_id' => $order['order_id']])->update(['status' => Constant::ORDER_STATUS_DELETE]);
        $this->orderTrace('删除', $order['order_id']);
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function close($data)
    {
        $order = \Db::table('Order')->where(['order_id' => $data['id']])->find();
        if ($order['status'] != Constant::ORDER_STATUS_CREATED) {
            throw new \Exception('只有待付款的订单才可以关闭');
        }
        \Db::table('Order')->where(['order_id' => $order['order_id']])->update(['status' => Constant::ORDER_STATUS_CLOSE]);
        $variations = \Db::table('OrderVariation')
            ->field(['variation_code', 'number'])
            ->where(['order_id' => $order['order_id']])
            ->where(['status' => 1])->findAll();
        foreach ($variations as $v) {
            \Db::table('ProductVariation')->where(['variation_code' => $v['variation_code']])->increase('stock', $v['number']);
        }
        $this->orderTrace('关闭', $order['order_id']);
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function ship($data)
    {
        $order = \Db::table('Order')->where(['order_id' => $data['id']])->find();
        if ($order['status'] != Constant::ORDER_STATUS_PENDING) {
            throw new \Exception('只有待发货的订单才可以发货');
        }
        \Db::table('Order')->where(['order_id' => $order['order_id']])->update([
            'status' => Constant::ORDER_STATUS_SHIPPED,
            'transport_id' => $data['transport_id'],
            'transport_order' => $data['transport_order'],
            'deliver_time' => time(),
        ]);
        $this->orderTrace('发货', $order['order_id']);
    }

    /**
     * 发送电子券
     * @param array|int $id
     * @throws \Exception
     */
    public function sendVoucher($id)
    {
        if (is_array($id)) {
            $order = $id;
        } else {
            $order = \Db::table('Order')->where(['order_id' => $id])->find();
        }
        if ($order['status'] != Constant::ORDER_STATUS_PENDING) {
            throw new \Exception('只有待发送的订单才可以发送电子券');
        }
        \Db::table('Order')->where(['order_id' => $order['order_id']])
            ->update([
                'status' => Constant::ORDER_STATUS_SHIPPED,
                'deliver_time' => time(),
            ]);
        $this->orderTrace('发送电子券', $order['order_id']);
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function receive($data)
    {
        $order = \Db::table('Order')->where(['order_id' => $data['id']])->find();

        if ($order['status'] != Constant::ORDER_STATUS_SHIPPED) {
            throw new \Exception('订单状态有误，请确认');
        }
        \Db::table('Order')->where(['order_id' => $order['order_id']])
            ->update([
                'status' => Constant::ORDER_STATUS_RECEIVED,
                'receive_time' => time()
            ]);
        $this->orderTrace($order['order_type'] == 1 ? '确认收货' : '使用电子券', $order['order_id']);
        //分销返利
        (new SpreadService())->spreadBackMoney($order);
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function complete($data)
    {
        $order = \Db::table('Order')->where(['order_id' => $data['id']])->find();

        if ($order['status'] != Constant::ORDER_STATUS_RECEIVED) {
            throw new \Exception('订单状态有误，请确认');
        }
        \Db::table('Order')->where(['order_id' => $order['order_id']])
            ->update([
                'status' => Constant::ORDER_STATUS_COMPLETE,
            ]);
        $this->orderTrace('评价', $order['order_id']);
        //分销返利
        (new SpreadService())->spreadBackMoney($order);
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function pay($data)
    {
        $order = \Db::table('Order')->where(['order_id' => $data['id']])->find();
        if ($order['status'] != Constant::ORDER_STATUS_CREATED) {
            throw new \Exception('只有待付款的订单才可以进行收款操作');
        }
        $order['status'] = Constant::ORDER_STATUS_PAID;
        if ($order['order_group'] == Constant::ORDER_GROUP_GROUPON) {
            if (isset($data['join_id'])) {
                $join = \Db::table('GrouponJoin')->where(['join_id' => $data['join_id']])->find();
                if ($join['status'] == 2 && $join['expire_time'] < time()) {
                    throw new \Exception('成团失败，请加入其他团');
                }
                $join['extra'] = $join['extra'] ? json_decode($join['extra'], true) : [];
                $join['extra'][] = [
                    'user_id' => $order['user_id'],
                    'order_code' => $order['order_code'],
                ];
                if (count($join['extra']) >= $join['number']) {
                    if ($join['status'] == 1) {
                        //成团完毕15分钟内该团允许加入
                        $join['expire_time'] = time() + 15 * 60;
                    }
                    $join['status'] = 2;
                }
                if ($join['status'] == 2) {
                    $order['status'] = Constant::ORDER_STATUS_PENDING;
                }
                $join['extra'] = json_encode($join['extra']);
                \Db::table('GrouponJoin')->where(['join_id' => $data['join_id']])->update($join);
            } else {
                $extra = json_decode($order['extra'], true);
                $groupon = \Db::table('Groupon')->where(['id' => $extra['go_id']])->find();
                if ($groupon['group_user_number'] > 1) {
                    $join = [
                        'go_id' => $extra['go_id'],
                        'number' => $groupon['group_user_number'],
                        'start_userid' => $order['user_id'],
                        'extra' => json_encode([['user_id' => $order['user_id'], 'order_code' => $order['order_code']]])
                    ];
                    \Db::table('GrouponJoin')->insert($join);
                } else {
                    $order['status'] = Constant::ORDER_STATUS_PENDING;
                }
            }
        }
        $order['pay_method'] = $data['pay_method'];
        $order['pay_money'] = $data['pay_money'];
        $order['pay_time'] = time();
        \Db::table('Order')->where(['order_id' => $order['order_id']])->update($order);
        $bill = [
            'title' => $order['order_title'],
            'user_id' => $order['user_id'],
            'bill_type' => Constant::BILL_TYPE_PAY,
            'relation_type' => Constant::BILL_RELATION_ORDER,
            'relation_id' => $order['order_id'],
            'amount' => $data['pay_money'],
            'pay_method' => $data['pay_method'],
            'transaction_id' => isset($data['transaction_id']) ? $data['transaction_id'] : '',
        ];
        \Db::table('UserBill')->insert($bill);
        $this->orderTrace('付款', $order['order_id']);
        //待发送的虚拟物品订单直接发货
        if ($order['status'] == Constant::ORDER_STATUS_PENDING && $order['order_type'] == Constant::ORDER_TYPE_VIRTUAL) {
            $this->sendVoucher($order);
        }
    }

    /**
     * @param $params
     * @throws \Exception
     */
    public function export($params)
    {
        if ($params['export_type'] == 1) {
            $list = $this->getList($params, false);
        } else {
            $list = \Db::table('Order')->where(['order_id' => ['in', explode(',', $params['ids'])]])->findAll();
        }
        if (count($list) > 10000) {
            throw new \Exception('最多导出1万条数据');
        }
        if (count($list) == 0) {
            throw new \Exception('没有符合条件的数据');
        }
        $data = [];
        $userIdList = array_column($list, 'user_id');
        $userList = \Db::table('User')
            ->field(['user_id', 'telephone', 'nickname'])
            ->where(['user_id' => ['in', $userIdList]])
            ->findAll();
        $userList = array_column($userList, null, 'user_id');
        foreach ($list as $v) {
            $arr = [
                $v['order_code'],
                date('Y-m-d H:i:s', $v['create_time']),
                $userList[$v['user_id']]['nickname'],
                $userList[$v['user_id']]['telephone'],
                $v['money'],
                $v['pay_money'],
                $this->statusList[$v['order_type']][$v['order_group']][$v['status']]
            ];
            $data[] = $arr;
        }
        $export = [];
        $export['table_name'] = '订单数据';
        $export['info'] = ['订单编号', '下单时间', '用户昵称', '用户手机号', '订单金额', '支付金额', '状态'];
        $export['data'] = $data;
        SpreadExcel::exportExcel($export);
    }

    /**
     * @param $variations
     * variation 包含number,freight_id 当freight_id=2时，还需要增加product_weight
     * @return float|int
     * @throws \Exception
     */
    public function getFreightFee($variations)
    {
        $freightIdList = array_column($variations, 'freight_id');
        $freightList = \Db::table('FreightTemplate')->where(['freight_id' => ['in', $freightIdList]])->findAll();
        $freightList = array_column($freightList, null, 'freight_id');
        $fee = 0;
        foreach ($variations as $v) {
            if ($v['freight_id'] == 0) {
                continue;
            }
            $freight = $freightList[$v['freight_id']];
            if ($freight['freight_type'] == 1) {
                $number = $v['number'] - $freight['number'];
            } else if ($freight['freight_type'] == 2) {
                $number = $v['number'] * $v['product_weight'] - $freight['number'];
            }
            $fee += $freight['start_price'];
            if ($number > 0) {
                $fee += $freight['step_price'] * ((int)ceil($number / $freight['step_number']));
            }
        }
        return round($fee, 2);
    }

    //整单退款
    public function refundByOrder($orders)
    {
        $payMethodList = \Db::table('PayMethod')->findAll();
        $payMethodList = array_column($payMethodList, null, 'method_id');
        foreach ($orders as $order) {
            $payMethod = $payMethodList[$order['pay_method']];
            //在线付款
            if ($payMethod['is_online'] == 1) {
                switch ($payMethod['keywords']) {
                    case 'alipay':
                        break;
                    case 'wechatpay':
                        break;
                    case 'wallet':
                    case 'cardpay':
                        \Db::table('UserWallet')->where(['user_id' => $order['user_id']])->increase(['balance' => $order['pay_money']]);
                        break;
                }
            } else {
                \Db::table('UserWallet')->where(['user_id' => $order['user_id']])->increase(['balance' => $order['pay_money']]);
            }
        }
    }
}