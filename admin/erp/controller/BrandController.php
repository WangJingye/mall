<?php

namespace admin\erp\controller;

use admin\common\controller\BaseController;
use admin\erp\service\BrandService;

class BrandController extends BaseController
{
    /** @var BrandService */
    public $brandService;

    public function init()
    {
        $this->brandService = new BrandService();
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
        /** @var BrandService $res */
        $res = $this->brandService->getList($params);
        $this->assign('params', $params);
        $this->assign('pagination', $this->pagination($res));
        $this->assign('list', $res->list);
    }

    /**
     * @throws \Exception
     */
    public function editAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                $params['logo'] = $this->parseFileOrUrl('logo', 'erp/brand');
                $this->brandService->saveBrand($params);
                return $this->success('保存成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
        $this->title = '创建品牌';
        if (isset($params['brand_id']) && $params['brand_id']) {
            $model = \Db::table('Brand')->where(['brand_id' => $params['brand_id']])->find();
            if (!$model) {
                throw new \Exception('数据不存在');
            }
            $this->assign('model', $model);
            $this->title = '编辑品牌 - ' . $model['brand_id'];
        }
    }

    /**
     * @throws \Exception
     */
    public function deleteAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                if (!isset($params['brand_id']) || $params['brand_id'] == '') {
                    throw new \Exception('非法请求');
                }
                $this->brandService->deleteBrand($params);
                return $this->success('删除成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function setSortAction()
    {
        try {
            $params = \App::$request->params->toArray();
            if (empty($params['id']) || empty($params['sort'])) {
                throw new \Exception('非法请求');
            }
            \Db::table('Brand')->where(['brand_id' => $params['id']])->update(['sort' => $params['sort']]);
            return $this->success('设置成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}