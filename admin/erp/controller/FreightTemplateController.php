<?php

namespace admin\erp\controller;

use admin\common\controller\BaseController;
use admin\erp\service\FreightTemplateService;

class FreightTemplateController extends BaseController
{
    /** @var FreightTemplateService */
    public $freightTemplateService;
    public $freightTypeList = [
        '1' => '按件',
        '2' => '按重量',
    ];

    public function init()
    {
        $this->freightTemplateService = new FreightTemplateService();
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
        /** @var FreightTemplateService $res */
        $res = $this->freightTemplateService->getList($params);
        $this->assign('params', $params);
        $this->assign('pagination', $this->pagination($res));
        $this->assign('list', $res->list);
        $this->assign('freightTypeList', $this->freightTypeList);
    }

    /**
     * @throws \Exception
     */
    public function editAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                $this->freightTemplateService->saveFreightTemplate($params);
                return $this->success('保存成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
        $this->title = '创建运费模版';
        if (isset($params['freight_id']) && $params['freight_id']) {
            $model = \Db::table('FreightTemplate')->where(['freight_id' => $params['freight_id']])->find();
            if (!$model) {
                throw new \Exception('数据不存在');
            }
            $this->assign('model', $model);
            $this->title = '编辑运费模版 - ' . $model['freight_id'];
        }
        $this->assign('freightTypeList', $this->freightTypeList);
    }

    /**
     * @throws \Exception
     */
    public function deleteAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                if (!isset($params['freight_id']) || $params['freight_id'] == '') {
                    throw new \Exception('非法请求');
                }
                $this->freightTemplateService->deleteFreightTemplate($params);
                return $this->success('删除成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }
}