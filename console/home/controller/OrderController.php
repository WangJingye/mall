<?php

namespace console\home\controller;

use admin\erp\service\OrderService;
use common\helper\Constant;
use component\ConsoleController;

class OrderController extends ConsoleController
{
    public function init()
    {
        parent::init();
    }

    /**
     * 订单取消
     * @throws \Exception
     */
    public function cancelAction()
    {
        $orderList = \Db::table('Order')->where([
            'status' => Constant::ORDER_STATUS_CREATED,
            'create_time' => ['<=', time() + \App::$config['site_info']['expire_order_pay'] * 60]
        ])->findAll();
        try {
            \Db::startTrans();
            \Db::table('Order')
                ->where(['order_id' => ['in', array_column($orderList, 'order_id')]])
                ->update(['status' => Constant::ORDER_STATUS_CLOSE]);
            $variations = \Db::table('OrderVariation')
                ->field(['variation_code', 'number'])
                ->where(['order_id' => ['in', array_column($orderList, 'order_id')]])
                ->where(['status' => 1])->findAll();
            foreach ($variations as $v) {
                \Db::table('ProductVariation')->where(['variation_code' => $v['variation_code']])->increase('stock', $v['number']);
            }
            $insertList = [];
            foreach ($orderList as $v) {
                $insert = [
                    'order_id' => $v['order_id'],
                    'user_type' => 1,
                    'detail' => '超时关闭',
                    'create_userid' => 1,
                ];
                $insertList[] = $insert;
            }
            \Db::table('OrderTrace')->multiInsert($insertList);
            \Db::commit();
        } catch (\Exception $e) {
            \Db::rollback();
            echo $e->getMessage() . PHP_EOL;
        }
    }

    /**
     * 团购订单未成团取消
     * @throws \Exception
     */
    public function closeGrouponPendingAction()
    {
        $orderList = \Db::table('Order')->where([
            'status' => Constant::ORDER_STATUS_PAID,
            'order_group' => Constant::ORDER_GROUP_GROUPON,
            'pay_time' => ['<=', time() + \App::$config['site_info']['expire_order_pending'] * 60]
        ])->findAll();
        try {
            \Db::startTrans();
            \Db::table('Order')
                ->where(['order_id' => ['in', array_column($orderList, 'order_id')]])
                ->update(['status' => Constant::ORDER_STATUS_CLOSE]);
            $orderService = new OrderService();
            $orderService->refundByOrder($orderList);
            $variations = \Db::table('OrderVariation')
                ->field(['variation_code', 'number'])
                ->where(['order_id' => ['in', array_column($orderList, 'order_id')]])
                ->where(['status' => 1])->findAll();
            foreach ($variations as $v) {
                \Db::table('ProductVariation')->where(['variation_code' => $v['variation_code']])->increase('stock', $v['number']);
            }
            $insertList = [];
            foreach ($orderList as $v) {
                $insert = [
                    'order_id' => $v['order_id'],
                    'user_type' => 1,
                    'detail' => '超时关闭',
                    'create_userid' => 1,
                ];
                $insertList[] = $insert;
            }
            \Db::table('OrderTrace')->multiInsert($insertList);
            \Db::commit();
        } catch (\Exception $e) {
            \Db::rollback();
            echo $e->getMessage() . PHP_EOL;
        }
    }

    /**
     * @throws \Exception
     */
    public function finishAction()
    {
        $orderList = \Db::table('Order')->where([
            'status' => Constant::ORDER_STATUS_SHIPPED,
            'deliver_time' => ['<=', time() + \App::$config['site_info']['expire_order_finish'] * 24 * 3600]
        ])->findAll();
        try {
            \Db::startTrans();
            \Db::table('Order')
                ->where(['order_id' => ['in', array_column($orderList, 'order_id')]])
                ->update(['status' => Constant::ORDER_STATUS_RECEIVED]);
            $orderService = new OrderService();
            $orderService->refundByOrder($orderList);
            $insertList = [];
            foreach ($orderList as $v) {
                $detail = '超时自动确认收货';
                if ($v['order_type'] == Constant::ORDER_TYPE_VIRTUAL) {
                    $detail = '电子券超时过期';
                }
                $insert = [
                    'order_id' => $v['order_id'],
                    'user_type' => 1,
                    'detail' => $detail,
                    'create_userid' => 1,
                ];
                $insertList[] = $insert;
            }
            \Db::table('OrderTrace')->multiInsert($insertList);
            \Db::commit();
        } catch (\Exception $e) {
            \Db::rollback();
            echo $e->getMessage() . PHP_EOL;
        }
    }

    /**
     * @throws \Exception
     */
    public function autoCommentAction()
    {
        $orderList = \Db::table('Order')->where([
            'status' => Constant::ORDER_STATUS_RECEIVED,
            'receive_time' => ['<=', time() + \App::$config['site_info']['expire_order_comment'] * 24 * 3600]
        ])->findAll();
        $orderIdList = array_column($orderList, 'order_id');
        try {
            \Db::startTrans();
            \Db::table('Order')->where(['order_id' => ['in', $orderIdList]])
                ->update(['status' => Constant::ORDER_STATUS_COMPLETE]);
            $insertList = [];
            foreach ($orderList as $v) {
                $insert = [
                    'order_id' => $v['order_id'],
                    'detail' => '超时未评价，系统自动好评',
                    'create_userid' => 1,
                ];
                $insertList[] = $insert;
            }
            \Db::table('OrderTrace')->multiInsert($insertList);
            $insertList = [];
            $variations = \Db::table('OrderVariation')
                ->field(['order_id', 'product_id', 'variation_code'])
                ->where(['order_id' => ['in', $orderIdList]])
                ->findAll();
            $variationList = [];
            foreach ($variations as $v) {
                $variationList[$v['order_id']][] = $v;
            }
            foreach ($orderList as $v) {
                $variations = isset($variationList[$v['order_id']]) ? $variationList[$v['order_id']] : [];
                foreach ($variations as $item) {
                    $insert = [
                        'order_id' => $v['order_id'],
                        'product_id' => $item['product_id'],
                        'variation_code' => $item['variation_code'],
                        'user_id' => 0,
                        'star' => 5,
                        'detail' => '超时未评价，系统自动好评',
                    ];
                    $insertList[] = $insert;
                }
            }
            \Db::table('ProductComment')->multiInsert($insertList);
            \Db::commit();
        } catch (\Exception $e) {
            \Db::rollback();
            echo $e->getMessage() . PHP_EOL;
        }
    }
}