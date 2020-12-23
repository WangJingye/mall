<?php

namespace api\v1\controller;

use common\helper\Constant;

class ProductController extends BaseController
{
    public function init()
    {
        parent::init();
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
            'product_id' => $product['product_id'],
            'order_group' => Constant::ORDER_GROUP_FLASHSALE,
            'order_type' => $product['product_type'],
            'title' => $obj['title'],
            'price' => $obj['price'],
            'pic' => $obj['pic'],
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
                'sale_number' => $obj['sale_number'],
                'product_price' => $obj['product_price'],
            ]
        ];
        $now = time();
        $res['start_time'] = $obj['start_time'];
        $res['end_time'] = $obj['end_time'];
        $res['now_time'] = $now;
        $res['comments'] = $this->comment($product['product_id']);
        return $res;
    }

    public function comment($productId)
    {
        $comments = \Db::table('ProductComment')
            ->field(['star', 'user_id', 'detail'])
            ->where(['product_id' => $productId])
            ->where(['is_show' => 1])
            ->limit(3)
            ->findAll();
        $userIdList = array_column($comments, 'user_id');
        $userList = \Db::table('User')
            ->field(['user_id', 'nickname', 'avatar'])
            ->where(['user_id' => ['in', $userIdList]])
            ->findAll();
        $userList = array_column($userList, null, 'user_id');
        $res = [];
        foreach ($comments as $c) {
            $user = $userList[$c['user_id']];
            $res[] = [
                'nickname' => $user['nickname'],
                'avatar' => $user['avatar'],
                'star' => $c['star'],
                'detail' => $c['detail']
            ];
        }
        $count = \Db::table('ProductComment')
            ->where(['product_id' => $productId])
            ->where(['is_show' => 1])->count();
        return ['total' => $count, 'list' => $res];
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
            'product_id' => $product['product_id'],
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
            'pic' => $groupon['pic'],
            'rules' => $extra['rules'],
        ];
        $list = [];
        foreach ($grouponVariations as $v) {
            $arr = [];
            $arr['variation_code'] = $v['variation_code'];
            $arr['rules_value'] = $v['rules_value'];
            $arr['stock'] = $v['stock'];
            $arr['sale_number'] = $v['sale_number'];
            $arr['pic'] = $v['pic'];
            $arr['price'] = $v['price'];
            $arr['product_price'] = $v['product_price'];
            $list[] = $arr;
        }
        $res['list'] = $list;
        $joins = \Db::table('GrouponJoin')
            ->where(['go_id' => $groupon['id']])
            ->where(['status' => 1])
            ->findAll();
        $userIdList = array_column($joins, 'start_userid');
        foreach ($joins as $k => $v) {
            $extra = json_decode($v['extra'], true);
            $joins[$k]['extra'] = $extra;
            foreach ($extra as $e) {
                $userIdList[] = $e['user_id'];
            }
        }
        $userIdList = array_unique($userIdList);
        $userList = \Db::table('User')
            ->field(['user_id', 'nickname', 'avatar'])
            ->where(['user_id' => ['in', $userIdList]])
            ->findAll();
        $userList = array_column($userList, null, 'user_id');
        $joinList = [];
        foreach ($joins as $v) {
            $user = $userList[$v['start_userid']];
            $leftTime = $v['create_time'] + \App::$config['site_info']['expire_order_pending'] * 60 - time();
            if ($leftTime <= 0) {
                continue;
            }
            $joinUsers = [];
            if (!empty(\App::$user)) {
                $flag = 0;
                foreach ($v['extra'] as $e) {
                    if ($e['user_id'] == \App::$user['user_id']) {
                        $flag = 1;
                        break;
                    }
                    $joinUser = $userList[$e['user_id']];
                    $joinUsers[] = [
                        'nickname' => $joinUser['nickname'],
                        'avatar' => $joinUser['avatar']
                    ];
                }
                if ($flag == 1) {
                    continue;
                }
            }
            $joinList[] = [
                'join_id' => $v['join_id'],
                'join_users' => $joinUsers,
                'nickname' => $user['nickname'],
                'avatar' => $user['avatar'],
                'left_number' => max($v['number'] - count($extra), 0),
                'left_time' => max($leftTime, 0),
                'show_time' => $this->getLeftTime(max($leftTime, 0))
            ];
        }
        $res['join_list'] = $joinList;
        $res['comments'] = $this->comment($product['product_id']);
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
            'product_id' => $product['product_id'],
            'title' => $product['product_name'],
            'sub_title' => $product['product_sub_name'],
            'order_type' => $product['product_type'],
            'order_group' => Constant::ORDER_GROUP_NORMAL,
            'pic' => $product['pic'],
            'price' => $product['price'],
            'market_price' => $product['market_price'],
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
            $arr['sale_number'] = $v['sale_number'];
            $arr['pic'] = $v['pic'];
            $arr['price'] = $v['price'];
            $arr['product_price'] = $v['market_price'];
            $list[] = $arr;
        }
        $res['list'] = $list;
        $res['comments'] = $this->comment($product['product_id']);
        return $res;
    }

    public function commentAction()
    {
        $params = \App::$request->params->toArray();
        if (empty($params['product_id'])) {
            throw new \Exception('参数有误');
        }
        $page = 1;
        if (!empty($params['page'])) {
            $page = $params['page'];
        }
        $selector = \Db::table('ProductComment')->where(['product_id' => $params['product_id']]);
        $pageSize = 10;
        $total = $selector->count();
        $totalPage = (int)ceil($total / $pageSize);
        $page = min($page, $totalPage);
        $page = max($page, 1);
        $list = $selector->order('comment_id desc')->limit((($page - 1) * $pageSize) . ',' . $pageSize)->findAll();
        $userIdList = array_column($list, 'user_id');
        $userList = \Db::table('User')
            ->field(['user_id', 'nickname', 'avatar'])
            ->where(['user_id' => ['in', $userIdList]])
            ->findAll();
        $userList = array_column($userList, null, 'user_id');
        $codes = array_column($list, 'variation_code');
        $vList = \Db::table('ProductVariation')
            ->field(['variation_code', 'rules_value'])
            ->where(['variation_code' => ['in', $codes]])
            ->findAll();
        $vList = array_column($vList, 'rules_value', 'variation_code');
        $res = [];
        foreach ($list as $v) {
            $user = $userList[$v['user_id']];
            $rules = $vList[$v['variation_code']];
            $res[] = [
                'comment_id' => $v['comment_id'],
                'nickname' => $user['nickname'],
                'avatar' => $user['avatar'],
                'images' => $v['images'] ? explode(',', $v['images']) : [],
                'detail' => $v['detail'],
                'star' => $v['star'],
                'create_time' => $v['create_time'],
                'rules_value' => $rules
            ];
        }
        return $this->success('success', ['list' => $res, 'page' => $page, 'total_page' => $totalPage]);
    }

    public function grouponAction()
    {
        $page = 1;
        if (!empty($params['page'])) {
            $page = $params['page'];
        }
        $selector = \Db::table('Groupon')->where(['status' => 2]);
        $pageSize = 10;
        $total = $selector->count();
        $totalPage = (int)ceil($total / $pageSize);
        $page = min($page, $totalPage);
        $page = max($page, 1);
        $list = $selector->order('id asc')->limit((($page - 1) * $pageSize) . ',' . $pageSize)->findAll();
        $ids = array_column($list, 'id');
        $joins = \Db::table('GrouponJoin')
            ->field(['start_userid', 'go_id'])
            ->where(['go_id' => ['in', $ids]])
            ->where(['status' => 1])
            ->findAll();
        $joinList = [];
        foreach ($joins as $v) {
            $joinList[$v['go_id']][] = $v['start_userid'];
        }
        $users = \Db::table('User')
            ->field(['avatar', 'user_id'])
            ->where(['user_id' => ['in', array_column($joins, 'start_userid')]])
            ->findAll();
        $users = array_column($users, 'avatar', 'user_id');
        $res = [];
        foreach ($list as $v) {
            $item = \Db::table('GrouponVariation')
                ->field(['sum(sale_number) as sale_number'])
                ->where(['go_id' => $v['id']])
                ->where(['status' => 1])->find();
            $joins = $joinList[$v['id']] ?? [];
            $avatars = [];
            foreach ($joins as $j) {
                $avatars[] = $users[$j] ?? '';
                if (count($avatars) > 2) {
                    break;
                }
            }
            $res[] = [
                'id' => $v['id'],
                'title' => $v['title'],
                'price' => $v['price'],
                'pic' => $v['pic'],
                'sale' => $item['sale_number'],
                'joins' => $avatars,
            ];
        }
        return $this->success('success', ['list' => $res, 'page' => $page, 'total_page' => $totalPage]);
    }

    public function flashSaleAction()
    {
        $params = \App::$request->params->toArray();
        $page = 1;
        if (!empty($params['page'])) {
            $page = $params['page'];
        }
        $selector = \Db::table('FlashSale')
            ->where(['status' => 1]);
        if (!empty($params['show_id'])) {
            $selector->where(['type' => 2])
                ->where(['show_id' => $params['show_id']])
                ->where(['date' => date('Y-m-d')]);
        }
        $pageSize = 10;
        $total = $selector->count();
        $totalPage = (int)ceil($total / $pageSize);
        $page = min($page, $totalPage);
        $page = max($page, 1);
        $list = $selector->order('flash_id asc')->limit((($page - 1) * $pageSize) . ',' . $pageSize)->findAll();
        $res = [];
        $now = time();
        foreach ($list as $k => $v) {
            $arr = [];
            $arr['id'] = $v['flash_id'];
            $arr['title'] = $v['title'];
            $arr['price'] = $v['price'];
            $arr['rules_value'] = $v['rules_value'];
            $arr['product_price'] = $v['product_price'];
            $arr['pic'] = $v['pic'];
            $arr['stock'] = $v['stock'];
            $arr['status'] = $v['status'];
            $arr['percent'] = round($v['sale_number'] / ($v['stock'] + $v['sale_number']) * 100, 2);
            $arr['start_time'] = $v['start_time'];
            $arr['end_time'] = $v['end_time'];
            $arr['now_time'] = $now;
            if ($now < $v['start_time']) {
                $v['left_time'] = $v['start_time'] - time();
            } else if ($now >= $v['start_time'] && $now < $v['end_time']) {
                $v['left_time'] = $v['end_time'] - time();
            } else {
                $v['left_time'] = 0;
            }
            $v['show_time'] = $this->getLeftTime($res['left_time']);
            $res[] = $arr;
        }
        return $this->success('success', ['list' => $res, 'page' => $page, 'total_page' => $totalPage]);
    }

    public function flashShowsAction()
    {
        $list = \Db::table('FlashShowing')
            ->field(['start_time', 'end_time', 'show_id'])
            ->where(['status' => 1])
            ->order('start_time asc')
            ->findAll();
        $now = time();
        foreach ($list as $k => $v) {
            $arr = explode(':', $v['start_time']);
            $list[$k]['start_time'] = $arr[0] . ':' . $arr[1];
            $start = strtotime(date('Y-m-d ' . $v['start_time']));
            $end = strtotime(date('Y-m-d ' . $v['end_time']));
            if ($start > $now) {
                $list[$k]['show_status'] = '即将开抢';
            } else if ($start <= $now && $end >= $now) {
                $list[$k]['show_status'] = '抢购中';
            } else {
                $list[$k]['show_status'] = '已结束';
            }
        }
        return $this->success('success', $list);
    }
}