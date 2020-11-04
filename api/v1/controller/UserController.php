<?php

namespace api\v1\controller;

use api\v1\service\UserService;

class UserController extends BaseController
{
    /** @var UserService */
    public $userService;

    public function init()
    {
        $this->userService = new UserService();
        parent::init();
    }

    public function addressAction()
    {
        $params = \App::$request->params;
        $selector = \Db::table('UserAddress')
            ->where(['user_id' => \App::$user['user_id']]);
        if (!empty($params['address_id'])) {
            $selector->where(['address_id' => $params['address_id']]);
        } else {
            $selector->where(['is_default' => 1]);
        }
        $data = $selector->find();
        return $this->success('success', $data);
    }

    public function addressListAction()
    {
        $selector = \Db::table('UserAddress')
            ->where(['user_id' => \App::$user['user_id']]);
        $data = $selector->findAll();
        return $this->success('success', $data);
    }

    public function setDefaultAddressAction()
    {
        $params = \App::$request->params;
        if (empty($params['id'])) {
            throw new \Exception('参数有误');
        }
        $obj = \Db::table('UserAddress')
            ->where(['user_id' => \App::$user['user_id']])
            ->where(['address_id' => $params['id']])
            ->find();
        if (!$obj) {
            throw new \Exception('参数有误');
        }
        \Db::table('UserAddress')
            ->where(['user_id' => \App::$user['user_id']])
            ->update(['is_default' => 0]);
        \Db::table('UserAddress')
            ->where(['address_id' => $params['id']])
            ->update(['is_default' => 1]);
        return $this->success('success');
    }

    public function deleteAddressAction()
    {
        $params = \App::$request->params;
        if (empty($params['id'])) {
            throw new \Exception('参数有误');
        }
        $obj = \Db::table('UserAddress')
            ->where(['user_id' => \App::$user['user_id']])
            ->where(['address_id' => $params['id']])
            ->find();
        if (!$obj) {
            throw new \Exception('参数有误');
        }
        \Db::table('UserAddress')
            ->where(['user_id' => \App::$user['user_id']])
            ->where(['address_id' => $params['id']])
            ->delete();
        $res = [];
        if ($obj['is_default']) {
            $other = \Db::table('UserAddress')
                ->where(['user_id' => \App::$user['user_id']])
                ->find();
            if ($other) {
                \Db::table('UserAddress')
                    ->where(['user_id' => \App::$user['user_id']])
                    ->where(['address_id' => $other['address_id']])
                    ->update(['is_default' => 1]);
                $res['default_id'] = $other['address_id'];
            }
        }
        return $this->success('success', $res);
    }
}