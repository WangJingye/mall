<?php

namespace admin\erp\controller;

use admin\common\controller\BaseController;
use admin\erp\service\CouponUserService;

class CouponUserController extends BaseController
{
    /** @var CouponUserService */
    public $couponUserService;
    public $statusList = [
        '1' => '未使用',
        '2' => '已使用',
        '3' => '已过期',
    ];

    public function init()
    {
        $this->couponUserService = new CouponUserService();
        parent::init();
    }

    /**
     * @throws \Exception
     */
    public function indexAction()
    {
        $params = \App::$request->params;
        $params['page'] = \App::$request->getParams('page', 1);
        $params['pageSize'] = \App::$request->getParams('pageSize', 10);
        if (!empty($params['search_type'])) {
            $params[$params['search_type']] = $params['search_value'];
        }
        /** @var CouponUserService $res */
        $res = $this->couponUserService->getList($params);
        $list = $res->list;
        $this->assign('params', $params);
        $this->assign('pagination', $this->pagination($res));
        $this->assign('list', $list);
        $userIdList = array_column($list, 'user_id');
        if (!empty($params['user_id'])) {
            $userIdList[] = $params['user_id'];
        }
        $userList = $this->couponUserService->getDataList('User', 'user_id', 'nickname', ['user_id' => ['in', $userIdList]]);
        $couponList = $this->couponUserService->getDataList('Coupon', 'coupon_id', 'title');
        $this->assign('couponList', $couponList);
        $this->assign('userList', $userList);
        $this->assign('statusList', $this->statusList);
    }

    /**
     * @throws \Exception
     */
    public function editAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                $this->couponUserService->saveCouponUser($params);
                return $this->success('保存成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
        $this->title = '优惠券发放';
        if (isset($params['id']) && $params['id']) {
            $model = \Db::table('CouponUser')->where(['id' => $params['id']])->find();
            if (!$model) {
                throw new \Exception('数据不存在');
            }
            $this->assign('model', $model);
            $this->title = '编辑优惠券发放 - ' . $model['id'];
        }
        $couponList = \Db::table('Coupon')->field(['coupon_id', 'title'])->findAll();
        $couponList = array_column($couponList, 'title', 'coupon_id');
        $this->assign('couponList', $couponList);
        $this->assign('statusList', $this->statusList);
    }

    /**
     * @throws \Exception
     */
    public function deleteAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                if (!isset($params['id']) || $params['id'] == '') {
                    throw new \Exception('非法请求');
                }
                $this->couponUserService->deleteCouponUser($params);
                return $this->success('删除成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }

    public function searchAction()
    {
        $params = \App::$request->params;
        $params['page'] = \App::$request->getParams('page', 1);
        $params['pageSize'] = \App::$request->getParams('pageSize', 5);
        if (!empty($params['search_type'])) {
            $params[$params['search_type']] = $params['search_value'];
        }
        /** @var CouponUserService $res */
        $res = $this->couponUserService->getList($params);
        $this->assign('pagination', $this->paginationJs($res));
        $this->assign('list', $res->list);
        $this->assign('params', $params);
        $this->assign('typeList', $this->couponUserService->typeList);
        $html = $this->renderPartial('erp/coupon-user/_search');
        return $this->success('success', ['html' => $html]);
    }
}