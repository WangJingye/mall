<?php

namespace admin\erp\service;

use admin\common\service\BaseService;
use admin\extend\Constant;
use common\extend\excel\SpreadExcel;

class OrderService extends BaseService
{

    public $statusList = [
        '1' => [//实物订单
            '1' => [//普通订单
                Constant::ORDER_STATUS_CREATED => '待付款',
                Constant::ORDER_STATUS_PAID => '已付款',
                Constant::ORDER_STATUS_PENDING => '待发货',
                Constant::ORDER_STATUS_SHIPPED => '已发货',
                Constant::ORDER_STATUS_RECEIVED => '已收货',
                Constant::ORDER_STATUS_COMPLETE => '已完成',
                Constant::ORDER_STATUS_CLOSE => '已关闭',
            ],
            '2' => [//团购订单
                Constant::ORDER_STATUS_CREATED => '待付款',
                Constant::ORDER_STATUS_PAID => '待成团',
                Constant::ORDER_STATUS_PENDING => '待发货',
                Constant::ORDER_STATUS_SHIPPED => '已发货',
                Constant::ORDER_STATUS_RECEIVED => '已收货',
                Constant::ORDER_STATUS_COMPLETE => '已完成',
                Constant::ORDER_STATUS_CLOSE => '已关闭',
            ],
            '3' => [//团购订单
                Constant::ORDER_STATUS_CREATED => '待付款',
                Constant::ORDER_STATUS_PAID => '已付款',
                Constant::ORDER_STATUS_PENDING => '待发货',
                Constant::ORDER_STATUS_SHIPPED => '已发货',
                Constant::ORDER_STATUS_RECEIVED => '已收货',
                Constant::ORDER_STATUS_COMPLETE => '已完成',
                Constant::ORDER_STATUS_CLOSE => '已关闭',
            ],
        ],
        '2' => [
            '1' => [
                Constant::ORDER_STATUS_CREATED => '待付款',
                Constant::ORDER_STATUS_PAID => '已付款',
                Constant::ORDER_STATUS_PENDING => '等待电子券',
                Constant::ORDER_STATUS_SHIPPED => '已发送电子券',
                Constant::ORDER_STATUS_RECEIVED => '已使用',
                Constant::ORDER_STATUS_COMPLETE => '已完成',
                Constant::ORDER_STATUS_CLOSE => '已关闭',
            ],
            '2' => [
                Constant::ORDER_STATUS_CREATED => '待付款',
                Constant::ORDER_STATUS_PAID => '待成团',
                Constant::ORDER_STATUS_PENDING => '已成团',
                Constant::ORDER_STATUS_SHIPPED => '已发送电子券',
                Constant::ORDER_STATUS_RECEIVED => '已使用',
                Constant::ORDER_STATUS_COMPLETE => '已完成',
                Constant::ORDER_STATUS_CLOSE => '已关闭',
            ],
            '3' => [
                Constant::ORDER_STATUS_CREATED => '待付款',
                Constant::ORDER_STATUS_PAID => '已付款',
                Constant::ORDER_STATUS_PENDING => '等待电子券',
                Constant::ORDER_STATUS_SHIPPED => '已发送电子券',
                Constant::ORDER_STATUS_RECEIVED => '已使用',
                Constant::ORDER_STATUS_COMPLETE => '已完成',
                Constant::ORDER_STATUS_CLOSE => '已关闭',
            ],
        ],

    ];

    public $orderTypeList = [
        Constant::ORDER_TYPE_REAL => '实物订单',
        Constant::ORDER_TYPE_VIRTUAL => '虚拟物品订单',
    ];
    public $orderGroupList = [
        Constant::ORDER_GROUP_NORMAL => '普通订单',
        Constant::ORDER_GROUP_GROUPON => '团购订单',
        Constant::ORDER_GROUP_FLASHSALE => '秒杀订单',
    ];

