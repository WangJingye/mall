<?php

namespace admin\erp\service;

use admin\common\service\BaseService;

class MessageCategoryService extends BaseService
{
    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getList($params, $ispage = true)
    {
        $selector = \Db::table('MessageCategory');

        if (isset($params['category_id']) && $params['category_id'] != '') {
            $selector->where(['category_id' => $params['category_id']]);
        }
        if (isset($params['category_name']) && $params['category_name'] != '') {
            $selector->where(['category_name' => ['like', '%' . $params['category_name'] . '%']]);
        }
        if (isset($params['pic']) && $params['pic'] != '') {
            $selector->where(['pic' => ['like', '%' . $params['pic'] . '%']]);
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
        $selector->order('category_id desc');
        if ($ispage) {
            return $this->pagination($selector, $params);
        }
        return $selector->findAll();
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function saveMessageCategory($data)
    {
        if (isset($data['category_id']) && $data['category_id']) {
            \Db::table('MessageCategory')->where(['category_id' => $data['category_id']])->update($data);
        } else {
            \Db::table('MessageCategory')->insert($data);
        }
    }

}