<?php

namespace admin\erp\controller;

use admin\common\controller\BaseController;
use admin\erp\service\CashOutService;
use common\helper\Constant;

class CashOutController extends BaseController
{
    /** @var CashOutService */
    public $cashOutService;

    public function init()
    {
        $this->cashOutService = new CashOutService();
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
        if (!empty($params['export_type'])) {
            $this->cashOutService->export($params);
        }
        /** @var CashOutService $res */
        $res = $this->cashOutService->getList($params);
        $list = $res->list;
        $this->assign('params', $params);
        $this->assign('pagination', $this->pagination($res));
        $this->assign('list', $list);
        $this->assign('statusList', $this->cashOutService->statusList);
        $userIdList = array_column($list, 'user_id');
        if (!empty($params['user_id'])) {
            $userIdList[] = $params['user_id'];
        }
        $userList = $this->cashOutService->getDataList('User', 'user_id', 'nickname', ['user_id' => ['in', $userIdList]]);
        $this->assign('userList', $userList);
        $adminIdList = array_column($list, 'operator_id');
        $operatorList = $this->cashOutService->getDataList('Admin', 'admin_id', 'realname', ['admin_id' => ['in', $adminIdList]]);
        $this->assign('operatorList', $operatorList);
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
                $this->cashOutService->deleteCashOut($params);
                return $this->success('删除成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }

    /**
     * 提现审核
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
                $model = \Db::table('CashOut')->where(['id' => $params['id']])->find();
                if ($model['status'] != Constant::CASH_OUT_STATUS_UNVERIFIED) {
                    throw new \Exception('状态有误，请确认');
                }
                \Db::startTrans();
                \Db::table('CashOut')->where(['id' => $model['id']])->update([
                        'status' => $params['status'],
                        'remark' => $params['id'],
                        'operator_id' => \App::$user['admin_id'],
                        'verify_time' => time(),
                    ]
                );
                //审核通过冻结金额减少
                if ($params['status'] == Constant::CASH_OUT_STATUS_ACCEPT) {
                    \Db::table('UserWallet')
                        ->where(['user_id' => $model['user_id']])
                        ->change([
                            'frozen_money' => ['-', $model['amount']],
                            'balance' => ['-', $model['amount']],
                            'cash_out_money' => ['+', $model['amount']]
                        ]);
                } else if ($params['status'] == Constant::CASH_OUT_STATUS_REJECT) {
                    \Db::table('UserWallet')
                        ->where(['user_id' => $model['user_id']])
                        ->decrease('frozen_money', $model['amount']);
                }
                \Db::commit();
                return $this->success('审核成功');
            } catch (\Exception $e) {
                \Db::rollback();
                return $this->error($e->getMessage());
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function detailAction()
    {
        $params = \App::$request->params->toArray();
        if (!isset($params['id']) || $params['id'] == '') {
            throw new \Exception('非法请求');
        }
        $model = \Db::table('CashOut')->where(['id' => $params['id']])->find();
        $this->assign('model', $model);
        $user = \Db::table('User')->where(['user_id' => $model['user_id']])->find();
        $this->assign('user', $user);
        $wallet = \Db::table('UserWallet')->where(['user_id' => $model['user_id']])->find();
        $this->assign('wallet', $wallet);
        $this->assign('statusList', $this->cashOutService->statusList);
    }
}