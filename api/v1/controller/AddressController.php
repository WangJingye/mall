<?php

namespace api\v1\controller;

class AddressController extends BaseController
{
    public function init()
    {
        parent::init();
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

    public function deleteAction()
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

    public function saveAction()
    {
        $params = \App::$request->params->toArray();
        $selector = \Db::table('UserAddress')
            ->where(['user_id' => \App::$user['user_id']])
            ->where(['is_default' => 1]);
        if (isset($params['address_id'])) {
            $selector->where(['address_id' => ['!=', $params['address_id']]]);
        }
        $default = $selector->find();
        if (!$default) {
            $params['is_default'] = 1;
        }
        if (!empty($params['is_default']) && $default) {
            \Db::table('UserAddress')
                ->where(['address_id' => $default['address_id']])
                ->update(['is_default' => 0]);
        }
        $params['user_id'] = \App::$user['user_id'];
        if (isset($params['address_id'])) {
            \Db::table('UserAddress')
                ->where(['address_id' => $params['address_id']])
                ->where(['user_id' => \App::$user['user_id']])
                ->update($params);
        } else {
            \Db::table('UserAddress')->insert($params);
        }
        return $this->success('success');
    }

}