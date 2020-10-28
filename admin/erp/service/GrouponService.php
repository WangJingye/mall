<?php

namespace admin\erp\service;

use admin\common\service\BaseService;

class GrouponService extends BaseService
{
    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getList($params, $ispage = true)
    {
        $selector = \Db::table('Groupon');

        if (isset($params['id']) && $params['id'] != '') {
            $selector->where(['id' => $params['id']]);
        }
        if (isset($params['title']) && $params['title'] != '') {
            $selector->where(['title' => ['like', '%' . $params['title'] . '%']]);
        }
        if (isset($params['product_id']) && $params['product_id'] != '') {
            $selector->where(['product_id' => $params['product_id']]);
        }
        if (isset($params['create_time_end']) && $params['create_time_end'] != '') {
            $selector->where(['create_time' => ['<', strtotime($params['create_time_end']) + 24 * 3600]]);
        }
        if (isset($params['create_time_start']) && $params['create_time_start'] != '') {
            $selector->where(['create_time' => ['>=', strtotime($params['create_time_start'])]]);
        }
        if (isset($params['status']) && $params['status'] != '') {
            $selector->where(['status' => $params['status']]);
        } else {
            $selector->where(['status' => ['>', 0]]);
        }
        if (isset($params['create_userid']) && $params['create_userid'] != '') {
            $selector->where(['create_userid' => $params['create_userid']]);
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
    public function saveGroupon($data)
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
        $variations = json_decode($data['variation'], true);
        if (empty($variations)) {
            throw new \Exception('SKU不能为空');
        }
        //商品价格取第一个variation的价格
        $data['price'] = $variations[0]['price'];
        unset($data['variation']);
        if (isset($data['id']) && $data['id']) {
            \Db::table('Groupon')->where(['id' => $data['id']])->update($data);
        } else {
            $data['id'] = \Db::table('Groupon')->insert($data);
        }
        $vIds = array_column($variations, 'variation_id');
        $pvList = \Db::table('ProductVariation')->where(['variation_id' => ['in', $vIds]])->findAll();
        $pvList = array_column($pvList, null, 'variation_id');
        $existList = \Db::table('GrouponVariation')->where(['go_id' => $data['id']])->where(['variation_id' => ['in', $vIds]])->findAll();
        $existList = array_column($existList, null, 'variation_id');
        foreach ($variations as $v) {
            $pv = $pvList[$v['variation_id']];
            $variation = [
                'go_id' => $data['id'],
                'variation_id' => $pv['variation_id'],
                'variation_code' => $pv['variation_code'],
                'rules_name' => $pv['rules_name'],
                'rules_value' => $pv['rules_value'],
                'price' => $v['price'],
                'product_price' => $v['product_price'],
                'stock' => $v['stock'],
                'status' => 1
            ];
            if (isset($existList[$v['variation_id']])) {
                \Db::table('GrouponVariation')->where(['id' => $existList[$v['variation_id']]['id']])->update($variation);
            } else {
                \Db::table('GrouponVariation')->insert($variation);
            }
        }
        foreach ($existList as $vId => $v) {
            if (!in_array($vId, $vIds)) {
                \Db::table('GrouponVariation')->delete(['id' => $vId]);
            }
        }
    }

}