<?php

namespace api\v1\controller;

use admin\erp\service\CouponService;
use common\helper\Constant;
use common\service\OrderService;
use common\service\PayService;
use component\Util;

class OrderController extends BaseController
{
    /** @var CouponService */
    public $couponService;

    /** @var OrderService */
    private $orderService;
    /** @var PayService */
    private $payService;

    public function init()
    {
        $this->couponService = new  CouponService();
        $this->orderService = new  OrderService();
        $this->payService = new  PayService();
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
            $gv = \Db::table('GrouponVariation')
                ->field(['go_id', 'variation_code', 'rules_name', 'rules_value', 'stock', 'price', 'product_price'])
                ->where(['go_id' => $params['rel_id']])
                ->where(['variation_code' => ['in', array_keys($variations)]])
                ->find();
            $groupon = \Db::table('Groupon')->where(['id' => $gv['go_id']])->find();
            $arr['product_id'] = $groupon['product_id'];
            $arr['price'] = !empty($params['buy_type']) && $params['buy_type'] == 'single' ? $gv['product_price'] : $gv['price'];
            $arr['stock'] = $gv['stock'];
            $arr['rules_name'] = $gv['rules_name'];
            $arr['rules_value'] = $gv['rules_value'];
            $arr['variation_code'] = $gv['variation_code'];
            $vs[] = $arr;
            $productIds = [$groupon['product_id']];
        } else if ($type == 'flashsale') {
            $vs = \Db::table('FlashSale')
                ->field(['product_id', 'variation_code', 'stock', 'price', 'rules_name', 'rules_value'])
                ->where(['flash_id' => $params['rel_id']])
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
            $names = $v['rules_name'] != '' ? explode(',', $v['rules_name']) : [];
            $values = $v['rules_value'] != '' ? explode(',', $v['rules_value']) : [];
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
        $order = $this->orderService->saveOrder($params);
        return $this->success('调用支付失败：支付未开通');
        //todo
        try {
            $res = $this->payService->exec($order);
            return $this->success('success', $res);
        } catch (\Exception $e) {
            return $this->success('调用支付失败：' . $e->getMessage());
        }
    }

    public function payAction()
    {
        $params = \App::$request->params->toArray();
        if (empty($params['order_code'])) {
            throw new \Exception('参数有误');
        }
        $order = \Db::table('Order')->where(['order_code' => $params['order_code']])->find();
        if (!$order) {
            throw new \Exception('订单不存在');
        }
        return $this->success('success');
        return $this->success('success', $this->payService->exec($order));
    }

    /**
     * 微信支付回调
     */
    public function notifyAction()
    {
        try {
            $xml = file_get_contents('php://input');
            file_put_contents('/tmp/123.log', date('Y-m-d H:i:s ') . $xml . PHP_EOL, FILE_APPEND);
            $this->payService->notify($xml);
            $res = [
                'return_code' => 'SUCCESS',
                'return_msg' => 'OK'
            ];
        } catch (\Exception $e) {
            $res = [
                'return_code' => 'FAIL',
                'return_msg' => $e->getMessage()
            ];
        }
        exit(Util::array2xml($res));
    }

    public function indexAction()
    {
        $params = \App::$request->params->toArray();
        $selector = \Db::table('Order')
            ->where(['user_id' => \App::$user['user_id']]);
        if (!empty($params['status']) && $params['status'] != 'all') {
            $selector->where(['status' => $params['status']]);
        } else {
            $selector->where(['status' => ['!=', 0]]);
        }
        $page = 1;
        if (!empty($params['page'])) {
            $page = $params['page'];
        }
        $pageSize = 10;
        $total = $selector->count();
        $totalPage = (int)ceil($total / $pageSize);
        $page = min($page, $totalPage);
        $page = max($page, 1);
        $list = $selector->order('order_id desc')->limit((($page - 1) * $pageSize) . ',' . $pageSize)->findAll();
        $variations = \Db::table('OrderVariation')
            ->where(['order_id' => ['in', array_column($list, 'order_id')]])
            ->where(['status' => 1])
            ->findAll();
        $variationList = [];
        foreach ($variations as $v) {
            $variationList[$v['order_id']][] = $v;
        }
        foreach ($list as $k => $v) {
            $v['variations'] = $variationList[$v['order_id']];
            $v['status_name'] = $this->orderService->statusList[$v['order_type']][$v['order_group']][$v['status']];
            $list[$k] = $v;
        }
        return $this->success('success', ['list' => $list, 'page' => $page, 'total_page' => $totalPage]);
    }

    public function deleteAction()
    {
        $params = \App::$request->params->toArray();
        $res = $this->orderService->setOrderStatus($params, Constant::ORDER_STATUS_CLOSE, Constant::ORDER_STATUS_DELETE);
        return $res['success'] ? $this->success('success') : $this->error($res['message']);
    }

    public function cancelAction()
    {
        $params = \App::$request->params->toArray();
        $res = $this->orderService->setOrderStatus($params, Constant::ORDER_STATUS_CREATED, Constant::ORDER_STATUS_CLOSE);
        return $res['success'] ? $this->success('success') : $this->error($res['message']);
    }

    public function receiveAction()
    {
        $params = \App::$request->params->toArray();
        $res = $this->orderService->setOrderStatus($params, Constant::ORDER_STATUS_SHIPPED, Constant::ORDER_STATUS_RECEIVED);
        return $res['success'] ? $this->success('success') : $this->error($res['message']);
    }

    public function pushCommentAction()
    {
        $params = \App::$request->params->toArray();
        if (empty($params['order_code'])) {
            throw new \Exception('参数有误');
        }
        if (empty($params['list'])) {
            throw new \Exception('评价内容不能为空');
        }
        $order = \Db::table('Order')
            ->where(['user_id' => \App::$user['user_id']])
            ->where(['order_code' => $params['order_code']])
            ->find();
        if (!$order) {
            throw new \Exception('参数有误');
        }
        if ($order['status'] == Constant::ORDER_STATUS_COMPLETE) {
            throw new \Exception('订单已评论');
        }
        if ($order['status'] != Constant::ORDER_STATUS_RECEIVED) {
            throw new \Exception('订单未完成');
        }
        \Db::table('Order')
            ->where(['user_id' => \App::$user['user_id']])
            ->where(['order_code' => $params['order_code']])
            ->update(['status' => Constant::ORDER_STATUS_COMPLETE]);
        $list = $params['list'];
        $codes = array_column($list, 'variation_code');
        $variations = \Db::table('ProductVariation')
            ->where(['variation_code' => ['in', $codes]])
            ->findAll();
        $variations = array_column($variations, null, 'variation_code');
        $inserts = [];
        foreach ($list as $v) {
            $variation = isset($variations[$v['variation_code']]) ? $variations[$v['variation_code']] : [];
            $data = [
                'order_code' => $order['order_code'],
                'user_type' => 2,
                'user_id' => \App::$user['user_id'],
                'product_id' => !empty($variation['product_id']) ? $variation['product_id'] : 0,
                'variation_code' => $v['variation_code'],
                'star' => $v['star'],
                'detail' => $v['comment'],
                'images' => implode(',', $v['images'])
            ];
            $inserts[] = $data;
        }
        \Db::table('ProductComment')->multiInsert($inserts);
        return $this->success('success');
    }

}