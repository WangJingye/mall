<?php

namespace console\home\controller;

use component\ConsoleController;

class GrouponController extends ConsoleController
{
    public function init()
    {
        parent::init();
    }

    /**
     * 订单超时未支付取消
     * @throws \Exception
     */
    public function indexAction()
    {
        \Db::table('Groupon')->where([
            'start_time' => ['<=', time()],
            'end_time' => ['>=', time()],
            'status' => 1
        ])->update(['status' => 2]);
        \Db::table('Groupon')->where([
            'end_time' => ['<', time()],
            'status' => 2
        ])->update(['status' => 3]);
    }
}