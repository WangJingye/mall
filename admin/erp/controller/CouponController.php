<?php

namespace admin\erp\controller;

use admin\common\controller\BaseController;
use admin\erp\service\CategoryService;
use admin\erp\service\CouponService;

class CouponController extends BaseController
{
    /** @var CouponService */
    public $couponService;
    /** @var CategoryService */
    public $categoryService;
    public $statusList = [
        '1' => '可用',
        '2' => '禁用',
    ];

    public function init()
    {
        $this->couponService = new CouponService();
        $this->categoryService = new CategoryService();
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
        /** @var CouponService $res */
        $res = $this->couponService->getList($params);
        $this->assign('params', $params);
        $this->assign('pagination', $this->pagination($res));
        $this->assign('list', $res->list);
        $this->assign('statusList', $this->statusList);
        $this->assign('typeList', $this->couponService->typeList);
        $this->assign('boolList', $this->typeList);
    }

    /**
     * @throws \Exception
     */
    public function editAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                $this->couponService->saveCoupon($params);
                return $this->success('保存成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
        $this->title = '创建优惠券';
        if (isset($params['coupon_id']) && $params['coupon_id']) {
            $model = \Db::table('Coupon')->where(['coupon_id' => $params['coupon_id']])->find();
            if (!$model) {
                throw new \Exception('数据不存在');
            }
            $this->assign('model', $model);
            $this->title = '编辑优惠券 - ' . $model['coupon_id'];
        }
        $this->assign('typeList', $this->couponService->typeList);
        $this->assign('categoryList', $this->categoryService->getAllCategory());
        $this->assign('statusList', $this->statusList);
        $this->assign('boolList', $this->typeList);
    }

    /**
     * @throws \Exception
     */
    public function deleteAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                if (!isset($params['coupon_id']) || $params['coupon_id'] == '') {
                    throw new \Exception('非法请求');
                }
                $this->couponService->deleteCoupon($params);
                return $this->success('删除成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function setStatusAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                if (empty($params['id'])) {
                    throw new \Exception('非法请求');
                }
                \Db::table('Coupon')->where(['coupon_id' => $params['id']])->update(['status' => $params['status']]);
                return $this->success($params['status'] == 1 ? '已启用' : '已禁用');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }

}