    /**
     * @param $params
     * @return array
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
        if (isset($params['receiver_postal']) && $params['receiver_postal'] != '') {
            $selector->where(['receiver_postal' => ['like', '%' . $params['receiver_postal'] . '%']]);
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
    public function saveOrder($data)
    {
        $variations = $data['variations'];
        $variations = $variations ? json_decode($variations, true) : [];
        if (empty($variations)) {
            throw new \Exception('商品信息不能为空');
        }
        $variationList = \Db::table('ProductVariation')->rename('a')
            ->join(['b' => 'Product'], 'a.product_id = b.product_id')
            ->where(['a.variation_id' => ['in', array_column($variations, 'variation_id')]])
            ->field(['a.variation_id', 'b.product_id', 'a.variation_code', 'b.pic', 'b.product_name', 'b.category_id',
                'a.rules_name', 'a.rules_value', 'b.product_weight', 'b.freight_id', 'a.stock'])
            ->findAll();
        $variationList = array_column($variationList, null, 'variation_id');
        $pList = [];
        $data['product_money'] = 0;
        foreach ($variations as $v) {
            if (!isset($variationList[$v['variation_id']])) {
                throw new \Exception('商品信息有误');
            }
            $item = $variationList[$v['variation_id']];
            if ($item['stock'] < $v['number']) {
                throw new \Exception('商品库存不足，请确认');
            }
            $item['number'] = $v['number'];
            if ($v['number'] == 0) {
                continue;
            }
            $item['price'] = $v['price'];
            $data['product_money'] += $item['number'] * $item['price'];
            $pList[$v['variation_id']] = $item;
        }
        if (!empty($data['coupon_id'])) {
            $this->useCoupon($data['coupon_id'], $pList);
        }
        $data['freight_money'] = $this->getFreightFee($pList);
        $data['money'] = $data['product_money'] + $data['freight_money'] - $data['rate_money'];
        $orderVariationList = [];
        if (isset($data['order_id']) && $data['order_id']) {
            \Db::table('Order')->where(['order_id' => $data['order_id']])->update($data);
            $orderVariationList = \Db::table('OrderVariation')->where(['order_id' => $data['order_id']])->findAll();
            $orderVariationList = array_column($orderVariationList, null, 'variation_id');
            $this->orderTrace('编辑', $data['order_id']);
        } else {
            $data['order_code'] = $this->generateCode();
            $data['order_id'] = \Db::table('Order')->insert($data);
            $this->orderTrace('创建', $data['order_id']);
        }
        foreach ($pList as $key => $v) {
            unset($pList[$key]['product_weight']);
            unset($pList[$key]['freight_id']);
            unset($pList[$key]['stock']);
            unset($pList[$key]['category_id']);
            $pList[$key]['order_id'] = $data['order_id'];
        }
        $insertList = [];
        $stockList = [];//增加库存
        //更新
        foreach ($pList as $key => $v) {
            if (isset($orderVariationList[$key])) {
                $orderVariation = $orderVariationList[$key];
                $stockList[$v['variation_id']] = $v['number'] - $orderVariation['number'];
                \Db::table('OrderVariation')->where(['id' => $orderVariation['id']])->update($v);
            } else {
                $stockList[$v['variation_id']] = $v['number'];
                $insertList[] = $v;
            }
        }
        //添加
        if (count($insertList)) {
            \Db::table('OrderVariation')->multiInsert($insertList);
        }
        //删除
        foreach ($orderVariationList as $key => $v) {
            if (!isset($pList[$key])) {
                $stockList[$v['variation_id']] = -$v['number'];
                \Db::table('OrderVariation')->where(['id' => $v['id']])->update(['status' => 0]);
            }
        }
        foreach ($stockList as $id => $number) {
            \Db::table('ProductVariation')->where(['variation_id' => $id])->decrease('stock', $number);
        }
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
            ->field(['variation_id', 'number'])
            ->where(['order_id' => $order['order_id']])
            ->where(['status' => 1])->findAll();
        foreach ($variations as $v) {
            \Db::table('ProductVariation')->where(['variation_id' => $v['variation_id']])->increase('stock', $v['number']);
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
            if (isset($data['collage_id'])) {
                $collage = \Db::table('GrouponCollage')->where(['collage_id' => $data['collage_id']])->find();
                if ($collage['status'] == 2 && $collage['expire_time'] < time()) {
                    throw new \Exception('成团失败，请加入其他团');
                }
                $collage['extra'] = $collage['extra'] ? json_decode($collage['extra'], true) : [];
                $collage['extra'][] = [
                    'user_id' => $order['user_id'],
                    'order_code' => $order['order_code'],
                ];
                if (count($collage['extra']) >= $collage['number']) {
                    if ($collage['status'] == 1) {
                        //成团完毕15分钟内该团允许加入
                        $collage['expire_time'] = time() + 15 * 60;
                    }
                    $collage['status'] = 2;
                }
                if ($collage['status'] == 2) {
                    $order['status'] = Constant::ORDER_STATUS_PENDING;
                }
                $collage['extra'] = json_encode($collage['extra']);
                \Db::table('GrouponCollage')->where(['collage_id' => $data['collage_id']])->save($collage);
            } else {
                $extra = json_decode($order['extra'], true);
                $groupon = \Db::table('Groupon')->where(['id' => $extra['go_id']])->find();
                if ($groupon['group_user_number'] > 1) {
                    $collage = [
                        'go_id' => $extra['go_id'],
                        'number' => $groupon['group_user_number'],
                        'start_userid' => $order['user_id'],
                        'extra' => json_encode([['user_id' => $order['user_id'], 'order_code' => $order['order_code']]])
                    ];
                    \Db::table('GrouponCollage')->insert($collage);
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

    /**
     * 使用优惠券
     * @param $coupon_id
     * @param $variationList
     * @throws \Exception
     */
    public function useCoupon($coupon_id, $variationList)
    {
        //优惠券使用
        $coupon = \Db::table('CouponUser')->where(['id' => $coupon_id])->find();
        if (!$coupon) {
            throw new \Exception('用户优惠券不存在');
        }
        if ($coupon['status'] != 1) {
            throw new \Exception('用户优惠券状态有误，请重新选择');
        }
        if ($coupon['expire_time'] <= time()) {
            throw new \Exception('用户优惠券已过期，请重新选择');
        }
        $data['rate_money'] = $coupon['price'];
        $used = 0;
        if ($coupon['type'] == 2) {//品类券
            $categoryIdList = $this->getChildIdList($coupon['relation_id'], 'ProductCategory', 'category_id');
            $categoryIdList[] = $coupon['relation_id'];
            $total = 0;
            foreach ($variationList as $v) {
                if (in_array($v['category_id'], $categoryIdList)) {
                    $total += $v['price'] * $v['number'];
                    $used = 1;
                }
            }
            if ($total < $coupon['min_price']) {
                throw new \Exception('优惠券不满足使用条件，请重新选择');
            }
        } else if ($coupon['type'] == 3) {//商品券
            $total = 0;
            foreach ($variationList as $v) {
                if ($v['product_id'] == $coupon['relation_id']) {
                    $total += $v['price'] * $v['number'];
                    $used = 1;
                }
            }
            if ($total < $coupon['min_price']) {
                throw new \Exception('优惠券不满足使用条件，请重新选择');
            }
        } else {
            $used = 1;
        }
        if ($used == 0) {
            throw new \Exception('优惠券不满足使用条件，请重新选择');
        }
        \Db::table('CouponUser')->where(['id' => $coupon_id])->update(['status' => 2]);
    }
}