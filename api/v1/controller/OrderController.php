<?php

namespace api\v1\controller;

use admin\erp\service\CouponService;
use api\v1\service\CartService;
use common\service\OrderService;

class OrderController extends BaseController
{
    /** @var CouponService */
    public $couponService;

    /** @var CartService */
    private $cartService;
    /** @var OrderService */
    private $orderService;

    public function init()
    {
        $this->couponService = new  CouponService();
        $this->cartService = new  CartService();
        $this->orderService = new  OrderService();
        parent::init();
    }


    public function getPreOrderAction()
    {
        $typeList = [
            'product' => 1,
            'groupon' => 2,
            'flashsale' => 3
        ];
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
            'order_group' => $typeList[$type],
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
        $params['add_type'] = 2;
        $params['user_id'] = \App::$user['user_id'];
        $this->orderService->saveOrder($params);
        return $this->success('success');
    }
}