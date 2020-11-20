<?php

namespace console\home\controller;

use common\helper\ProductESHelper;
use component\ConsoleController;

class ProductController extends ConsoleController
{
    public function init()
    {
        parent::init();
    }

    public function syncEsAction()
    {
        $list = \Db::table('Product')
            ->field([
                'product_id', 'product_name', 'product_sub_name', 'category_name',
                'brand_id', 'price', 'pic', 'status'
            ])->where(['is_sync_es' => 0])->findAll();
        if (!count($list)) {
            echo '脚本执行成功' . PHP_EOL;
            return;
        }
        $brandIdList = array_column($list, 'brand_id');
        $brandList = \Db::table('Brand')
            ->field(['brand_id', 'brand_name'])
            ->where(['brand_id' => ['in', $brandIdList]])
            ->findAll();
        $brandList = array_column($brandList, 'brand_name', 'brand_id');
        $onlineList = [];
        $deletes = [];
        foreach ($list as $v) {
            if ($v['status'] != 1) {
                $deletes[] = $v['product_id'];
                continue;
            }
            $commentNumber = \Db::table('ProductComment')
                ->where(['product_id' => $v['product_id']])
                ->count();
            $goodNumber = \Db::table('ProductComment')
                ->where(['product_id' => $v['product_id']])
                ->where(['star' => ['>', 3]])
                ->count();
            $onlineList[] = [
                'product_id' => $v['product_id'],
                'product_name' => $v['product_name'],
                'product_sub_name' => $v['product_sub_name'],
                'category_name' => $v['category_name'],
                'brand' => $brandList[$v['brand_id']] ?? '',
                'price' => $v['price'],
                'pic' => $v['pic'],
                'comment_number' => $commentNumber,
                'good_comment_percent' => $commentNumber > 0 ? round($goodNumber / $commentNumber, 2) : 0
            ];
        }
        if (count($deletes)) {
            ProductESHelper::instance()->bulkDelete($deletes);
        }
        if (count($onlineList)) {
            ProductESHelper::instance()->bulkIndex($onlineList);
        }
        \Db::table('Product')
            ->where(['product_id' => ['in', array_column($list, 'product_id')]])
            ->update(['is_sync_es' => 1]);
        echo '脚本执行成功' . PHP_EOL;
    }
}