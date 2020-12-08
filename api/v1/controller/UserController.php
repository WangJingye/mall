<?php

namespace api\v1\controller;

use api\v1\service\UserService;

class UserController extends BaseController
{
    /** @var UserService */
    public $userService;

    public function init()
    {
        $this->userService = new UserService();
        parent::init();
    }

    public function addressAction()
    {
        $params = \App::$request->params->toArray();
        $selector = \Db::table('UserAddress')
            ->where(['user_id' => \App::$user['user_id']]);
        if (!empty($params['id'])) {
            $selector->where(['address_id' => $params['id']]);
        } else {
            $selector->where(['is_default' => 1]);
        }
        $data = $selector->find();
        return $this->success('success', $data);
    }

    public function addressListAction()
    {
        $selector = \Db::table('UserAddress')
            ->where(['user_id' => \App::$user['user_id']]);
        $data = $selector->findAll();
        return $this->success('success', $data);
    }

    public function cartAction()
    {
        $cartList = \Db::table('Cart')
            ->where(['user_id' => \App::$user['user_id']])
            ->findAll();
        $variations = \Db::table('ProductVariation')
            ->where(['variation_code' => ['in', array_column($cartList, 'variation_code')]])
            ->findAll();
        $products = \Db::table('Product')
            ->where(['product_id' => ['in', array_column($variations, 'product_id')]])
            ->findAll();
        $variations = array_column($variations, null, 'variation_code');
        $products = array_column($products, null, 'product_id');
        $res = [];
        foreach ($cartList as $v) {
            $variation = $variations[$v['variation_code']];
            $product = $products[$variation['product_id']];
            $arr = [];
            $arr['product_id'] = $product['product_id'];
            $arr['variation_code'] = $v['variation_code'];
            $arr['product_name'] = $product['product_name'];
            $arr['pic'] = $product['pic'];
            $arr['rules'] = $variation['rules_value'];
            $arr['price'] = $variation['price'];
            $arr['stock'] = $variation['stock'];
            $arr['number'] = min($v['number'], $variation['stock']);
            $arr['status'] = $product['status'];
            $res[] = $arr;
        }
        return $this->success('success', $res);
    }

    public function suggestAction()
    {
        $params = \App::$request->params->toArray();
        if (!isset($params['content']) || $params['content'] === '') {
            throw new \Exception('参数有误');
        }
        $data = [
            'user_id' => \App::$user['user_id'],
            'content' => $params['content']
        ];
        \Db::table('Suggest')->insert($data);
        return $this->success('success');
    }
}