<?php

namespace admin\common\controller;

use component\WebController;

class BaseController extends WebController
{
    public $boolList = [
        '0' => '否',
        '1' => '是',
    ];
    public function init()
    {
        $this->layout('main');
        $user = \App::$session->get('user');
        \App::$user = $user ? $user : [];
        $this->validateUserGrant();
        parent::init();
    }

    /**
     * 验证用户权限
     * @throws \Exception
     */
    private function validateUserGrant()
    {
        try {
            $uri = \App::$request->uri;
            if ($this->checkNoLoginList(\App::$request->module . '/' . \App::$request->controller, \App::$request->action)) {
                return false;
            }
            if (empty(\App::$user)) {
                throw new \Exception('您暂未登陆', 999);
            }
            if ($this->checkWhiteList(\App::$request->module . '/' . \App::$request->controller, \App::$request->action)) {
                return false;
            }
            $menu = \Db::table('Menu')->where(['url' => $uri])->find();
            if (!$menu) {
                throw new \Exception($uri . '该地址不在权限中');
            }
            if (\App::$user['identity'] == 1) {
                return false;
            }
            $access = \Db::table('RoleMenu')->rename('a')
                ->join(['b' => 'RoleAdmin'], 'a.role_id = b.role_id')
                ->where(['a.menu_id' => $menu['id'], 'b.admin_id' => \App::$user['admin_id']])
                ->find();
            if (!$access) {
                throw new \Exception('您暂无该权限');
            }
        } catch (\Exception $e) {
            if (!\App::$request->isAjax() && $e->getCode() == 999) {
                $now = trim($_SERVER['QUERY_STRING'], 's=');
                $option = [];
                if ($now && $now != 'system/public/login') {
                    $option['redirect_url'] = $now;
                }
                return $this->redirect('system/public/login', $option);
            }
            throw $e;
        }
    }

    /**
     * @param $uri
     * @throws \Exception
     */
    /**
     * @param $moduleName
     * @param null $actionName
     * @return bool
     * @throws \Exception
     */
    public function checkNoLoginList($moduleName, $actionName = null)
    {
        $noLoginActions = \App::$config->actionNoLoginList;

        $moduleName = strtolower($moduleName);
        $actionName = strtolower($actionName);
        $_deal_action = [];
        foreach ($noLoginActions as $m => $a) {
            array_walk($a, function (&$x) {
                $x = strtolower($x);
            });
            $_deal_action[strtolower($m)] = $a;
        }
        if (isset($_deal_action[$moduleName]) && in_array($actionName, $_deal_action[$moduleName])) {
            return true;
        }
        return false;
    }
}