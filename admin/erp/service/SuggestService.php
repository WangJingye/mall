<?php

namespace admin\erp\service;

use admin\common\service\BaseService;

class SuggestService extends BaseService
{
    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getList($params, $ispage = true)
    {
        $selector = \Db::table('Suggest');

        if (isset($params['id']) && $params['id'] != '') {
            $selector->where(['id' => $params['id']]);
        }
        if (isset($params['user_id']) && $params['user_id'] != '') {
            $selector->where(['user_id' => $params['user_id']]);
        }
        if (isset($params['content']) && $params['content'] != '') {
            $selector->where(['content' => ['like', '%' . $params['content'] . '%']]);
        }
        if (isset($params['status']) && $params['status'] != '') {
            $selector->where(['status' => $params['status']]);
        } else {
            $selector->where(['status' => ['!=', 0]]);
        }
        if (isset($params['reply']) && $params['reply'] != '') {
            $selector->where(['reply' => ['like', '%' . $params['reply'] . '%']]);
        }
        if (isset($params['create_time']) && $params['create_time'] != '') {
            $selector->where(['create_time' => $params['create_time']]);
        }
        if (isset($params['update_time']) && $params['update_time'] != '') {
            $selector->where(['update_time' => $params['update_time']]);
        }
        if (isset($params['nickname']) && $params['nickname'] != '') {
            $userList = \Db::table('User')->field(['user_id'])
                ->where(['nickname' => ['like', '%' . $params['nickname'] . '%']])
                ->findAll();
            $userIdList = array_column($userList, 'user_id');
            $selector->where(['user_id' => ['in', $userIdList]]);
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
    public function saveSuggest($data)
    {
        if (isset($data['id']) && $data['id']) {
            \Db::table('Suggest')->where(['id' => $data['id']])->update($data);
        } else {
            \Db::table('Suggest')->insert($data);
        }
    }

}