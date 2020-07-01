<?php

namespace admin\erp\controller;

use admin\common\controller\BaseController;
use admin\erp\service\UserLevelVerifyService;
use admin\extend\Constant;

class UserLevelVerifyController extends BaseController
{
    /** @var UserLevelVerifyService */
    public $userLevelVerifyService;
    public $levelList = [
        '1' => '普通会员',
        '2' => '初级会员',
        '3' => '高级会员',
    ];
    public $statusList = [
        '1' => '未审核',
        '2' => '审核通过',
        '3' => '审核拒绝',
    ];

    public function init()
    {
        $this->userLevelVerifyService = new UserLevelVerifyService();
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
        /** @var UserLevelVerifyService $res */
        $res = $this->userLevelVerifyService->getList($params);
        $list = $res->list;
        $this->assign('params', $params);
        $this->assign('pagination', $this->pagination($res));
        $this->assign('list', $list);
        $this->assign('levelList', $this->levelList);
        $this->assign('statusList', $this->statusList);
        $userIdList = array_column($list, 'user_id');
        if (!empty($params['user_id'])) {
            $userIdList[] = $params['user_id'];
        }
        $userList = $this->userLevelVerifyService->getDataList('User', 'user_id', 'nickname', ['user_id' => ['in', $userIdList]]);
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
                if (!isset($params['id']) || $params['id'] == '') {
                    throw new \Exception('非法请求');
                }
                \Db::table('UserLevelVerify')->where(['verify_id' => $params['id']])->update(['status' => 0]);
                return $this->success('删除成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function verifyAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                if (empty($params['id'])) {
                    throw new \Exception('非法请求');
                }
                $model = \Db::table('UserLevelVerify')->where(['verify_id' => $params['id']])->find();
                if ($model['status'] != Constant::USER_LEVEL_STATUS_UNVERIFIED) {
                    throw new \Exception('状态有误，请确认');
                }
                \Db::startTrans();
                \Db::table('UserLevelVerify')->where(['verify_id' => $params['id']])->update([
                        'status' => $params['status'],
                        'remark' => $params['remark'],
                        'operator_id' => \App::$user['admin_id'],
                        'verify_time' => time(),
                    ]
                );
                //审核通过会员等级提升
                if ($params['status'] == Constant::USER_LEVEL_STATUS_ACCEPT) {
                    \Db::table('User')
                        ->where(['user_id' => $model['user_id']])
                        ->update(['level' => $model['level']]);
                }
                \Db::commit();
                return $this->success('审核成功');
            } catch (\Exception $e) {
                \Db::rollback();
                return $this->error($e->getMessage());
            }
        }
    }
}