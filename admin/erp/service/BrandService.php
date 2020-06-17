<?php

namespace admin\erp\service;

use admin\common\service\BaseService;

class BrandService extends BaseService
{
    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getList($params, $ispage = true)
    {
        $selector = \Db::table('Brand')->where(['status' => 1]);

        if (isset($params['brand_id']) && $params['brand_id'] != '') {
            $selector->where(['brand_id' => $params['brand_id']]);
        }
        if (isset($params['brand_name']) && $params['brand_name'] != '') {
            $selector->where(['brand_name' => ['like', '%' . $params['brand_name'] . '%']]);
        }
        if (isset($params['logo']) && $params['logo'] != '') {
            $selector->where(['logo' => ['like', '%' . $params['logo'] . '%']]);
        }
        if (isset($params['sort']) && $params['sort'] != '') {
            $selector->where(['sort' => $params['sort']]);
        }
        if (isset($params['create_time']) && $params['create_time'] != '') {
            $selector->where(['create_time' => $params['create_time']]);
        }
        if (isset($params['update_time']) && $params['update_time'] != '') {
            $selector->where(['update_time' => $params['update_time']]);
        }
        $selector->order('sort desc,brand_id desc');
        if ($ispage) {
            return $this->pagination($selector, $params);
        }
        return $selector->findAll();
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function saveBrand($data)
    {
        if (isset($data['brand_id']) && $data['brand_id']) {
            \Db::table('Brand')->where(['brand_id' => $data['brand_id']])->update($data);
        } else {
            \Db::table('Brand')->insert($data);
        }
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function deleteBrand($data)
    {
        \Db::table('Brand')->where($data)->update(['status' => 0]);
    }

}