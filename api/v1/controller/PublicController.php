<?php

namespace api\v1\controller;

use api\v1\service\UserService;

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