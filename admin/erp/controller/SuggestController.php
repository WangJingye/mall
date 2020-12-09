<?php

namespace admin\erp\controller;

use admin\common\controller\BaseController;
use admin\erp\service\SuggestService;

class SuggestController extends BaseController
{
    /** @var SuggestService */
    public $suggestService;

    public $statusList = [
        '1' => '未处理',
        '2' => '已处理',
    ];

    public function init()
    {
        $this->suggestService = new SuggestService();
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
        /** @var SuggestService $res */
        $res = $this->suggestService->getList($params);
        $list = $res->list;
        $userIds = array_column($list, 'user_id');
        $userList = $this->suggestService->getDataList('User', 'user_id', 'nickname', ['user_id' => ['in', $userIds]]);
        $this->assign('params', $params);
        $this->assign('pagination', $this->pagination($res));
        $this->assign('list', $list);
        $this->assign('statusList', $this->statusList);
        $this->assign('userList', $userList);
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
                \Db::table('Suggest')->where(['id' => $params['id']])->update(['status' => 0]);
                return $this->success('删除成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }

    /**
     * 回复
     */
    public function replyAction()
    {
        try {
            $params = \App::$request->params->toArray();
            if (empty($params['ids']) || !isset($params['reply']) || $params['reply'] == '') {
                throw new \Exception('非法请求');
            }
            \Db::table('Suggest')
                ->where(['id' => ['in', explode(',', $params['ids'])]])
                ->where(['status' => 1])
                ->update([
                    'reply' => $params['reply'],
                    'operator' => \App::$user['admin_id'],
                    'status' => 2
                ]);
            return $this->success('已回复');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}