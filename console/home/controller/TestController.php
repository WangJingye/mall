<?php

namespace console\home\controller;

use admin\erp\service\CategoryService;
use component\ConsoleController;

class TestController extends ConsoleController
{
    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        $service = new  CategoryService();
        $list = $service->getChildIdList(1, 'ProductCategory', 'category_id');
        var_dump($list);
        echo '脚本执行成功' . PHP_EOL;
    }
}