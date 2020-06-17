<?php

namespace admin\erp\controller;

use admin\common\controller\BaseController;
use admin\extend\Constant;

class SiteInfoController extends BaseController
{

    public function init()
    {
        parent::init();
    }

    /**
     * @throws \Exception
     */
    public function wechatAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                $siteInfo = \Db::table('SiteInfo')->find();
                if ($siteInfo) {
                    \Db::table('SiteInfo')->update($params);
                } else {
                    \Db::table('SiteInfo')->insert($params);
                }
                return $this->success('保存成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
        $model = \Db::table('SiteInfo')->find();
        $this->assign('model', $model);
    }

    /**
     * @throws \Exception
     */
    public function baseInfoAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                $siteInfo = \Db::table('SiteInfo')->find();
                if ($siteInfo) {
                    \Db::table('SiteInfo')->update($params);
                } else {
                    \Db::table('SiteInfo')->insert($params);
                }
                return $this->success('保存成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
        $model = \Db::table('SiteInfo')->find();
        $this->assign('model', $model);
    }

    /**
     * @throws \Exception
     */
    public function appInfoAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                $siteInfo = \Db::table('SiteInfo')->find();
                $params['app_logo'] = $this->parseFileOrUrl('app_logo', 'erp/site-info');
                if ($siteInfo) {
                    \Db::table('SiteInfo')->update($params);
                } else {
                    \Db::table('SiteInfo')->insert($params);
                }
                return $this->success('保存成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
        $model = \Db::table('SiteInfo')->find();
        $this->assign('model', $model);
    }

    /**
     * 首页
     * @throws \Exception
     */
    public function indexAction()
    {
        $today = strtotime(date('Y-m-d'));
        $data['order_total'] = \Db::table('Order')->where(['create_time' => ['>=', $today]])->count();
        $todaySales = \Db::table('Order')->field('sum(money) as money')->where(['create_time' => ['>=', $today]])->find();
        $data['today_sales'] = empty($todaySales['money']) ? 0 : $todaySales['money'];
        $yesterdaySales = \Db::table('Order')->field('sum(money) as money')
            ->where(['create_time' => ['>=', $today - 24 * 3600]])
            ->where(['create_time' => ['<', $today]])
            ->find();
        $data['yesterday_sales'] = empty($yesterdaySales['money']) ? 0 : $yesterdaySales['money'];
        $weekSales = \Db::table('Order')->field('sum(money) as money')
            ->where(['create_time' => ['>=', $today - 6 * 24 * 3600]])
            ->find();
        $data['week_sales'] = empty($weekSales['money']) ? 0 : $weekSales['money'];

        $data['product_online'] = \Db::table('Product')->where(['status' => 1])->count();
        $data['product_offline'] = \Db::table('Product')->where(['status' => 2])->count();
        $data['product_total'] = $data['product_online'] + $data['product_offline'];

        $data['today_user_normal'] = \Db::table('User')
            ->where(['level' => 1])
            ->where(['create_time' => ['>=', $today]])
            ->count();
        $data['yesterday_user_normal'] = \Db::table('User')
            ->where(['level' => 1])
            ->where(['create_time' => ['>=', $today - 24 * 3600]])
            ->where(['create_time' => ['<', $today]])
            ->count();
        $data['month_user_normal'] = \Db::table('User')
            ->where(['level' => 1])
            ->where(['create_time' => ['>=', strtotime(date('Y-m-01'))]])
            ->count();
        $data['user_total_normal'] = \Db::table('User')
            ->where(['level' => 1])
            ->count();
        $data['today_user_profession'] = \Db::table('User')
            ->where(['level' => 2])
            ->where(['create_time' => ['>=', $today]])
            ->count();
        $data['yesterday_user_profession'] = \Db::table('User')
            ->where(['level' => 2])
            ->where(['create_time' => ['>=', $today - 24 * 3600]])
            ->where(['create_time' => ['<', $today]])
            ->count();
        $data['month_user_profession'] = \Db::table('User')
            ->where(['level' => 2])
            ->where(['create_time' => ['>=', strtotime(date('Y-m-01'))]])
            ->count();
        $data['user_total_profession'] = \Db::table('User')
            ->where(['level' => 2])
            ->count();

        $data['order_real_unpaid'] = \Db::table('Order')
            ->where(['order_type' => 1])
            ->where(['status' => Constant::ORDER_STATUS_CREATE])
            ->count();
        $data['order_real_undelviver'] = \Db::table('Order')
            ->where(['order_type' => 1])
            ->where(['status' => Constant::ORDER_STATUS_PAID])
            ->count();
        $data['order_virtual_unpaid'] = \Db::table('Order')
            ->where(['order_type' => 2])
            ->where(['status' => Constant::ORDER_STATUS_CREATE])
            ->count();
        $data['order_virtual_unused'] = \Db::table('Order')
            ->where(['order_type' => 2])
            ->where(['status' => Constant::ORDER_STATUS_PAID])
            ->count();

        $data['product_unverified']=\Db::table('Product')
            ->where(['verify_status' => 0])
            ->count();
        $data['user_unverified']=\Db::table('UserVerify')
            ->where(['verify_status' => 0])
            ->count();
        $this->assign('data', $data);
    }
}