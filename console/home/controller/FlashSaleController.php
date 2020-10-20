<?php

namespace console\home\controller;

use component\ConsoleController;

class FlashSaleController extends ConsoleController
{
    public function init()
    {
        parent::init();
    }

    /**
     * @throws \Exception
     */
    public function indexAction()
    {
        \Db::table('FlashSale')->where([
            'start_time' => ['<=', time()],
            'end_time' => ['>=', time()],
            'status' => 1
        ])->update(['status' => 2]);
        \Db::table('FlashSale')->where([
            'end_time' => ['<', time()],
            'status' => 2
        ])->update(['status' => 3]);
    }
}