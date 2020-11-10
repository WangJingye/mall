<?php

namespace common\service;

use admin\common\service\BaseService;

class CouponService extends BaseService
{
    public $typeList = [
        1 => '通用券',
        2 => '品类券',
        3 => '商品券'
    ];

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