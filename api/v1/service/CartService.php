<?php

namespace api\v1\service;

use api\common\service\BaseService;

class CartService extends BaseService
{
    public function deleteCart($codes)
    {
        \Db::table('Cart')
            ->where(['user_id' => \App::$user['user_id']])
            ->where(['variation_code'  => ['in', $codes]])
            ->delete();
    }
}