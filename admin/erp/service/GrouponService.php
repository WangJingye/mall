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
        $variations = json_decode($data['variation'], true);
        if (empty($variations)) {
            throw new \Exception('SKU不能为空');
        }
        //商品价格取第一个variation的价格
        $data['price'] = $variations[0]['price'];
        $data['product_price'] = $variations[0]['product_price'];
        $product = \Db::table('Product')->where(['product_id' => $data['product_id']])->find();
        $data['pic'] = $product['pic'];
        unset($data['variation']);
        if (isset($data['id']) && $data['id']) {
            \Db::table('Groupon')->where(['id' => $data['id']])->update($data);
        } else {
            $data['id'] = \Db::table('Groupon')->insert($data);
        }
        $codes = array_column($variations, 'variation_code');
        $pvList = \Db::table('ProductVariation')->where(['variation_code' => ['in', $codes]])->findAll();
        $pvList = array_column($pvList, null, 'variation_code');
        $existList = \Db::table('GrouponVariation')->where(['go_id' => $data['id']])->where(['variation_code' => ['in', $codes]])->findAll();
        $existList = array_column($existList, null, 'variation_code');
        foreach ($variations as $v) {
            $pv = $pvList[$v['variation_code']];
            $variation = [
                'go_id' => $data['id'],
                'variation_code' => $pv['variation_code'],
                'rules_name' => $pv['rules_name'],
                'rules_value' => $pv['rules_value'],
                'pic' => $pv['pic'],
                'price' => $v['price'],
                'product_price' => $v['product_price'],
                'stock' => $v['stock'],
                'status' => 1
            ];
            if (isset($existList[$v['variation_code']])) {
                \Db::table('GrouponVariation')->where(['id' => $existList[$v['variation_code']]['id']])->update($variation);
            } else {
                \Db::table('GrouponVariation')->insert($variation);
            }
        }
        foreach ($existList as $code => $v) {
            if (!in_array($code, $codes)) {
                \Db::table('GrouponVariation')->delete(['id' => $code]);
            }
        }
    }

}