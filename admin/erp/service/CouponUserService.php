<?php

namespace admin\erp\service;

use admin\common\service\BaseService;

class CouponUserService extends BaseService
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
        $selector = \Db::table('CouponUser')->where(['status' => ['!=', 0]]);

        if (isset($params['id']) && $params['id'] != '') {
            $selector->where(['id' => $params['id']]);
        }
        if (isset($params['user_id']) && $params['user_id'] != '') {
            $selector->where(['user_id' => $params['user_id']]);
        }
        if (isset($params['coupon_id']) && $params['coupon_id'] != '') {
            $selector->where(['coupon_id' => $params['coupon_id']]);
        }
        if (isset($params['price']) && $params['price'] != '') {
            $selector->where(['price' => $params['price']]);
        }
        if (isset($params['min_price']) && $params['min_price'] != '') {
            $selector->where(['min_price' => $params['min_price']]);
        }
        if (isset($params['create_time']) && $params['create_time'] != '') {
            $selector->where(['create_time' => $params['create_time']]);
        }
        if (isset($params['status']) && $params['status'] != '') {
            $selector->where(['status' => $params['status']]);
        }
        if (isset($params['type']) && $params['type'] != '') {
            $selector->where(['type' => $params['type']]);
        }
        if (isset($params['expire_time']) && $params['expire_time'] != '') {
            $selector->where(['expire_time' => $params['expire_time']]);
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
    public function saveCouponUser($data)
    {
        $coupon = \Db::table('Coupon')->where(['coupon_id' => $data['coupon_id']])->find();
        if (!$coupon) {
            throw new \Exception('优惠券有误');
        }
        $data['price'] = $coupon['price'];
        $data['min_price'] = $coupon['min_price'];
        $data['coupon_name'] = $coupon['title'];
        $data['type'] = $coupon['type'];
        $data['relation_id'] = $coupon['relation_id'];
        if (isset($data['id']) && $data['id']) {
            \Db::table('CouponUser')->where(['id' => $data['id']])->update($data);
        } else {
            $data['create_time'] = time();
            $data['expire_time'] = $data['create_time'] + $coupon['expire'] * 60;
            \Db::table('CouponUser')->insert($data);
        }
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function deleteCouponUser($data)
    {
        \Db::table('CouponUser')->where($data)->update(['status' => 0]);
    }

}