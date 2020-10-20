<?php

namespace admin\erp\controller;

use admin\common\controller\BaseController;
use admin\erp\service\FlashSaleService;

class FlashSaleController extends BaseController
{
    /** @var FlashSaleService */
    public $flashSaleService;
    public $statusList = [
        '1' => '等待',
        '2' => '进行中',
        '3' => '已结束',
    ];

    public function init()
    {
        $this->flashSaleService = new FlashSaleService();
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
        /** @var FlashSaleService $res */
        $res = $this->flashSaleService->getList($params);
        $list = $res->list;
        $productList = $this->flashSaleService->getDataList('Product', 'product_id', 'product_name', ['product_id' => ['in', array_column($list, 'product_id')]]);
        $userList = $this->flashSaleService->getDataList('Admin', 'admin_id', 'username', ['admin_id' => ['in', array_column($list, 'create_userid')]]);
        $this->assign('userList', $userList);
        $this->assign('productList', $productList);
        $this->assign('params', $params);
        $this->assign('pagination', $this->pagination($res));
        $this->assign('list', $list);
        $this->assign('statusList', $this->statusList);
        if (!empty($params['product_id'])) {
            $product = \Db::table('Product')->where(['product_id' => $params['product_id']])->find();
            $this->assign('product', $product);
        }
    }

    /**
     * @throws \Exception
     */
    public function editAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                $params['create_userid'] = \App::$user['admin_id'];
                $this->flashSaleService->saveFlashSale($params);
                return $this->success('保存成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
        $this->title = '创建秒杀';
        if (!empty($params['product_id'])) {
            $product = \Db::table('Product')->where(['product_id' => $params['product_id']])->find();
            $this->assign('product', $product);
        }
        if (!empty($params['flash_id'])) {
            $model = \Db::table('FlashSale')->where(['flash_id' => $params['flash_id']])->find();
            if (!$model) {
                throw new \Exception('数据不存在');
            }
            $this->assign('model', $model);
            $product = \Db::table('Product')->where(['product_id' => $model['product_id']])->find();
            $this->assign('product', $product);
            $this->title = '编辑秒杀 - ' . $model['flash_id'];
        }
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
                if (empty($params['id'])) {
                    throw new \Exception('非法请求');
                }
                \Db::table('FlashSale')->where(['flash_id' => $params['id']])->update(['status' => 0]);
                return $this->success('删除成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }
}