<?php

namespace admin\erp\service;

use admin\common\service\BaseService;

class CarouselService extends BaseService
{
    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getList($params, $ispage = true)
    {
        $selector = \Db::table('Carousel');

        if (isset($params['carousel_id']) && $params['carousel_id'] != '') {
            $selector->where(['carousel_id' => $params['carousel_id']]);
        }
        if (isset($params['carousel_type']) && $params['carousel_type'] != '') {
            $selector->where(['carousel_type' => $params['carousel_type']]);
        }
        if (isset($params['title']) && $params['title'] != '') {
            $selector->where(['title' => ['like', '%' . $params['title'] . '%']]);
        }
        if (isset($params['pic']) && $params['pic'] != '') {
            $selector->where(['pic' => ['like', '%' . $params['pic'] . '%']]);
        }
        if (isset($params['sort']) && $params['sort'] != '') {
            $selector->where(['sort' => $params['sort']]);
        }
        if (isset($params['link_type']) && $params['link_type'] != '') {
            $selector->where(['link_type' => $params['link_type']]);
        }
        if (isset($params['link_id']) && $params['link_id'] != '') {
            $selector->where(['link_id' => $params['link_id']]);
        }
        if (isset($params['is_show']) && $params['is_show'] != '') {
            $selector->where(['is_show' => $params['is_show']]);
        }
        if (isset($params['create_time']) && $params['create_time'] != '') {
            $selector->where(['create_time' => $params['create_time']]);
        }
        if (isset($params['update_time']) && $params['update_time'] != '') {
            $selector->where(['update_time' => $params['update_time']]);
        }
        $selector->order('sort desc,carousel_id desc');
        if ($ispage) {
            return $this->pagination($selector, $params);
        }
        return $selector->findAll();
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function saveCarousel($data)
    {
        if (isset($data['carousel_id']) && $data['carousel_id']) {
            \Db::table('Carousel')->where(['carousel_id' => $data['carousel_id']])->update($data);
        } else {
            \Db::table('Carousel')->insert($data);
        }
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function deleteCarousel($data)
    {
        \Db::table('Carousel')->where($data)->delete();
    }

}