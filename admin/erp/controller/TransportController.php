<?php

namespace admin\erp\controller;

use admin\common\controller\BaseController;
use admin\erp\service\TransportService;

class TransportController extends BaseController
{
    /** @var TransportService */
    public $transportService;
    public $statusList = [
        '1' => '启用中',
        '2' => '禁用中',
    ];

    public function init()
    {
        $this->transportService = new TransportService();
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
        /** @var TransportService $res */
        $res = $this->transportService->getList($params);
        $this->assign('params', $params);
        $this->assign('pagination', $this->pagination($res));
        $this->assign('list', $res->list);
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
                $this->transportService->saveTransport($params);
                return $this->success('保存成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
        $this->title = '创建物流方式';
        if (isset($params['transport_id']) && $params['transport_id']) {
            $model = \Db::table('Transport')->where(['transport_id' => $params['transport_id']])->find();
            if (!$model) {
                throw new \Exception('数据不存在');
            }
            $this->assign('model', $model);
            $this->title = '编辑物流方式 - ' . $model['transport_id'];
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
                if (!isset($params['transport_id']) || $params['transport_id'] == '') {
                    throw new \Exception('非法请求');
                }
                $this->transportService->deleteTransport($params);
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
                if (!isset($params['id']) || $params['id'] == '') {
                    throw new \Exception('非法请求');
                }
                \Db::table('Transport')->where(['transport_id' => $params['id']])->update(['status' => $params['status']]);
                return $this->success($params['status'] == 1 ? '已启用' : '已禁用');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }
}