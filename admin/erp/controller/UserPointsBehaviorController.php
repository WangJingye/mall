<?php

namespace admin\erp\controller;

use admin\common\controller\BaseController;
use admin\erp\service\UserPointsBehaviorService;

class UserPointsBehaviorController extends BaseController
{
    /** @var UserPointsBehaviorService */
    public $userPointsBehaviorService;
    public $typeList = [
        '1' => '每日',
        '2' => '总共',
    ];

    public function init()
    {
        $this->userPointsBehaviorService = new UserPointsBehaviorService();
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
        /** @var UserPointsBehaviorService $res */
        $res = $this->userPointsBehaviorService->getList($params);
        $this->assign('params', $params);
        $this->assign('pagination', $this->pagination($res));
        $this->assign('list', $res->list);
        $this->assign('typeList', $this->typeList);
        $this->assign('statusList', $this->userPointsBehaviorService->statusList);
    }

    /**
     * @throws \Exception
     */
    public function editAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                $this->userPointsBehaviorService->saveUserPointsBehavior($params);
                return $this->success('保存成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
        $this->title = '创建用户积分行为';
        if (isset($params['behavior_id']) && $params['behavior_id']) {
            $model = \Db::table('UserPointsBehavior')->where(['behavior_id' => $params['behavior_id']])->find();
            if (!$model) {
                throw new \Exception('数据不存在');
            }
            $this->assign('model', $model);
            $this->title = '编辑用户积分行为 - ' . $model['behavior_id'];
        }
        $this->assign('typeList', $this->typeList);
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
                \Db::table('UserPointsBehavior')->where(['behavior_id' => $params['id']])->update(['status' => 0]);
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
                \Db::table('UserPointsBehavior')->where(['behavior_id' => $params['id']])->update(['status' => $params['status']]);
                return $this->success($params['status'] == 1 ? '已启用' : '已禁用');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }
}