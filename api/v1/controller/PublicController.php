<?php

namespace api\v1\controller;

use api\v1\service\UserService;
use common\extend\wechat\Wechat;

class PublicController extends BaseController
{
    /** @var UserService */
    public $userService;

    public function init()
    {
        $this->userService = new UserService();
        parent::init();
    }

    public function loginAction()
    {
        $params = \App::$request->params->toArray();
        $checkList = [
            'nickname' => '昵称不能为空',
            'avatar' => '头像不能为空',
            'city' => '城市不能为空',
            'gender' => '性别不能为空',
            'code' => 'code不能为空',
        ];
        foreach ($checkList as $field => $message) {
            if (!isset($params[$field]) || $params[$field] == '') {
                throw new \Exception($message);
            }
        }
        $res = $this->userService->getUserInfo($params);
        return $this->success('success', $res);
    }

    public function getUserInfoAction()
    {
        $header = \App::$request->header;
        if (empty($header['identity'])) {
            throw new \Exception('未登录', 999);
        }
        $params['openid'] = $header['identity'];
        $res = $this->userService->getUserInfo($params);
        return $this->success('success', $res);
    }

    public function getIdentityByCodeAction()
    {
        $params = \App::$request->params->toArray();
        if (empty($params['code'])) {
            throw new \Exception('参数有误');
        }
        $openid = Wechat::instance()->getOpenIdByCode($params['code']);
        $user = \Db::table('User')
            ->where(['openid' => $openid])
            ->find();
        $res = [];
        if ($user) {
            $res['user_id'] = $user['user_id'];
            $res['nickname'] = $user['nickname'];
            $res['avatar'] = $user['avatar'];
            $res['identity'] = $user['openid'];
            $res['is_promoter'] = $user['is_promoter'];
        }
        return $this->success('success', $res);
    }

    public function indexAction()
    {
        $carousels = \Db::table('Carousel')
            ->field(['carousel_id', 'title', 'pic', 'link_type', 'link'])
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
            ->where(['status' => ['in', [2]]])
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
            $flashSales[$k]['show_time'] = $this->getLeftTime($flashSales[$k]['left_time']);
        }
        $res['flashsales'] = $flashSales;
        $groupons = \Db::table('Groupon')
            ->field(['id', 'title', 'price', 'end_time', 'group_user_number', 'pic', 'status'])
            ->where(['show_home' => 1])
            ->where(['status' => 2])
            ->findAll();
        foreach ($groupons as $k => $v) {
            $left = $v['end_time'] - time();
            $groupons[$k]['left_time'] = $left;
            $groupons[$k]['show_time'] = $this->getLeftTime($left);
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