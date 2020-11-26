<?php

namespace api\v1\controller;

use api\v1\service\UserService;
use common\helper\Constant;

class PublicController extends BaseController
{
    /** @var UserService */
    public $userService;

    public function init()
    {
        $this->userService = new UserService();
        parent::init();
    }

    public function getUserInfoAction()
    {
        try {
            $params = \App::$request->params->toArray();
            $header = \App::$request->header;
            if (empty($header['identity'])) {
                $checkList = [
                    'telephone' => '手机号不能为空',
                    'nickname' => '昵称不能为空',
                    'avatar' => '头像不能为空',
                    'code' => 'code不能为空',
                ];
                foreach ($checkList as $field => $message) {
                    if (!isset($params[$field]) || $params[$field] == '') {
                        throw new \Exception($message);
                    }
                }
            } else {
                $params['openid'] = $header['identity'];
            }
            $res = $this->userService->getUserInfo($params);
            return $this->success('success', $res);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function indexAction()
    {
        $carousels = \Db::table('Carousel')
            ->field(['title', 'pic', 'link_type', 'link_id'])
            ->where(['carousel_type' => 1])
            ->where(['is_show' => 1])
            ->order('sort desc')
            ->findAll();
        $res['carousels'] = $carousels;
        $categories = \Db::table('ProductCategory')
            ->field(['category_id', 'category_name', 'pic'])
            ->where(['level' => 1])
            ->where(['show_home' => 1])
            ->order('sort desc')
            ->limit(8)
            ->findAll();
        $res['categories'] = $categories;
        $flashSales = \Db::table('FlashSale')
            ->field(['flash_id', 'title', 'price', 'product_price', 'status', 'start_time', 'end_time', 'pic'])
            ->where(['status' => ['in', [1, 2]]])
            ->where(['show_home' => 1])
            ->order('sort desc,flash_id desc')
            ->limit(8)
            ->findAll();
        foreach ($flashSales as $k => $v) {
            if ($v['status'] == 2) {
                $flashSales[$k]['left_time'] = $v['end_time'] - time();
            } else {
                $flashSales[$k]['left_time'] = $v['start_time'] - time();
            }
        }
        $res['flashsales'] = $flashSales;
        $groupons = \Db::table('Groupon')
            ->field(['id', 'title', 'price', 'end_time', 'group_user_number', 'pic', 'status'])
            ->where(['show_home' => 1])
            ->where(['status' => 2])
            ->findAll();
        foreach ($groupons as $k => $v) {
            $groupons[$k]['left_time'] = $v['end_time'] - time();
        }
        $res['groupons'] = $groupons;
        $list = \Db::table('Product')->where(['status' => 1])->limit(6)->findAll();
        $list1 = $list;
        $list = array_merge($list1, $list);
        $list1 = array_reverse($list1);
        $list = array_merge($list1, $list);
        $res['modules'] = [
            ['title' => '每日好货', 'show_more' => 0, 'list' => $list],
            ['title' => '为您推荐', 'show_more' => 1, 'list' => $list],
        ];
        return $this->success('success', $res);
    }

    public function detailAction()
    {
        $params = \App::$request->params;
        if (empty($params['id'])) {
            throw new \Exception('参数有误');
        }
        if (empty($params['type'])) {
            $params['type'] = 'product';
        }
        if ($params['type'] == 'flashsale') {
            $res = $this->flashSale($params['id']);
        } else if ($params['type'] == 'groupon') {
            $res = $this->groupon($params['id']);
        } else {
            $res = $this->product($params['id']);
        }
        return $this->success('success', $res);
    }

    public function flashSale($id)
    {
        $obj = \Db::table('FlashSale')->where(['flash_id' => $id])->find();
        if (!$obj) {
            throw new \Exception('商品不存在');
        }

        $product = \Db::table('Product')->where(['product_id' => $obj['product_id']])->find();
        if (!$product) {
            throw new \Exception('商品不存在');
        }
        $extra = json_decode($product['extra'], true);
        $res = [
            'id' => $obj['flash_id'],
            'order_group' => Constant::ORDER_GROUP_FLASHSALE,
            'order_type' => $product['product_type'],
            'title' => $obj['title'],
            'price' => $obj['price'],
            'product_price' => $obj['product_price'],
            'images' => explode(',', $extra['images']),
            'detail' => $product['detail'],
            'product_params' => $extra['product_params'],
            'status' => $obj['status']
        ];
        $res['list'] = [
            [
                'variation_code' => $obj['variation_code'],
                'price' => $obj['price'],
                'stock' => $obj['stock'],
                'product_price' => $obj['product_price'],
            ]
        ];
        if ($obj['status'] == 1) {
            $res['left_time'] = time() - $obj['start_time'];
        } else if ($obj['status'] == 2) {
            $res['left_time'] = $obj['end_time'] - time();
        } else {
            $res['left_time'] = 0;
        }
        return $res;
    }

    public function groupon($id)
    {
        $groupon = \Db::table('Groupon')->where(['id' => $id])->find();
        if (!$groupon) {
            throw new \Exception('商品不存在');
        }
        $product = \Db::table('Product')->where(['product_id' => $groupon['product_id']])->find();
        if (!$product) {
            throw new \Exception('商品不存在');
        }
        $extra = json_decode($product['extra'], true);
        $grouponVariations = \Db::table('GrouponVariation')
            ->where(['go_id' => $groupon['id']])
            ->where(['status' => 1])
            ->findAll();
        $res = [
            'id' => $groupon['id'],
            'title' => $groupon['title'],
            'order_group' => Constant::ORDER_GROUP_GROUPON,
            'order_type' => $product['product_type'],
            'group_user_number' => $groupon['group_user_number'],
            'price' => $groupon['price'],
            'product_price' => $groupon['product_price'],
            'detail' => $product['detail'],
            'product_params' => $extra['product_params'],
            'status' => $groupon['status'],
            'images' => explode(',', $extra['images']),
            'rules' => $extra['rules']
        ];
        if ($groupon['status'] == 1) {
            $res['left_time'] = time() - $groupon['start_time'];
        } else if ($groupon['status'] == 2) {
            $res['left_time'] = $groupon['end_time'] - time();
        } else {
            $res['left_time'] = 0;
        }
        $list = [];
        foreach ($grouponVariations as $v) {
            $arr = [];
            $arr['variation_code'] = $v['variation_code'];
            $arr['rules_value'] = $v['rules_value'];
            $arr['stock'] = $v['stock'];
            $arr['price'] = $v['price'];
            $arr['product_price'] = $v['product_price'];
            $list[] = $arr;
        }
        $res['list'] = $list;
        return $res;
    }

    public function product($id)
    {
        $product = \Db::table('Product')->where(['product_id' => $id])->find();
        if (!$product) {
            throw new \Exception('商品不存在');
        }
        $extra = json_decode($product['extra'], true);
        $res = [
            'id' => $product['product_id'],
            'title' => $product['product_name'],
            'sub_title' => $product['product_sub_name'],
            'order_type' => $product['product_type'],
            'order_group' => Constant::ORDER_GROUP_NORMAL,
            'pic' => $product['pic'],
            'price' => $product['price'],
            'detail' => $product['detail'],
            'product_params' => $extra['product_params'],
            'status' => $product['status'],
            'images' => explode(',', $extra['images']),
            'rules' => $extra['rules']
        ];
        $variations = \Db::table('ProductVariation')
            ->where(['product_id' => $product['product_id']])
            ->where(['status' => 1])
            ->findAll();
        $list = [];
        foreach ($variations as $v) {
            $arr = [];
            $arr['variation_code'] = $v['variation_code'];
            $arr['rules_value'] = $v['rules_value'];
            $arr['stock'] = $v['stock'];
            $arr['pic'] = $v['pic'];
            $arr['price'] = $v['price'];
            $arr['product_price'] = $v['market_price'];
            $list[] = $arr;
        }
        $res['list'] = $list;
        return $res;
    }

    public function uploadAction()
    {
        if (empty($_FILES['file'])) {
            throw new \Exception('未选择上传文件');
        }
        $pic = $this->parseFileOrUrl('file', 'v1/comment');
        return $this->success('success', ['pic' => $pic]);
    }

    public function categoryAction()
    {
        $categories = \Db::table('ProductCategory')
            ->field(['category_id', 'category_name', 'pic', 'parent_id'])
            ->where(['status' => 1])->findAll();
        $categoryList = [];
        foreach ($categories as $v) {
            $categoryList[$v['parent_id']][] = $v;
        }
        return $this->success('success', $categoryList);
    }
}