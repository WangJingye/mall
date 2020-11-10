<?php

namespace api\v1\controller;

use api\v1\service\CartService;

class CartController extends BaseController
{
    /** @var CartService */
    private $cartService;

    public function init()
    {
        $this->cartService = new CartService();
        parent::init();
    }

    public function changeAction()
    {
        $params = \App::$request->params;
        if (empty($params['variation_code']) || empty($params['number'])) {
            throw new \Exception('参数有误');
        }
        $obj = \Db::table('Cart')
            ->where(['user_id' => \App::$user['user_id']])
            ->where(['variation_code' => $params['variation_code']])
            ->find();
        if (!$obj) {
            throw new \Exception('参数有误');
        }
        $variation = \Db::table('ProductVariation')
            ->where(['variation_code' => $params['variation_code']])
            ->find();
        if (!$variation) {
            throw new \Exception('参数有误');
        }
        $product = \Db::table('Product')
            ->where(['product_id' => $variation['product_id']])
            ->find();
        $res['status'] = $variation['status'] == 1 && $product['status'] == 1 ? 1 : 0;
        $res['stock'] = $variation['stock'];
        $number = min($variation['stock'], $params['number']);
        \Db::table('Cart')
            ->where(['user_id' => \App::$user['user_id']])
            ->where(['variation_code' => $params['variation_code']])
            ->update(['number' => $number]);
        if ($variation['stock'] < $params['number']) {
            return $this->success('超出最大库存数量', $res);
        }
        return $this->success('success', $res);
    }

    public function deleteAction()
    {
        $params = \App::$request->params;
        if (empty($params['variation_code'])) {
            throw new \Exception('参数有误');
        }
        $codes = explode(',', $params['variation_code']);
        $obj = \Db::table('Cart')
            ->where(['user_id' => \App::$user['user_id']])
            ->where(['variation_code' => ['in', $codes]])
            ->findAll();
        if (!$obj) {
            throw new \Exception('参数有误');
        }
        $this->cartService->deleteCart($codes);
        return $this->success('success');
    }

    public function addAction()
    {
        $params = \App::$request->params->toArray();
        if (empty($params['variation_code']) || empty($params['number'])) {
            throw new \Exception('参数有误');
        }
        $cart = \Db::table('Cart')
            ->where(['user_id' => \App::$user['user_id']])
            ->where(['variation_code' => $params['variation_code']])
            ->find();
        if (!$cart) {
            $data = [
                'user_id' => \App::$user['user_id'],
                'number' => $params['number'],
                'variation_code' => $params['variation_code']
            ];
            \Db::table('Cart')->insert($data);
        } else {
            \Db::table('Cart')->where(['user_id' => \App::$user['user_id']])
                ->where(['variation_code' => $params['variation_code']])
                ->update(['number' => $params['number']]);
        }
        return $this->success('success');
    }

}