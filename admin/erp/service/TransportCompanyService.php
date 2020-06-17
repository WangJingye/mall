<?php

namespace admin\erp\service;

use admin\common\service\BaseService;

class TransportCompanyService extends BaseService
{
    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getList($params, $ispage = true)
    {
        $selector = \Db::table('TransportCompany')->where(['status' => 1]);

        if (isset($params['company_id']) && $params['company_id'] != '') {
            $selector->where(['company_id' => $params['company_id']]);
        }
        if (isset($params['company_name']) && $params['company_name'] != '') {
            $selector->where(['company_name' => ['like', '%' . $params['company_name'] . '%']]);
        }
        if (isset($params['remark']) && $params['remark'] != '') {
            $selector->where(['remark' => ['like', '%' . $params['remark'] . '%']]);
        }
        if (isset($params['create_time']) && $params['create_time'] != '') {
            $selector->where(['create_time' => $params['create_time']]);
        }
        if (isset($params['update_time']) && $params['update_time'] != '') {
            $selector->where(['update_time' => $params['update_time']]);
        }
        $selector->order('company_id desc');
        if ($ispage) {
            return $this->pagination($selector, $params);
        }
        return $selector->findAll();
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function saveTransportCompany($data)
    {
        if (isset($data['company_id']) && $data['company_id']) {
            \Db::table('TransportCompany')->where(['company_id' => $data['company_id']])->update($data);
        } else {
            \Db::table('TransportCompany')->insert($data);
        }
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function deleteTransportCompany($data)
    {
        \Db::table('TransportCompany')->where($data)->update(['status' => 0]);
    }

}