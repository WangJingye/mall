<?php

namespace admin\erp\service;

use admin\common\service\BaseService;

class FreightTemplateService extends BaseService
{
    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getList($params, $ispage = true)
    {
        $selector = \Db::table('FreightTemplate')->where(['status' => 1]);

        if (isset($params['freight_id']) && $params['freight_id'] != '') {
            $selector->where(['freight_id' => $params['freight_id']]);
        }
        if (isset($params['template_name']) && $params['template_name'] != '') {
            $selector->where(['template_name' => ['like', '%' . $params['template_name'] . '%']]);
        }
        if (isset($params['freight_type']) && $params['freight_type'] != '') {
            $selector->where(['freight_type' => $params['freight_type']]);
        }
        if (isset($params['number']) && $params['number'] != '') {
            $selector->where(['number' => $params['number']]);
        }
        if (isset($params['start_price']) && $params['start_price'] != '') {
            $selector->where(['start_price' => $params['start_price']]);
        }
        if (isset($params['step_number']) && $params['step_number'] != '') {
            $selector->where(['step_number' => $params['step_number']]);
        }
        if (isset($params['step_price']) && $params['step_price'] != '') {
            $selector->where(['step_price' => $params['step_price']]);
        }
        if (isset($params['create_time']) && $params['create_time'] != '') {
            $selector->where(['create_time' => $params['create_time']]);
        }
        if (isset($params['update_time']) && $params['update_time'] != '') {
            $selector->where(['update_time' => $params['update_time']]);
        }
        $selector->order('freight_id desc');
        if ($ispage) {
            return $this->pagination($selector, $params);
        }
        return $selector->findAll();
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function saveFreightTemplate($data)
    {
        if (isset($data['freight_id']) && $data['freight_id']) {
            \Db::table('FreightTemplate')->where(['freight_id' => $data['freight_id']])->update($data);
        } else {
            \Db::table('FreightTemplate')->insert($data);
        }
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function deleteFreightTemplate($data)
    {
        \Db::table('FreightTemplate')->where($data)->update(['status' => 1]);
    }

}