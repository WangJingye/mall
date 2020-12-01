<?php

namespace common\service;

use common\helper\Constant;

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

    public function saveOrder($params)
    {
        $list = $params['list'];
        if (empty($list)) {
            throw new \Exception('商品不能为空');
        }
        if (!is_array($list)) {
            $list = json_decode($list, true);
        }
        $order = [
            'user_id' => $params['user_id'],
            'order_group' => $params['order_group'],
            'product_money' => 0,
            'rate_money' => 0,
            'freight_money' => 0,
            'remark' => $params['remark']
        ];
        if (!empty($params['address_id'])) {
            $address = \Db::table('UserAddress')
                ->where(['User_id' => \App::$user['user_id']])
                ->where(['address_id' => $params['address_id']])
                ->find();
            if (!$address) {
                throw new \Exception('地址信息有误');
            }
            $params['receiver_name'] = $address['receiver_name'];
            $params ['receiver_mobile'] = $address['receiver_mobile'];
            $params['receiver_address'] = $address['address_area'] . ' ' . $address['detail_address'];
        }
        $order['receiver_name'] = $params['receiver_name'];
        $order['receiver_mobile'] = $params['receiver_mobile'];
        $order['receiver_address'] = $params['receiver_address'];
        if ($params['order_group'] == 1) {
            $variationList = \Db::table('ProductVariation')
                ->field(['product_id', 'variation_code', 'pic', 'stock', 'price', 'status', 'rules_name', 'rules_value'])
                ->where(['variation_code' => ['in', array_column($list, 'variation_code')]])
                ->findAll();
        } else if ($params['order_group'] == 2) {
            $obj = \Db::table('Groupon')->where(['id' => $params['rel_id']])->find();
            if (!$obj) {
                throw new \Exception('团购商品信息有误，请联系客服');
            }
            if ($obj['status'] == 1) {
                throw new \Exception('团购未开始，请确认');
            }
            if ($obj['status'] == 3) {
                throw new \Exception('团购已结束，请确认');
            }
            $v = \Db::table('GrouponVariation')
                ->field(['variation_code', 'price', 'pic', 'stock', 'product_price', 'status', 'rules_name', 'rules_value'])
                ->where(['go_id' => $obj['id']])
                ->where(['variation_code' => ['in', array_column($list, 'variation_code')]])
                ->find();
            if (!$v) {
                throw new \Exception('团购信息有误，请联系客服');
            }
            $v['product_id'] = $obj['product_id'];
            if (!empty($params['buy_type']) && $params['buy_type'] == 'single') {
                $v['price'] = $v['product_price'];
            }
            $variationList[] = $v;
            $extra = ['go_id' => $obj['id'], 'buy_type' => $params['buy_type']];
            if (!empty($params['join_id'])) {
                $extra['join_id'] = $params['join_id'];
            }
            $order['extra'] = json_encode($extra);
        } else if ($params['order_group'] == 3) {
            $v = \Db::table('FlashSale')
                ->field(['product_id', 'variation_code', 'pic', 'stock', 'price', 'status', 'rules_name', 'rules_value'])
                ->where(['flash_id' => $params['rel_id']])
                ->where(['variation_code' => ['in', array_column($list, 'variation_code')]])
                ->find();
            if (!$v) {
                throw new \Exception('秒杀商品信息有误，请联系客服');
            }
            if ($v['status'] == 1) {
                throw new \Exception('秒杀未开始，请确认');
            }
            if ($v['status'] == 3) {
                throw new \Exception('秒杀已结束，请确认');
            }
            $variationList[] = $v;
            $order['extra'] = json_encode(['flash_id' => $params['flash_id']]);
        }
        if (empty($params['order_title'])) {
            $order['order_title'] = $this->generateTitle($order['order_group']);
        } else {
            $order['order_title'] = $params['order_title'];
        }
        $variationList = array_column($variationList, null, 'variation_code');
        $productList = \Db::table('Product')
            ->field(['product_id', 'product_name', 'category_id', 'pic', 'status', 'product_type', 'product_weight'])
            ->where(['product_id' => ['in', array_column($variationList, 'product_id')]])
            ->findAll();
        $productList = array_column($productList, null, 'product_id');

        $dataList = [];
        foreach ($list as $v) {
            if (!isset($variationList[$v['variation_code']])) {
                throw new \Exception('1商品信息已变更，请确认～');
            }
            $item = $variationList[$v['variation_code']];
            if ($item['price'] != $v['price']) {
                throw new \Exception('2商品信息已变更，请确认～');
            }
            $product = $productList[$item['product_id']];
            if ($item['status'] == 0) {
                throw new \Exception('【' . $product['product_name'] . '】商品信息已变更，请确认～');
            }
            if ($v['number'] > $item['stock']) {
                throw new \Exception('【' . $product['product_name'] . '】商品库存不足，请确认～');
            }
            $order['order_type'] = $product['product_type'];
            $arr = [];
            $arr['variation_code'] = $v['variation_code'];
            $arr['category_id'] = $product['category_id'];
            //app创建的价格无效，后台手动创建的以输入的价格为主
            $arr['price'] = $params['add_type'] == 1 ? $item['price'] : $v['price'];
            $arr['product_id'] = $item['product_id'];
            $arr['pic'] = $item['pic'] ? $item['pic'] : $product['pic'];
            $arr['product_name'] = $product['product_name'];
            $arr['rules_name'] = $item['rules_name'];
            $arr['rules_value'] = $item['rules_value'];
            $arr['number'] = $v['number'];
            $dataList[] = $arr;
            $order['product_money'] += $arr['price'] * $arr['number'];
        }
        if (!empty($params['coupon_id'])) {
            $coupon = \Db::table('CouponUser')
                ->where(['user_id' => $params['user_id']])
                ->where(['id' => $params['coupon_id']])
                ->find();
            if (!$coupon) {
                throw new \Exception('优惠券信息有误，请确认～');
            }
            if ($coupon['status'] == 2) {
                throw new \Exception('优惠券已使用，请确认～');
            }
            $couponService = new CouponService();
            if (!$couponService->checkCouponAvailable($coupon, $dataList)) {
                throw new \Exception('优惠券未达到使用条件');
            }
            $order['rate_money'] = $coupon['price'];
            $order['coupon_id'] = $params['coupon_id'];
        }
        $order['money'] = $order['product_money'] + $order['freight_money'] - $order['rate_money'];
        if ($order['money'] != $params['money']) {
            throw new \Exception('3商品信息已变更，请确认～');
        }
        if (!empty($params['order_id'])) {
            $order['order_id'] = $params['order_id'];
            \Db::table('Order')->where(['order_id' => $params['order_id']])->update($order);
        } else {
            $order['order_code'] = $this->generateCode();
            $order['add_type'] = $params['add_type'];
            $order['order_id'] = \Db::table('Order')->insert($order);
        }
        $this->orderTrace('创建', $order['order_id'], $params['add_type']);
        $this->saveVariations($order, $dataList);
        if (!empty($params['coupon_id'])) {
            \Db::table('CouponUser')->where(['id' => $params['coupon_id']])->update(['status' => 2]);
        }
        if (!empty($params['from_type']) && $params['from_type'] == 'cart') {
            \Db::table('Cart')
                ->where(['user_id' => $params['user_id']])
                ->where(['variation_code' => ['in', array_column($dataList, 'variation_code')]])
                ->delete();
        }
        return $order;
    }

    public function saveVariations($order, $list)
    {
        $list = array_column($list, null, 'variation_code');
        $variations = \Db::table('OrderVariation')
            ->where(['order_id' => $order['order_id']])
            ->findAll();
        $variations = array_column($variations, null, 'variation_code');
        $insertList = [];
        $stockList = [];//增加库存
        //更新
        foreach ($list as $v) {
            if (isset($variations[$v['variation_code']])) {
                $variation = $variations[$v['variation_code']];
                $stockList[$v['variation_code']] = $v['number'] - $variation['number'];
                $v['status'] = 1;
                \Db::table('OrderVariation')->where(['id' => $variation['id']])->update($v);
            } else {
                $v['order_id'] = $order['order_id'];
                $stockList[$v['variation_code']] = $v['number'];
                $insertList[] = $v;
            }
        }
        //添加
        if (count($insertList)) {
            \Db::table('OrderVariation')->multiInsert($insertList);
        }
        //删除
        foreach ($variations as $key => $v) {
            if (!isset($list[$key])) {
                $stockList[$v['variation_code']] = -$v['number'];
                \Db::table('OrderVariation')->where(['id' => $v['id']])->update(['status' => 0]);
            }
        }
        if ($order['order_group'] == 'product') {
            foreach ($stockList as $code => $number) {
                \Db::table('ProductVariation')
                    ->where(['variation_code' => $code])
                    ->decrease('stock', $number);
            }
        } else if ($order['order_group'] == 'groupon') {
            $extra = json_decode($order['extra'], true);
            foreach ($stockList as $code => $number) {
                \Db::table('GrouponVariation')
                    ->where(['go_id' => $extra['go_id']])
                    ->where(['variation_code' => $code])
                    ->decrease('stock', $number);
            }
        } else if ($order['order_group'] == 'flashsale') {
            $extra = json_decode($order['extra'], true);
            foreach ($stockList as $code => $number) {
                \Db::table('FlashSale')
                    ->where(['flash_id' => $extra['flash_id']])
                    ->where(['variation_code' => $code])
                    ->decrease('stock', $number);
            }
        }

    }

    /**
     * 生成单号
     * @param string $prefix
     * @return string
     */
    public function generateCode($prefix = '')
    {
        return $prefix . date('YmdHis') . str_pad(rand(000000, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function generateTitle($group)
    {
        switch ($group) {
            case 1:
                return date('Y-m-d ') . '商品订单';
            case 2:
                return date('Y-m-d ') . '团购订单';
            case 3:
                return date('Y-m-d ') . '秒杀订单';
            default:
                return date('Y-m-d ') . '商品订单';
        }
    }

    /**
     * 订单日志
     * @param $detail
     * @param $params
     * @param int $userType
     * @throws \Exception
     */
    public function orderTrace($detail, $orderId, $userType = 1)
    {
        if (empty($orderId)) {
            return;
        }
        if ($userType == 1) {
            $userId = \App::$user ? \App::$user['admin_id'] : 1;
        } else {
            $userId = \App::$user['user_id'];
        }
        $data = [
            'order_id' => $orderId,
            'create_userid' => $userId,
            'user_type' => $userType,
            'detail' => $detail,
        ];
        \Db::table('OrderTrace')->insert($data);
    }

    public function setOrderStatus($data, $from, $to)
    {
        if (empty($data['order_code'])) {
            return ['success' => false, 'message' => '参数有误'];
        }
        $order = \Db::table('Order')
            ->where(['user_id' => \App::$user['user_id']])
            ->where(['order_code' => $data['order_code']])
            ->find();
        if (!$order) {
            return ['success' => false, 'message' => '订单已删除'];
        }
        if ($order['status'] == $to) {
            return ['success' => true, 'message' => '成功'];
        }
        if ($order['status'] != $from) {
            return ['success' => false, 'message' => '订单状态有误，请确认～'];
        }
        \Db::table('Order')
            ->where(['user_id' => \App::$user['user_id']])
            ->where(['order_code' => $order['order_code']])
            ->update(['status' => $to]);
        return ['success' => true, 'message' => '成功'];
    }
}