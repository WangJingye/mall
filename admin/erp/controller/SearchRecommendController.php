<?php

namespace admin\erp\controller;

use admin\common\controller\BaseController;
use admin\erp\service\SearchRecommendService;

class SearchRecommendController extends BaseController
{
    /** @var SearchRecommendService */
    public $searchRecommendService;

    public function init()
    {
        $this->searchRecommendService = new SearchRecommendService();
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
        /** @var SearchRecommendService $res */
        $res = $this->searchRecommendService->getList($params);
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
                $this->searchRecommendService->saveSearchRecommend($params);
                return $this->success('保存成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
        $this->title = '创建搜索页推荐';
        if (!empty($params['id'])) {
            $model = \Db::table('SearchRecommend')->where(['id' => $params['id']])->find();
            if (!$model) {
                throw new \Exception('数据不存在');
            }
            $this->assign('model', $model);
            $this->title = '编辑搜索页推荐 - ' . $model['id'];
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
                if (empty($params['id'])) {
                    throw new \Exception('非法请求');
                }
                \Db::table('SearchRecommend')->where(['id' => $params['id']])->delete();
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
            \Db::table('SearchRecommend')->where(['id' => $params['id']])->update(['sort' => $params['sort']]);
            return $this->success('设置成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}