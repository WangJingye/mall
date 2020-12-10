<?php

namespace admin\erp\service;

use admin\common\service\BaseService;

class MessageActivityService extends BaseService
{
    /**
     * @param $params
     * @param bool $ispage
     * @return MessageActivityService|array|mixed
     * @throws \Exception
     */
    public function getList($params, $ispage = true)
    {
        $selector = \Db::table('MessageActivity');

        if (isset($params['id']) && $params['id'] != '') {
            $selector->where(['id' => $params['id']]);
        }
        if (isset($params['title']) && $params['title'] != '') {
            $selector->where(['title' => ['like', '%' . $params['title'] . '%']]);
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
    public function saveMessageActivity($data)
    {
        if (isset($data['id']) && $data['id']) {
            \Db::table('MessageActivity')->where(['id' => $data['id']])->update($data);
        } else {
            \Db::table('MessageActivity')->insert($data);
        }
    }
}