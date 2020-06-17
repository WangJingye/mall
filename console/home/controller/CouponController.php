<?php

namespace console\home\controller;

use component\ConsoleController;

class CouponController extends ConsoleController
{
    public function init()
    {
        parent::init();
    }

    /**
     * 订单超时未支付取消
     * @throws \Exception
     */
    public function cancelAction()
    {
        \Db::table('CouponUser')->where([
            'expire_time' => ['<=', time()],
            'status' => 1,
        ])->update(['status' => 3]);
    }
}