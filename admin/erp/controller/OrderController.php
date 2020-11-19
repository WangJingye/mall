<?php

namespace admin\erp\controller;

use admin\common\controller\BaseController;
use admin\erp\service\OrderService;
use common\helper\Constant;

class OrderController extends BaseController
{
    /** @var OrderService */
    public $orderService;

    public function init()
    {
        $this->orderService = new OrderService();
        parent::init();
    }

    /**
     * 订单列表
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
            $this->orderService->export($params);
        }
        /** @var OrderService $res */
        $res = $this->orderService->getList($params);
        $list = $res->list;
        $userIdList = array_column($list, 'user_id');
        $userList = \Db::table('User')
            ->field(['user_id', 'telephone', 'nickname'])
            ->where(['user_id' => ['in', $userIdList]])
            ->findAll();
        $userList = array_column($userList, null, 'user_id');
        $this->assign('userList', $userList);
        $this->assign('params', $params);
        $this->assign('pagination', $this->pagination($res));
        $this->assign('list', $list);
        $transportList = $this->orderService->getDataList('Transport', 'transport_id', 'transport_name');
        $this->assign('transportList', $transportList);
        $this->assign('statusList', $this->orderService->statusList);
        $payMethodList = $this->orderService->getDataList('PayMethod', 'method_id', 'name');
        $this->assign('payMethodList', $payMethodList);
        $this->assign('orderTypeList', $this->orderService->orderTypeList);
        $this->assign('orderGroupList', $this->orderService->orderGroupList);
    }

    /**
     * @throws \Exception
     */
    public function editAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                \Db::startTrans();
                $params['add_type'] = 1;
                $params['order_group'] = Constant::ORDER_GROUP_NORMAL;
                $this->orderService->saveOrder($params);
                \Db::commit();
                return $this->success('保存成功');
            } catch (\Exception $e) {
                \Db::rollback();
                return $this->error($e->getMessage());
            }
        }
        $this->title = '添加订单';
        $variationList = $productList = [];
        if (isset($params['order_id']) && $params['order_id']) {
            $model = \Db::table('Order')->where(['order_id' => $params['order_id']])->find();
            if (!$model) {
                throw new \Exception('数据不存在');
            }
            $this->assign('model', $model);
            $user = \Db::table('User')->field(['user_id', 'nickname'])->where(['user_id' => $model['user_id']])->find();
            $this->assign('user', $user);
            $variationList = \Db::table('OrderVariation')->where(['order_id' => $model['order_id']])->findAll();
            $productList = \Db::table('Product')->where(['product_id' => ['in', array_column($variationList, 'product_id')]])->findAll();
            $productList = array_column($productList, null, 'product_id');
            if (!empty($model['coupon_id'])) {
                $coupon = \Db::table('CouponUser')->where(['id' => $model['coupon_id']])->find();
                $this->assign('coupon', $coupon);
            }
            $this->title = '编辑订单 - ' . $model['order_title'];
        }

        $transportList = $this->orderService->getDataList('Transport', 'transport_id', 'transport_name');
        $this->assign('transportList', $transportList);
        $this->assign('orderTypeList', $this->orderService->orderTypeList);
        $freightList = \Db::table('FreightTemplate')->where(['status' => 1])->findAll();
        $freightList = array_column($freightList, null, 'freight_id');
        $this->assign('freightList', $freightList);
        $this->assign('productList', $productList);
        $this->assign('variationList', $variationList);
    }

    /**
     * 删除订单
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
                $this->orderService->deleteOrder($params);
                return $this->success('订单已删除');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }


    /**
     * 关闭订单
     * @throws \Exception
     */
    public function closeAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                if (!isset($params['id']) || $params['id'] == '') {
                    throw new \Exception('非法请求');
                }
                $this->orderService->close($params);
                return $this->success('订单已关闭');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }

    /**
     * 发货订单
     * @throws \Exception
     */
    public function shipAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                if (!isset($params['id']) || $params['id'] == '') {
                    throw new \Exception('非法请求');
                }
                $this->orderService->ship($params);
                return $this->success('订单已发货');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }

    /**
     * 完成订单
     * @throws \Exception
     */
    public function completeAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                if (!isset($params['id']) || $params['id'] == '') {
                    throw new \Exception('非法请求');
                }
                \Db::startTrans();
                $this->orderService->complete($params);
                \Db::commit();
                return $this->success('订单已完成');
            } catch (\Exception $e) {
                \Db::rollback();
                return $this->error($e->getMessage());
            }
        }
    }

    /**
     * 收货/使用电子券
     * @throws \Exception
     */
    public function receiveAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                if (!isset($params['id']) || $params['id'] == '') {
                    throw new \Exception('非法请求');
                }
                \Db::startTrans();
                $this->orderService->receive($params);
                \Db::commit();
                return $this->success('订单已完成');
            } catch (\Exception $e) {
                \Db::rollback();
                return $this->error($e->getMessage());
            }
        }
    }

    /**
     * 详情
     * @throws \Exception
     */
    public function detailAction()
    {
        $params = \App::$request->params->toArray();
        if (empty($params['order_id'])) {
            throw new \Exception('参数有误');
        }
        $order = \Db::table('Order')->where(['order_id' => $params['order_id']])->find();
        $variationList = \Db::table('OrderVariation')->where(['order_id' => $order['order_id']])->findAll();
        $user = \Db::table('User')->where(['user_id' => $order['user_id']])->find();
        $this->assign('order', $order);
        $this->assign('variationList', $variationList);
        $this->assign('user', $user);
        $this->assign('statusList', $this->orderService->statusList);
        if ($order['status'] >= Constant::ORDER_STATUS_SHIPPED) {
            $transport = \Db::table('Transport')->where(['transport_id' => $order['transport_id']])->find();
            $this->assign('transport', $transport);
        }
        $traceList = \Db::table('OrderTrace')->where(['order_id' => $order['order_id']])->order('id desc')->findAll();
        $this->assign('traceList', $traceList);
        $operatorList = $this->orderService->getOperateUserList($traceList);
        $this->assign('operatorList', $operatorList);
        $this->assign('orderTypeList', $this->orderService->orderTypeList);
        $this->assign('orderGroupList', $this->orderService->orderGroupList);
        if (!empty($order['coupon_id'])) {
            $coupon = \Db::table('CouponUser')->where(['id' => $order['coupon_id']])->find();
            $this->assign('coupon', $coupon);
        }
    }

    /**
     * 手动支付/收款
     * @throws \Exception
     */
    public function payAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                if (empty($params['id']) || empty($params['pay_method'])) {
                    throw new \Exception('非法请求');
                }
                \Db::startTrans();
                $this->orderService->pay($params);
                \Db::commit();
                return $this->success('订单已收款');
            } catch (\Exception $e) {
                \Db::rollback();
                return $this->error($e->getMessage());
            }
        }
    }

}