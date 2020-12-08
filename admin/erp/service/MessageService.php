<?php

namespace admin\erp\service;

use admin\common\service\BaseService;

class MessageService extends BaseService
{
    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getList($params, $ispage = true)
    {
        $selector = \Db::table('Message');

        if (isset($params['id']) && $params['id'] != '') {
            $selector->where(['id' => $params['id']]);
        }
        if (isset($params['category_id']) && $params['category_id'] != '') {
            $selector->where(['category_id' => $params['category_id']]);
        }
        if (isset($params['user_id']) && $params['user_id'] != '') {
            $selector->where(['user_id' => $params['user_id']]);
        }
        if (isset($params['content']) && $params['content'] != '') {
            $selector->where(['content' => ['like', '%' . $params['content'] . '%']]);
        }
        if (isset($params['create_time']) && $params['create_time'] != '') {
            $selector->where(['create_time' => $params['create_time']]);
        }
        if (isset($params['update_time']) && $params['update_time'] != '') {
            $selector->where(['update_time' => $params['update_time']]);
        }
        $selector->order('id desc');
        if ($ispage) {
            return $this->pagination($selector, $params);
        }
        return $selector->findAll();
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function saveMessage($data)
    {
        if (isset($data['id']) && $data['id']) {
            \Db::table('Message')->where(['id' => $data['id']])->update($data);
        } else {
            \Db::table('Message')->insert($data);
        }
    }

}