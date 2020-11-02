<?php

namespace admin\erp\controller;

use admin\common\controller\BaseController;
use admin\erp\service\CategoryService;

class CategoryController extends BaseController
{
    /** @var CategoryService */
    public $categoryService;

    public function init()
    {
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
        /** @var CategoryService $res */
        $list = $this->categoryService->getList($params, false);
        $this->assign('params', $params);
        $this->assign('list', $list);
        $this->assign('boolList', $this->boolList);
    }

    /**
     * @throws \Exception
     */
    public function editAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                $params['pic'] = $this->parseFileOrUrl('pic', 'erp/category');
                $this->categoryService->saveCategory($params);
                return $this->success('保存成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
        $this->title = '创建分类';
        $id = 0;
        if (!empty($params['add_type'])) {
            $this->assign('add_type', $params['add_type']);
            if (empty($params['parent_id'])) {
                throw new \Exception('参数有误');
            }
            $this->assign('pid', $params['parent_id']);
        }
        if (isset($params['category_id']) && $params['category_id']) {
            $model = \Db::table('ProductCategory')->where(['category_id' => $params['category_id']])->find();
            if (!$model) {
                throw new \Exception('数据不存在');
            }
            $this->assign('model', $model);
            $this->title = '编辑分类 - ' . $model['category_id'];
            $id = $params['category_id'];
        }
        $this->assign('childList', array_column($this->categoryService->getChild($id), 'name', 'id'));
        $this->assign('boolList', $this->boolList);
    }

    /**
     * @throws \Exception
     */
    public function deleteAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                if (!isset($params['category_id']) || $params['category_id'] == '') {
                    throw new \Exception('非法请求');
                }
                $this->categoryService->deleteCategory($params);
                return $this->success('删除成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }

    public function getChildListAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                if (!isset($params['id']) || $params['id'] == '') {
                    throw new \Exception('非法请求');
                }
                $list = \Db::table('ProductCategory')
                    ->where(['parent_id' => $params['id']])
                    ->where(['status' => 1])
                    ->order('category_id desc')
                    ->findAll();
                $this->assign('list', $list);
                $this->assign('hasChildList', $this->hasChildList);
                $this->assign('pid', $params['id']);
                $html = $this->renderPartial('erp/category/_info');
                return $this->success('success', ['html' => $html, 'is_empty' => count($list) ? 0 : 1]);
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }

    }
}