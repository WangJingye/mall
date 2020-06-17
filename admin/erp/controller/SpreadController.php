<?php

namespace admin\erp\controller;

use admin\common\controller\BaseController;
use admin\erp\service\SpreadService;
use admin\erp\service\UserService;

class SpreadController extends BaseController
{
    /** @var UserService */
    public $userService;
    /** @var SpreadService */
    public $spreadService;
    public $typeList = [
        1 => '指定分销',
        2 => '人人分销'
    ];

    public function init()
    {
        $this->userService = new UserService();
        $this->spreadService = new SpreadService();
        parent::init();
    }

    public function configAction()
    {
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                $post = \App::$request->params->toArray();
                \Db::table('SiteInfo')->update(['spread' => json_encode($post)]);
                return $this->success('配置成功');
            } catch (\Exception $e) {
                return $this->error($e);
            }
        }
        $model = \App::$config['site_info']['spread'] ? json_decode(\App::$config['site_info']['spread'], true) : [];
        $this->assign('model', $model);
        $this->assign('typeList', $this->typeList);
    }

    /**
     * 分销员列表
     * @throws \Exception
     */
    public function promoterAction()
    {
        $params = \App::$request->params;
        $params['page'] = \App::$request->getParams('page', 1);
        $params['pageSize'] = \App::$request->getParams('pageSize', 10);
        if (!empty($params['search_type'])) {
            $params[$params['search_type']] = $params['search_value'];
        }
        if (!empty($params['export_type'])) {
            $this->userService->export($params);
        }
        $params['is_promoter'] = 1;
        /** @var UserService $res */
        $res = $this->userService->getList($params);
        $list = $res->list;
        $uidList = array_column($list, 'user_id');
        $walletList = \Db::table('UserWallet')->where(['user_id' => ['in', $uidList]])->findAll();
        $walletList = array_column($walletList, null, 'user_id');
        $this->assign('params', $params);
        $this->assign('pagination', $this->pagination($res));
        $this->assign('list', $list);
        $this->assign('walletList', $walletList);
        $childUserCount = \Db::table('User')->field(['count(*) as count', 'spread_id'])->where(['spread_id' => ['in', $uidList]])->group('spread_id')->findAll();
        $childUserCount = array_column($childUserCount, 'count', 'spread_id');
        $this->assign('childUserCount', $childUserCount);
        $spreadIdList = array_column($list, 'spread_id');
        if (!empty($params['spread_id'])) {
            $spreadIdList[] = $params['spread_id'];
        }
        $spreadList = $this->userService->getDataList('User', 'user_id', 'nickname', ['user_id' => ['in', $spreadIdList]]);
        $this->assign('spreadList', $spreadList);
    }

    /**
     * @throws \Exception
     */
    public function orderAction()
    {
        $params = \App::$request->params;
        $params['page'] = \App::$request->getParams('page', 1);
        $params['pageSize'] = \App::$request->getParams('pageSize', 10);
        if (!empty($params['search_type'])) {
            $params[$params['search_type']] = $params['search_value'];
        }
        /** @var SpreadService $res */
        $res = $this->spreadService->getList($params);
        $list = $res->list;
        $userList = \Db::table('User')
            ->field(['user_id', 'nickname', 'telephone'])
            ->where(['user_id' => ['in', array_column($list, 'user_id')]])
            ->findAll();
        $userList = array_column($userList, null, 'user_id');
        $this->assign('params', $params);
        $this->assign('pagination', $this->pagination($res));
        $this->assign('list', $res->list);
        $this->assign('userList', $userList);
    }

}