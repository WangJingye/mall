<?php

namespace api\common\controller;

use component\RestController;

class BaseController extends RestController
{
    /**
     * @throws \Exception
     */
    public function init()
    {
        $this->getUserByToken();
        parent::init();
    }

    /**根据token获取用户信息
     * @throws \Exception
     */
    protected function getUserByToken()
    {
        $moduleController = \App::$request->module . '/' . \App::$request->controller;
        $url = $moduleController . '/' . \App::$request->action;
        $actionWhites = \App::$config->action_white_list;
        $actionWhites = $actionWhites ? $actionWhites : [];
        $actionWhiteList = [];
        foreach ((array)$actionWhites as $controller => $actionList) {
            foreach ($actionList as $action) {
                if ($action == '*') {
                    $actionWhiteList[] = $controller;
                } else {
                    $actionWhiteList[] = $controller . '/' . $action;
                }
            }
        }

        $header = \App::$request->header;
        $user = null;
        if (isset($header['identity']) && $header['identity'] != '') {
            $user = \Db::table('User')->where(['openid' => $header['identity']])->find();
        }
        if (!in_array($url, $actionWhiteList) && !in_array($moduleController, $actionWhiteList) && !$user) {
            throw new \Exception('未登录', 999);
        }
        if ($user) {
            \App::$user = $user;
        }
    }

    /**
     * @param $result
     * @throws \Exception
     */
    public function after($result)
    {
        parent::after($result);
        if (!is_array($result) || $result['code'] != 200) {
            return;
        }
        if (empty(\App::$user)) {
            return;
        }
        //积分
        $this->addPoints();
    }

    /**
     * 添加用户积分
     * @throws \Exception
     */
    private function addPoints()
    {
        $uri = $moduleController = \App::$request->module . '/' . \App::$request->controller . '/' . \App::$request->action;;
        $behavior = \Db::table('UserPointsBehavior')
            ->where(['url' => $uri])
            ->where(['status' => 1])
            ->find();
        if (!$behavior) {
            return;
        }
        $selector = \Db::table('UserPointsLog')
            ->where(['user_id' => \App::$user['user_id']])
            ->where(['behavior_id' => $behavior['behavior_id']]);
        //每日
        if ($behavior['type'] == 1) {
            $selector->where(['create_time' => ['>=', strtotime(date('Y-m-d'))]]);
        }
        $total = $selector->count();
        if ($total < $behavior['number']) {
            $data = [
                'user_id' => \App::$user['user_id'],
                'points' => $behavior['points'],
                'behavior_id' => $behavior['behavior_id'],
            ];
            \Db::table('UserPointsLog')->insert($data);
            \Db::table('UserWallet')->where(['user_id' => $data['user_id']])->increase('points', $behavior['points']);
        }
    }
}