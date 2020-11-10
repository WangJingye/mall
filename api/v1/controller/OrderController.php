<?php

namespace api\v1\controller;

use admin\erp\service\CouponService;
use admin\erp\service\OrderService;
use api\v1\service\CartService;

class OrderController extends BaseController
{
    /** @var CouponService */
    public $couponService;

    /** @var CartService */
    private $cartService;

    public function init()
    {
        $this->couponService = new  CouponService();
        $this->cartService = new  CartService();
        parent::init();
    }

    public function getPreOrderAction()
    {
        $params = \App::$request->params->toArray();
        $variations = !empty($params['variations']) ? $params['variations'] : [];
        $type = !empty($params['type']) ? $params['type'] : 'product';
        $productMoney = 0;
        $variations = array_column($variations, null, 'variation_code');
        if ($type == 'product') {
            $vs = \Db::table('ProductVariation')
                ->field(['product_id', 'variation_code', 'rules_name', 'rules_value', 'stock', 'price'])
                ->where(['variation_code' => ['in', array_keys($variations)]])
                ->findAll();
            $productIds = array_column($vs, 'product_id');
        } else if ($type == 'groupon') {
            $gv = \Db::table('GroupVariation')
                ->field(['go_id', 'variation_code', 'rules_name', 'rules_value', 'stock', 'price', 'product_price'])
                ->where(['variation_code' => ['in', array_keys($variations)]])
                ->find();
            $groupon = \Db::table('Groupon')->where(['id' => $gv['go_id']])->find();
            $arr['product_id'] = $groupon['product_id'];
            $buyType = !empty($params['buy_type']) ? 'single' : 'together';
            $arr['price'] = $buyType == 'single' ? $gv['product_price'] : $gv['price'];
            $arr['stock'] = $gv['stock'];
            $arr['rules_name'] = $gv['rules_name'];
            $arr['rules_value'] = $gv['rules_value'];
            $vs[] = $arr;
            $productIds = [$gv['product_id']];
        } else if ($type == 'flashsale') {
            $vs = \Db::table('FlashSale')
                ->field(['product_id', 'variation_code', 'stock', 'price', 'rules_name', 'rules_value'])
                ->where(['variation_code' => ['in', array_keys($variations)]])
                ->findAll();

            $productIds = array_column($vs, 'product_id');
        }
        $ps = \Db::table('Product')
            ->field(['product_id', 'category_id', 'product_name', 'extra', 'pic'])
            ->where(['product_id' => ['in', $productIds]])
            ->findAll();
        $ps = array_column($ps, null, 'product_id');
        $list = [];
        foreach ($vs as $v) {
            $variation = $variations[$v['variation_code']];
            if ($v['stock'] < $variation['number']) {
                throw new \Exception('该商品不能购买更多哦～');
            }
            $names = explode(',', $v['rules_name']);
            $values = explode(',', $v['rules_value']);
            $rules = [];
            foreach ($names as $k => $n) {
                $rules[] = ['name' => $n, 'value' => $values[$k]];
            }
            $arr = [];
            $product = $ps[$v['product_id']];
            $arr['pic'] = $product['pic'];
            $arr['variation_code'] = $v['variation_code'];
            $arr['product_id'] = $v['product_id'];
            $arr['category_id'] = $ps[$v['product_id']];
            $arr['stock'] = $v['stock'];
            $arr['title'] = $product['product_name'];
            $arr['number'] = $variation['number'];
            $arr['price'] = $v['price'];
            $arr['rules'] = $rules;
            $list[] = $arr;
            $productMoney += $v['price'] * $variation['number'];
        }
        $rateMoney = 0;
        $coupons = \Db::table('CouponUser')
            ->where(['user_id' => \App::$user['user_id']])
            ->where(['status' => 1])
            ->findAll();
        $couponNumber = 0;
        $coupon = null;
        $couponList = [];
        foreach ($coupons as $c) {
            if ($this->couponService->checkCouponAvailable($c, $list)) {
                if ($rateMoney < $c['price']) {
                    $rateMoney = $c['price'];
                    $coupon = $c;
                }
                $couponList[] = $c;
                $couponNumber++;
            }
        }
        $freightMoney = 0;//todo 运费
        $sum = [
            'product_money' => number_format($productMoney, 2, '.', ''),
            'freight_money' => number_format($freightMoney, 2, '.', ''),
            'rate_money' => number_format($rateMoney, 2, '.', ''),
        ];
        $sum['money'] = $sum['product_money'] + $sum['freight_money'] - $sum['rate_money'];
        $sum['money'] = number_format($sum['money'], 2, '.', '');
        $res = [
            'order_group' => $type,
            'sum' => $sum,
            'list' => $list,
            'coupon_number' => $couponNumber,
            'coupon' => $coupon,
            'couponList' => $couponList,
            'freight' => ['title' => '满39包邮']//todo
        ];
        return $this->success('success', $res);
    }

