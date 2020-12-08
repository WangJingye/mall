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
            $message = \Db::table('Message')->where(['category_id' => $v['category_id']])->where(['status' => 1])->order('update_time desc')->limit(1)->find();
            $arr = [];
            $arr['content'] = $message['content'];
            $arr['category_name'] = $v['category_name'];
            $arr['pic'] = $v['pic'];
            $arr['last_time'] = $message['update_time'];
            $res[] = $arr;
        }
        return $this->success('success', $res);
    }
}