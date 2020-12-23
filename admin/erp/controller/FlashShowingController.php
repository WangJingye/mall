<?php

namespace admin\erp\controller;

use admin\common\controller\BaseController;
use admin\erp\service\FlashShowingService;

class FlashShowingController extends BaseController
{
    /** @var FlashShowingService */
    public $flashShowingService;

    public function init()
    {
        $this->flashShowingService = new FlashShowingService();
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
        /** @var FlashShowingService $res */
        $res = $this->flashShowingService->getList($params);
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
                $checks = ['start_time' => '开始时间', 'end_time' => '结束时间'];
                foreach ($checks as $k => $v) {
                    if (empty($params[$k])) {
                        throw new \Exception('请输入' . $v);
                    }
                    if (strtotime(date('Y-m-d') . ' ' . $params[$k] ) === false) {
                        throw new \Exception($v . '有误');
                    }
                }
                $this->flashShowingService->saveFlashShowing($params);
                return $this->success('保存成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
        $this->title = '创建团购场次';
        if (!empty($params['show_id'])) {
            $model = \Db::table('FlashShowing')->where(['show_id' => $params['show_id']])->find();
            if (!$model) {
                throw new \Exception('数据不存在');
            }
            $this->assign('model', $model);
            $this->title = '编辑团购场次 - ' . $model['show_id'];
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
                \Db::table('FlashShowing')->where(['show_id' => $params['id']])->update(['status' => 0]);
                return $this->success('删除成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }
}