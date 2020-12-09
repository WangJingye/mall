<?php

namespace admin\erp\service;

class CouponService extends \common\service\CouponService
{
    /**
     * @param $params
     * @return array|\Service
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

    public function checkCouponAvailable($coupon_id, $variations)
    {
        if (is_array($coupon_id)) {
            $coupon = $coupon_id;
        } else {
            $coupon = \Db::table('CouponUser')->where(['id' => $coupon_id])->find();
        }
        $used = 0;
        if ($coupon['type'] == 2) {//品类券
            $categoryIdList = $this->getChildIdList($coupon['relation_id'], 'ProductCategory', 'category_id');
            $categoryIdList[] = $coupon['relation_id'];
            $total = 0;
            foreach ($variations as $v) {
                if (in_array($v['category_id'], $categoryIdList)) {
                    $total += $v['price'] * $v['number'];
                    $used = 1;
                }
            }
            if ($total < $coupon['min_price']) {
                return false;
            }
        } else if ($coupon['type'] == 3) {//商品券
            $total = 0;
            foreach ($variations as $v) {
                if ($v['product_id'] == $coupon['relation_id']) {
                    $total += $v['price'] * $v['number'];
                    $used = 1;
                }
            }
            if ($total < $coupon['min_price']) {
                return false;
            }
        } else {
            $total = 0;
            foreach ($variations as $v) {
                $total += $v['price'] * $v['number'];
                $used = 1;
            }
            if ($total < $coupon['min_price']) {
                return false;
            }
        }
        if ($used == 1) {
            return true;
        }
        return false;
    }
}