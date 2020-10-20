<?php

namespace admin\erp\service;

use admin\common\service\BaseService;

class FlashSaleService extends BaseService
{
    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getList($params, $ispage = true)
    {
        $selector = \Db::table('FlashSale');

        if (isset($params['flash_id']) && $params['flash_id'] != '') {
            $selector->where(['flash_id' => $params['flash_id']]);
        }
        if (isset($params['title']) && $params['title'] != '') {
            $selector->where(['title' => ['like', '%' . $params['title'] . '%']]);
        }
        if (isset($params['product_id']) && $params['product_id'] != '') {
            $selector->where(['product_id' => $params['product_id']]);
        }
        if (isset($params['variation_code']) && $params['variation_code'] != '') {
            $selector->where(['variation_code' => ['like', '%' . $params['variation_code'] . '%']]);
        }
        if (isset($params['price']) && $params['price'] != '') {
            $selector->where(['price' => $params['price']]);
        }
        if (isset($params['product_price']) && $params['product_price'] != '') {
            $selector->where(['product_price' => $params['product_price']]);
        }
        if (isset($params['market_price']) && $params['market_price'] != '') {
            $selector->where(['market_price' => $params['market_price']]);
        }
        if (isset($params['stock']) && $params['stock'] != '') {
            $selector->where(['stock' => $params['stock']]);
        }
        if (isset($params['start_time']) && $params['start_time'] != '') {
            $selector->where(['start_time' => $params['start_time']]);
        }
        if (isset($params['end_time']) && $params['end_time'] != '') {
            $selector->where(['end_time' => $params['end_time']]);
        }
        if (isset($params['status']) && $params['status'] != '') {
            $selector->where(['status' => $params['status']]);
        } else {
            $selector->where(['status' => ['>', 0]]);
        }
        if (isset($params['create_userid']) && $params['create_userid'] != '') {
            $selector->where(['create_userid' => $params['create_userid']]);
        }
        if (isset($params['create_time']) && $params['create_time'] != '') {
            $selector->where(['create_time' => $params['create_time']]);
        }
        if (isset($params['update_time']) && $params['update_time'] != '') {
            $selector->where(['update_time' => $params['update_time']]);
        }
        $selector->order('flash_id desc');
        if ($ispage) {
            return $this->pagination($selector, $params);
        }
        return $selector->findAll();
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function saveFlashSale($data)
    {
        $data['start_time'] = strtotime($data['start_time']);
        if (!$data['start_time']) {
            throw new \Exception('开始时间格式有误');
        }
        $data['end_time'] = strtotime($data['end_time']);
        if (!$data['end_time']) {
            throw new \Exception('结束时间格式有误');
        }
        if ($data['start_time'] >= $data['end_time']) {
            throw new \Exception('开始时间必须小于结束时间');
        }
        $data['status'] = 1;
        if ($data['start_time'] < time()) {
            $data['status'] = 2;
        }
        if ($data['end_time'] < time()) {
            $data['status'] = 3;
        }
        if (isset($data['flash_id']) && $data['flash_id']) {
            \Db::table('FlashSale')->where(['flash_id' => $data['flash_id']])->update($data);
        } else {
            \Db::table('FlashSale')->insert($data);
        }
    }

}