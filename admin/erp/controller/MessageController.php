<?php

namespace admin\erp\controller;

use admin\common\controller\BaseController;
use admin\erp\service\MessageService;

class MessageController extends BaseController
{
    /** @var MessageService */
    public $messageService;

    public function init()
    {
        $this->messageService = new MessageService();
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
        /** @var MessageService $res */
        $res = $this->messageService->getList($params);
        $this->assign('params', $params);
        $this->assign('pagination', $this->pagination($res));
        $this->assign('list', $res->list);
        $categoryList = \Db::table('MessageCategory')->field(['category_name', 'categoryList'])->findAll();
        $categoryList = array_column($categoryList, 'categoryList', 'category_name');
        $this->assign('categoryList', $categoryList);
    }

    /**
     * @throws \Exception
     */
    public function editAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                $this->messageService->saveMessage($params);
                return $this->success('保存成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
        $this->title = '创建消息';
        if (!empty($params['id'])) {
            $model = \Db::table('Message')->where(['id' => $params['id']])->find();
            if (!$model) {
                throw new \Exception('数据不存在');
            }
            $this->assign('model', $model);
            $this->title = '编辑消息 - ' . $model['id'];
        }
        $categoryList = \Db::table('MessageCategory')->field(['category_name', 'categoryList'])->findAll();
        $categoryList = array_column($categoryList, 'categoryList', 'category_name');
        $this->assign('categoryList', $categoryList);
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
                \Db::table('Message')->where(['id' => $params['id']])->delete();
                return $this->success('删除成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }
}