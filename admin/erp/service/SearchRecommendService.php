<?php

namespace admin\erp\service;

use admin\common\service\BaseService;

class SearchRecommendService extends BaseService
{
    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getList($params, $ispage = true)
    {
        $selector = \Db::table('SearchRecommend');

        if (isset($params['id']) && $params['id'] != '') {
            $selector->where(['id' => $params['id']]);
        }
        if (isset($params['title']) && $params['title'] != '') {
            $selector->where(['title' => ['like', '%' . $params['title'] . '%']]);
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
    public function saveSearchRecommend($data)
    {
        if (isset($data['id']) && $data['id']) {
            \Db::table('SearchRecommend')->where(['id' => $data['id']])->update($data);
        } else {
            \Db::table('SearchRecommend')->insert($data);
        }
    }

}