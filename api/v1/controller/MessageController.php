<?php

namespace api\v1\controller;

class MessageController extends BaseController
{
    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        $categoryIds = \Db::table('Message')
            ->field(['category_id'])
            ->where(['user_id' => \App::$user['user_id']])
            ->where(['status' => 1])
            ->group('category_id')
            ->findAll();
        $categoryIds = array_column($categoryIds, 'category_id');
        $categoryList = \Db::table('MessageCategory')
            ->field(['category_id', 'category_name', 'pic'])
            ->where(['category_id' => ['in', $categoryIds]])
            ->findAll();
        $res = [];
        foreach ($categoryList as $v) {
            $message = \Db::table('Message')
                ->where(['category_id' => $v['category_id']])
                ->where(['status' => 1])
                ->order('update_time desc')
                ->limit(1)
                ->find();
            $arr = [];
            $arr['id'] = $v['category_id'];
            $arr['content'] = $message['content'];
            $arr['title'] = $v['category_name'];
            $arr['pic'] = $v['pic'];
            $arr['last_time'] = $message['update_time'];
            $res[] = $arr;
        }
        return $this->success('success', $res);
    }

    public function listAction()
    {
        $params = \App::$request->params->toArray();
        $category = \Db::table('MessageCategory')
            ->where(['category_id' => $params['category_id']])
            ->find();
        $selector = \Db::table('Message')
            ->where(['category_id' => $params['category_id']])
            ->where(['user_id' => \App::$user['user_id']])
            ->where(['status' => 1]);
        $page = 1;
        if (!empty($params['page'])) {
            $page = $params['page'];
        }
        $pageSize = 20;
        $total = $selector->count();
        $totalPage = (int)ceil($total / $pageSize);
        $page = min($page, $totalPage);
        $page = max($page, 1);
        $list = $selector->order('id desc')
            ->limit((($page - 1) * $pageSize) . ',' . $pageSize)
            ->findAll();
        $res = [];
        foreach ($list as $v) {
            $extra = $v['extra'] ? json_decode($v['extra'], true) : [];
            $arr = [];
            $arr['id'] = $v['id'];
            $arr['title'] = $v['title'];
            $arr['content'] = $v['content'];
            $arr['update_time'] = $v['update_time'];
            if ($category['type'] == 1) {
                $arr['pic'] = $extra['pic'];
                $arr['order_code'] = $extra['order_code'];
            } else if ($category['type'] == 2) {
                $arr['icon'] = $extra['icon'];
                $arr['first'] = $extra['first'];
                $arr['children'] = $extra['children'];
            }
            $res[] = $arr;
        }
        return $this->success('success', ['type' => $category['type'], 'title' => $category['category_name'], 'list' => $res, 'page' => $page, 'total_page' => $totalPage]);
    }

    public function deleteAction()
    {
        $params = \App::$request->params->toArray();
        if (empty($params['id'])) {
            throw new \Exception('参数有误');
        }
        \Db::table('Message')->where([
            'id' => $params['id'],
            'user_id' => \App::$user['user_id']
        ])->update(['status' => 0]);
        return $this->success('success');
    }
}