    public function saveAction()
    {
        $params = \App::$request->params->toArray();
        $list = $params['list'];
        if (empty($list)) {
            throw new \Exception('商品不能为空');
        }
        $address = \Db::table('UserAddress')
            ->where(['User_id' => \App::$user['user_id']])
            ->where(['address_id' => $params['address_id']])
            ->find();
        if (!$address) {
            throw new \Exception('地址信息有误');
        }
        $data = [
            'user_id' => \App::$user['user_id'],
            'product_money' => 0,
            'rate_money' => 0,
            'freight_money' => 0,
            'receiver_name' => $address['receiver_name'],
            'receiver_mobile' => $address['receiver_mobile'],
            'receiver_address' => $address['address_area'] . ' ' . $address['detail_address'],
            'remark' => $params['remark']
        ];
        if ($params['order_group'] == 'product') {
            $data['order_group'] = 1;
            $variationList = \Db::table('ProductVariation')
                ->field(['product_id', 'variation_code', 'pic', 'stock', 'price', 'status', 'rules_name', 'rules_value'])
                ->where(['variation_code' => ['in', array_column($list, 'variation_code')]])
                ->findAll();
        } else if ($params['order_group'] == 'groupon') {
            $data['order_group'] = 2;
            $obj = \Db::table('Groupon')->where(['id' => $params['go_id']])->find();
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
                ->field(['variation_code', 'price', 'stock', 'product_price', 'status', 'rules_name', 'rules_value'])
                ->where(['go_id' => $obj['id']])
                ->where(['variation_code' => ['in', array_column($list, 'variation_code')]])
                ->find();
            if (!$v) {
                throw new \Exception('团购信息有误，请联系客服');
            }
            $v['product_id'] = $obj['product_id'];
            $variationList[] = $v;
            $data['extra'] = json_encode(['go_id' => $obj['go_id']]);
        } else if ($params['order_group'] == 'flashsale') {
            $data['order_group'] = 3;
            $v = \Db::table('FlashSale')
                ->field(['product_id', 'variation_code', 'pic', 'stock', 'price', 'status', 'rules_name', 'rules_value'])
                ->where(['flash_id' => $params['flash_id']])
                ->where(['variation_code' => ['in', array_column($list, 'variation_code')]])
                ->find();
            if (!$v) {
                throw new \Exception('秒杀商品信息有误，请联系客服');
            }
            if ($v['variation_code'] == 1) {
                throw new \Exception('秒杀未开始，请确认');
            }
            if ($v['status'] == 1) {
                throw new \Exception('秒杀未开始，请确认');
            }
            if ($v['status'] == 3) {
                throw new \Exception('秒杀已结束，请确认');
            }
            $variationList[] = $v;
            $data['extra'] = json_encode(['flash_id' => $params['flash_id']]);
        }
        $data['order_title'] = $this->generateTitle($data['order_group']);
        $variationList = array_column($variationList, null, 'variation_code');
        $productList = \Db::table('Product')
            ->field(['product_id', 'product_name', 'category_id', 'pic', 'status', 'product_type', 'product_weight'])
            ->where(['product_id' => ['in', array_column($variationList, 'product_id')]])
            ->findAll();
        $productList = array_column($productList, null, 'product_id');

        $dataList = [];
        foreach ($list as $v) {
            if (!isset($variationList[$v['variation_code']])) {
                throw new \Exception('商品信息已变更，请确认～');
            }
            $item = $variationList[$v['variation_code']];
            if ($item['price'] != $v['price']) {
                throw new \Exception('商品信息已变更，请确认～');
            }
            $product = $productList[$item['product_id']];
            if ($item['status'] == 0) {
                throw new \Exception('【' . $product['product_name'] . '】商品信息已变更，请确认～');
            }
            if ($v['number'] > $item['stock']) {
                throw new \Exception('【' . $product['product_name'] . '】商品库存不足，请确认～');
            }
            $data['order_type'] = $product['product_type'];
            $data['product_money'] += $item['price'] * $v['number'];
            $arr = [];
            $arr['variation_code'] = $v['variation_code'];
            $arr['category_id'] = $product['category_id'];
            $arr['price'] = $item['price'];
            $arr['product_id'] = $item['product_id'];
            $arr['pic'] = $item['pic'] ? $item['pic'] : $product['pic'];
            $arr['product_name'] = $product['product_name'];
            $arr['rules_name'] = $item['rules_name'];
            $arr['rules_value'] = $item['rules_value'];
            $arr['number'] = $v['number'];
            $dataList[] = $arr;
        }
        if (!empty($params['coupon_id'])) {
            $coupon = \Db::table('CouponUser')
                ->where(['user_id' => \App::$user['user_id']])
                ->where(['id' => $params['coupon_id']])
                ->find();
            if (!$coupon) {
                throw new \Exception('优惠券信息有误，请确认～');
            }
            if ($coupon['status'] == 2) {
                throw new \Exception('优惠券已使用，请确认～');
            }
            if (!$this->couponService->checkCouponAvailable($coupon, $dataList)) {
                throw new \Exception('优惠券未达到使用条件');
            }
            $data['rate_money'] = $coupon['price'];
            $data['coupon_id'] = $params['coupon_id'];
        }
        $data['money'] = $data['product_money'] + $data['freight_money'] - $data['rate_money'];
        if ($data['money'] != $params['money']) {
            throw new \Exception('商品信息已变更，请确认～');
        }
        $data['order_code'] = $this->generateCode();
        try {
            \Db::startTrans();
            $data['order_id'] = \Db::table('Order')->insert($data);
            $orderService = new OrderService();
            $orderService->orderTrace('创建', $data['order_id'], 2);
            $orderService->saveVariations($data, $dataList);
            if ($params['from_type'] == 'cart') {
                $this->cartService->deleteCart(array_column($dataList, 'variation_code'));
            }
            \Db::commit();
        } catch (\Exception $e) {
            \Db::rollback();
            return $this->error($e->getMessage());
        }
        return $this->success('success');
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
}