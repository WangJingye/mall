<?php

namespace admin\erp\controller;

use admin\common\controller\BaseController;
use admin\erp\service\GrouponService;

class GrouponController extends BaseController
{
    /** @var GrouponService */
    public $grouponService;
    public $statusList = [
        '1' => '等待',
        '2' => '进行中',
        '3' => '已结束',
    ];

    public function init()
    {
        $this->grouponService = new GrouponService();
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
        /** @var GrouponService $res */
        $res = $this->grouponService->getList($params);
        $list = $res->list;
        $productList = $this->grouponService->getDataList('Product', 'product_id', 'product_name', ['product_id' => ['in', array_column($list, 'product_id')]]);
        $userList = $this->grouponService->getDataList('Admin', 'admin_id', 'username', ['admin_id' => ['in', array_column($list, 'create_userid')]]);
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
                \Db::startTrans();
                $this->grouponService->saveGroupon($params);
                \Db::commit();
                return $this->success('保存成功');
            } catch (\Exception $e) {
                \Db::rollback();
                return $this->error($e->getMessage());
            }
        }
        $this->title = '创建团购';
        $variationList = [];
        if (!empty($params['product_id'])) {
            $product = \Db::table('Product')->where(['product_id' => $params['product_id']])->find();
            $this->assign('product', $product);
        }
        if (!empty($params['id'])) {
            $model = \Db::table('Groupon')->where(['id' => $params['id']])->find();
            if (!$model) {
                throw new \Exception('数据不存在');
            }
            $this->assign('model', $model);
            $this->title = '编辑团购 - ' . $model['id'];
            $product = \Db::table('Product')->where(['product_id' => $model['product_id']])->find();
            $this->assign('product', $product);
            $variationList = \Db::table('GrouponVariation')->where(['go_id' => $params['id']])->findAll();
        }
        $this->assign('variationList', $variationList);
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
                \Db::table('Groupon')->where(['id' => $params['id']])->update(['status' => 0]);
                return $this->success('删除成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function stopAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                if (empty($params['id'])) {
                    throw new \Exception('非法请求');
                }
                \Db::table('Groupon')->where(['id' => $params['id']])->update(['status' => 3]);
                return $this->success('团购已结束');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }
}