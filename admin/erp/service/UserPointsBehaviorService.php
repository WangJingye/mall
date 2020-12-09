<?php

namespace admin\erp\service;

use admin\common\service\BaseService;

class UserPointsBehaviorService extends BaseService
{
    public $statusList = [
        '1' => '启用中',
        '2' => '禁用中',
    ];

    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getList($params, $ispage = true)
    {
        $selector = \Db::table('UserPointsBehavior')->where(['status' => ['!=', 0]]);

        if (isset($params['behavior_id']) && $params['behavior_id'] != '') {
            $selector->where(['behavior_id' => $params['behavior_id']]);
        }
        if (isset($params['behavior_name']) && $params['behavior_name'] != '') {
            $selector->where(['behavior_name' => ['like', '%' . $params['behavior_name'] . '%']]);
        }
        if (isset($params['type']) && $params['type'] != '') {
            $selector->where(['type' => $params['type']]);
        }
        if (isset($params['url']) && $params['url'] != '') {
            $selector->where(['url' => ['like', '%' . $params['url'] . '%']]);
        }
        if (isset($params['number']) && $params['number'] != '') {
            $selector->where(['number' => $params['number']]);
        }
        if (isset($params['create_time']) && $params['create_time'] != '') {
            $selector->where(['create_time' => $params['create_time']]);
        }
        if (isset($params['update_time']) && $params['update_time'] != '') {
            $selector->where(['update_time' => $params['update_time']]);
        }
        $selector->order('behavior_id desc');
        if ($ispage) {
            return $this->pagination($selector, $params);
        }
        return $selector->findAll();
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function saveUserPointsBehavior($data)
    {
        if (isset($data['behavior_id']) && $data['behavior_id']) {
            \Db::table('UserPointsBehavior')->where(['behavior_id' => $data['behavior_id']])->update($data);
        } else {
            \Db::table('UserPointsBehavior')->insert($data);
        }
    }

}