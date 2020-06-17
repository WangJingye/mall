<?php

namespace admin\erp\controller;

use admin\common\controller\BaseController;
use admin\erp\service\OrderService;
use admin\erp\service\ProductService;
use admin\erp\service\UserService;
use admin\extend\Constant;

class UserController extends BaseController
{
    /** @var UserService */
    public $userService;

    /** @var ProductService */
    public $productService;

    /** @var OrderService */
    public $orderService;

    public $userTypeList = [
        '1' => '普通会员',
        '2' => '企业会员',
    ];

    public function init()
    {
        $this->userService = new UserService();
        $this->productService = new ProductService();
        $this->orderService = new OrderService();
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
        if (!empty($params['export_type'])) {
            $this->userService->export($params);
        }
        /** @var UserService $res */
        $res = $this->userService->getList($params);
        $list = $res->list;
        $this->assign('params', $params);
        $this->assign('pagination', $this->pagination($res));
        $this->assign('list', $list);
        $this->assign('userTypeList', $this->userTypeList);
        $this->assign('genderList', $this->userService->genderList);
        $sales = \Db::table('UserBill')
            ->field(['sum(amount) as amount', 'user_id'])
            ->where(['user_id' => ['in', array_column($list, 'user_id')]])
            ->where(['bill_type' => Constant::BILL_TYPE_PAY])
            ->group('user_id')
            ->findAll();
        foreach ($sales as $v) {
            $saleList[$v['user_id']]['amount'] = $v['amount'];
        }
        $sales = \Db::table('UserBill')
            ->field(['count(*) as number', 'user_id'])
            ->where(['user_id' => ['in', array_column($list, 'user_id')]])
            ->where(['bill_type' => Constant::BILL_TYPE_PAY])
            ->where(['relation_type' => Constant::BILL_RELATION_ORDER])
            ->group('user_id')
            ->findAll();
        foreach ($sales as $v) {
            $saleList[$v['user_id']]['number'] = $v['number'];
        }
        $this->assign('saleList', $saleList);
    }

    /**
     * @throws \Exception
     */
    public function setStatusAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                if (!isset($params['id']) || $params['id'] == '') {
                    throw new \Exception('非法请求');
                }
                \Db::table('User')->where(['user_id' => $params['id']])->update(['status' => $params['status']]);
                return $this->success($params['status'] == 1 ? '已解禁' : '已禁用');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }

    /**
     * 用户详情
     * @throws \Exception
     */
    public function detailAction()
    {
        $params = \App::$request->params->toArray();
        if (empty($params['user_id'])) {
            throw new \Exception('请求有误');
        }
        $model = \Db::table('User')->where(['user_id' => $params['user_id']])->find();
        if (!$model) {
            throw new \Exception('数据不存在');
        }
        $this->title = '查看普通会员 - ' . $model['nickname'];
        $addressList = \Db::table('UserAddress')->where(['user_id' => $model['user_id']])->where(['status' => 1])->findAll();
        $this->assign('model', $model);
        $this->assign('genderList', $this->userService->genderList);
        $this->assign('addressList', $addressList);
        $orderList = \Db::table('Order')
            ->where(['user_id' => $model['user_id']])
            ->where(['status' => ['>', 0]])
            ->order('order_id desc')
            ->limit(5)
            ->findAll();
        $this->assign('orderList', $orderList);
        $this->assign('orderStatusList', $this->orderService->statusList);
        $this->assign('orderVirtualStatusList', $this->orderService->virtualStatusList);
        $this->assign('orderTypeList', $this->orderService->orderTypeList);
    }

    /**
     * 获取用户订单
     * @return array
     * @throws \Exception
     */
    public function getOrderListAction()
    {
        $params['page'] = \App::$request->getParams('page', 2);
        $params['pageSize'] = \App::$request->getParams('pageSize', 5);
        $params['user_id'] = \App::$request->getParams('id');
        if (empty($params['user_id'])) {
            throw new \Exception('参数有误');
        }
        $limit = ($params['page'] - 1) * $params['pageSize'] . ',' . $params['pageSize'];
        $list = \Db::table('Order')
            ->where(['user_id' => $params['user_id']])
            ->where(['status' => ['>', 0]])
            ->order('order_id desc')
            ->limit($limit)
            ->findAll();
       return $this->success('获取成功', $list);
    }

    /**
     * 设置默认地址
     * @throws \Exception
     */
    public function setDefaultAddressAction()
    {
        $params = \App::$request->params->toArray();
        if (empty($params['id']) || empty($params['user_id'])) {
            throw new \Exception('请求有误');
        }
        \Db::table('UserAddress')->where(['user_id' => $params['user_id']])->update(['is_default' => 0]);
        \Db::table('UserAddress')->where(['address_id' => $params['id']])->update(['is_default' => 1]);
        return $this->success('设置成功');
    }

    /**
     * 用户查询
     * @throws \Exception
     */
    public function searchAction()
    {
        $params = \App::$request->params;
        $params['page'] = \App::$request->getParams('page', 1);
        $params['pageSize'] = \App::$request->getParams('pageSize', 5);
        if (!empty($params['search_type'])) {
            $params[$params['search_type']] = $params['search_value'];
        }
        /** @var UserService $res */
        $res = $this->userService->getList($params);
        $this->assign('pagination', $this->paginationJs($res));
        $this->assign('list', $res->list);
        $this->assign('params', $params);
        $html = $this->renderPartial('erp/user/_search');
        return $this->success('success', ['html' => $html]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function editAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                $params['avatar'] = $this->parseFileOrUrl('avatar', 'erp/user');
                \Db::startTrans();
                $this->userService->saveUser($params);
                \Db::commit();
                return $this->success('保存成功');
            } catch (\Exception $e) {
                \Db::rollback();
                return $this->error($e->getMessage());
            }
        }
        $this->title = '添加新用户';
        if (isset($params['user_id']) && $params['user_id']) {
            $model = \Db::table('User')->where(['user_id' => $params['user_id']])->find();
            if (!$model) {
                throw new \Exception('数据不存在');
            }
            $this->assign('model', $model);
            $this->title = '编辑普通用户 - ' . $model['nickname'];
        }
        $this->assign('genderList', $this->userService->genderList);
    }

}