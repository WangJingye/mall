<?php

namespace admin\erp\controller;

use admin\common\controller\BaseController;
use admin\erp\service\MessageCategoryService;

class MessageCategoryController extends BaseController
{
    /** @var MessageCategoryService */
    public $messageCategoryService;
    public $statusList = [
        '1' => '使用中',
        '0' => '已删除',
    ];

    public function init()
    {
        $this->messageCategoryService = new MessageCategoryService();
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
        /** @var MessageCategoryService $res */
        $res = $this->messageCategoryService->getList($params);
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
                $params['pic'] = $this->parseFileOrUrl('pic','erp/message-category');
                $this->messageCategoryService->saveMessageCategory($params);
                return $this->success('保存成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
        $this->title = '创建消息分类';
        if (!empty($params['category_id'])) {
            $model = \Db::table('MessageCategory')->where(['category_id' => $params['category_id']])->find();
            if (!$model) {
                throw new \Exception('数据不存在');
            }
            $this->assign('model', $model);
            $this->title = '编辑消息分类 - ' . $model['category_id'];
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
                \Db::table('MessageCategory')->where(['category_id' => $params['id']])->delete();
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
                \Db::table('MessageCategory')->where(['category_id' => $params['id']])->update(['status' => $params['status']]);
                return $this->success($params['status'] == 1 ? '已启用' : '已禁用');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }
}