<?php

namespace admin\erp\service;

use admin\common\service\BaseService;

class UserLevelVerifyService extends BaseService
{
    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getList($params, $ispage = true)
    {
        $selector = \Db::table('UserLevelVerify');

        if (isset($params['verify_id']) && $params['verify_id'] != '') {
            $selector->where(['verify_id' => $params['verify_id']]);
        }
        if (isset($params['user_id']) && $params['user_id'] != '') {
            $selector->where(['user_id' => $params['user_id']]);
        }
        if (isset($params['level']) && $params['level'] != '') {
            $selector->where(['level' => $params['level']]);
        }
        if (isset($params['status']) && $params['status'] != '') {
            $selector->where(['status' => $params['status']]);
        }
        if (isset($params['remark']) && $params['remark'] != '') {
            $selector->where(['remark' => ['like', '%' . $params['remark'] . '%']]);
        }
        if (isset($params['create_time']) && $params['create_time'] != '') {
            $selector->where(['create_time' => $params['create_time']]);
        }
        if (isset($params['verify_time']) && $params['verify_time'] != '') {
            $selector->where(['verify_time' => $params['verify_time']]);
        }
        if (isset($params['update_time']) && $params['update_time'] != '') {
            $selector->where(['update_time' => $params['update_time']]);
        }
        $selector->order('verify_id desc');
        if ($ispage) {
            return $this->pagination($selector, $params);
        }
        return $selector->findAll();
    }

}