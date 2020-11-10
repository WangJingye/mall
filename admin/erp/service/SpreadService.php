<?php

namespace admin\erp\service;

use admin\common\service\BaseService;
use common\helper\Constant;

class SpreadService extends BaseService
{
    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getList($params, $ispage = true)
    {
        $selector = \Db::table('UserSpread');

        if (isset($params['id']) && $params['id'] != '') {
            $selector->where(['id' => $params['id']]);
        }
        if (isset($params['spread_id']) && $params['spread_id'] != '') {
            $selector->where(['spread_id' => $params['spread_id']]);
        }
        if (isset($params['order_code']) && $params['order_code'] != '') {
            $selector->where(['order_code' => ['like', '%' . $params['order_code'] . '%']]);
        }
        if (isset($params['user_id']) && $params['user_id'] != '') {
            $selector->where(['user_id' => $params['user_id']]);
        }
        $selector->order('id desc');
        if ($ispage) {
            return $this->pagination($selector, $params);
        }
        return $selector->findAll();
    }

    /**
     * 推广返利
     * @param $order
     * @throws \Exception
     */
    public function spreadBackMoney($order)
    {
        $config = \App::$config['site_info']['spread'] ? json_decode(\App::$config['site_info']['spread'], true) : [];
        if (empty($config['back'])) {
            return;
        }
        $userId = $order['user_id'];
        $user = \Db::table('User')->field(['spread_id'])->where(['user_id' => $userId])->find();
        if (!$user || !$user['spread_id']) {
            return;
        }
        $spreadId = $user['spread_id'];
        foreach ($config['back'] as $v) {
            $money = $order['pay_money'] * $v / 100;
            \Db::table('UserWallet')->where(['user_id' => $spreadId])->increase([
                'balance' => $money,
                'spread_money' => $money,
                'spread_order_money' => $order['pay_money'],
            ]);
            $data['spread_id'] = $spreadId;
            $data['order_id'] = $order['order_id'];
            $data['order_code'] = $order['order_code'];
            $data['user_id'] = $order['user_id'];
            $data['back_money'] = $money;
            \Db::table('UserSpread')->insert($data);
            $bill = [
                'user_id' => $spreadId,
                'bill_type' => Constant::BILL_TYPE_SPREAD,
                'title' => '订单分销返现',
                'amount' => $money,
                'pay_method' => 0,
                'transaction_id' => '',
            ];
            \Db::table('UserBill')->insert($bill);
            $parent = \Db::table('User')->field(['spread_id'])->where(['user_id' => $spreadId]);
            if (!$parent || !$parent['spread_id']) {
                break;
            }
            $spreadId = $parent['spread_id'];
        }
    }
}