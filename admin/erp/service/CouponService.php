<?php

namespace admin\erp\service;

use admin\common\service\BaseService;

class CouponService extends BaseService
{
    public $typeList = [
        1 => '通用券',
        2 => '品类券',
        3 => '商品券'
    ];

    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getList($params, $ispage = true)
    {
        $selector = \Db::table('Coupon')->where(['status' => ['!=', 0]]);

        if (isset($params['coupon_id']) && $params['coupon_id'] != '') {
            $selector->where(['coupon_id' => $params['coupon_id']]);
        }
        if (isset($params['title']) && $params['title'] != '') {
            $selector->where(['title' => ['like', '%' . $params['title'] . '%']]);
        }
        if (isset($params['type']) && $params['type'] != '') {
            $selector->where(['type' => $params['type']]);
        }
        if (isset($params['price']) && $params['price'] != '') {
            $selector->where(['price' => $params['price']]);
        }
        if (isset($params['min_price']) && $params['min_price'] != '') {
            $selector->where(['min_price' => $params['min_price']]);
        }
        if (isset($params['expire']) && $params['expire'] != '') {
            $selector->where(['expire' => $params['expire']]);
        }
        if (isset($params['status']) && $params['status'] != '') {
            $selector->where(['status' => $params['status']]);
        }
        if (isset($params['create_time']) && $params['create_time'] != '') {
            $selector->where(['create_time' => $params['create_time']]);
        }
        if (isset($params['update_time']) && $params['update_time'] != '') {
            $selector->where(['update_time' => $params['update_time']]);
        }
        $selector->order('coupon_id desc');
        if ($ispage) {
            return $this->pagination($selector, $params);
        }
        return $selector->findAll();
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function saveCoupon($data)
    {
        if (isset($data['coupon_id']) && $data['coupon_id']) {
            \Db::table('Coupon')->where(['coupon_id' => $data['coupon_id']])->update($data);
        } else {
            \Db::table('Coupon')->insert($data);
        }
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function deleteCoupon($data)
    {
        \Db::table('Coupon')->where($data)->update(['status' => 0]);
    }

}