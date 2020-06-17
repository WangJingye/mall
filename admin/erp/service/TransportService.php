<?php

namespace admin\erp\service;

use admin\common\service\BaseService;

class TransportService extends BaseService
{
    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getList($params, $ispage = true)
    {
        $selector = \Db::table('Transport');

        if (isset($params['transport_id']) && $params['transport_id'] != '') {
            $selector->where(['transport_id' => $params['transport_id']]);
        }
        if (isset($params['transport_name']) && $params['transport_name'] != '') {
            $selector->where(['transport_name' => ['like', '%' . $params['transport_name'] . '%']]);
        }
        if (isset($params['remark']) && $params['remark'] != '') {
            $selector->where(['remark' => ['like', '%' . $params['remark'] . '%']]);
        }
        if (isset($params['status']) && $params['status'] != '') {
            $selector->where(['status' => $params['status']]);
        } else {
            $selector->where(['status' => ['!=', 0]]);
        }
        if (isset($params['create_time']) && $params['create_time'] != '') {
            $selector->where(['create_time' => $params['create_time']]);
        }
        if (isset($params['update_time']) && $params['update_time'] != '') {
            $selector->where(['update_time' => $params['update_time']]);
        }
        $selector->order('transport_id desc');
        if ($ispage) {
            return $this->pagination($selector, $params);
        }
        return $selector->findAll();
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function saveTransport($data)
    {
        if (isset($data['transport_id']) && $data['transport_id']) {
            \Db::table('Transport')->where(['transport_id' => $data['transport_id']])->update($data);
        } else {
            \Db::table('Transport')->insert($data);
        }
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function deleteTransport($data)
    {
        \Db::table('Transport')->where($data)->update(['status' => 0]);
    }

}