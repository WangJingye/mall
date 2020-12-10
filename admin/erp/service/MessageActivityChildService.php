<?php

namespace admin\erp\service;

use admin\common\service\BaseService;

class MessageActivityChildService extends BaseService
{
    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getList($params, $ispage = true)
    {
        $selector = \Db::table('MessageActivityChild');

        if (isset($params['id']) && $params['id'] != '') {
            $selector->where(['id' => $params['id']]);
        }
        if (isset($params['activity_id']) && $params['activity_id'] != '') {
            $selector->where(['activity_id' => $params['activity_id']]);
        }
        if (isset($params['title']) && $params['title'] != '') {
            $selector->where(['title' => ['like', '%' . $params['title'] . '%']]);
        }
        if (isset($params['pic']) && $params['pic'] != '') {
            $selector->where(['pic' => ['like', '%' . $params['pic'] . '%']]);
        }
        if (isset($params['link_type']) && $params['link_type'] != '') {
            $selector->where(['link_type' => $params['link_type']]);
        }
        if (isset($params['link']) && $params['link'] != '') {
            $selector->where(['link' => ['like', '%' . $params['link'] . '%']]);
        }
        if (isset($params['parent_id']) && $params['parent_id'] != '') {
            $selector->where(['parent_id' => $params['parent_id']]);
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
        $selector->order('sort desc,id desc');
        if ($ispage) {
            return $this->pagination($selector, $params);
        }
        return $selector->findAll();
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function saveMessageActivityChild($data)
    {
        if (isset($data['id']) && $data['id']) {
            \Db::table('MessageActivityChild')->where(['id' => $data['id']])->update($data);
        } else {
            \Db::table('MessageActivityChild')->insert($data);
        }
    }

}