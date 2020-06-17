<?php

namespace admin\erp\service;

use admin\common\service\BaseService;

class ProductCommentService extends BaseService
{
    public $starList = [
        1 => '1星',
        2 => '2星',
        3 => '3星',
        4 => '4星',
        5 => '5星',
    ];

    public $isShowList = [
        '0' => '否',
        '1' => '是',
    ];

    public $statusList=[
        '1'=>'未回复',
        '2'=>'已回复'
    ];

    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getList($params, $ispage = true)
    {
        $selector = \Db::table('ProductComment')->where(['status' => ['!=', 0]]);

        if (isset($params['comment_id']) && $params['comment_id'] != '') {
            $selector->where(['comment_id' => $params['comment_id']]);
        }
        if (isset($params['order_id']) && $params['order_id'] != '') {
            $selector->where(['order_id' => $params['order_id']]);
        }
        if (isset($params['user_id']) && $params['user_id'] != '') {
            $selector->where(['user_id' => $params['user_id']]);
        }
        if (isset($params['product_id']) && $params['product_id'] != '') {
            $selector->where(['product_id' => $params['product_id']]);
        }
        if (isset($params['star']) && $params['star'] != '') {
            $selector->where(['star' => $params['star']]);
        }
        if (isset($params['detail']) && $params['detail'] != '') {
            $selector->where(['detail' => ['like', '%' . $params['detail'] . '%']]);
        }
        if (isset($params['is_show']) && $params['is_show'] != '') {
            $selector->where(['is_show' => $params['is_show']]);
        }
        if (isset($params['create_time']) && $params['create_time'] != '') {
            $selector->where(['create_time' => $params['create_time']]);
        }
        if (isset($params['update_time']) && $params['update_time'] != '') {
            $selector->where(['update_time' => $params['update_time']]);
        }
        if (isset($params['status']) && $params['status'] != '') {
            $selector->where(['status' => $params['status']]);
        }
        $selector->order('comment_id desc');
        if ($ispage) {
            return $this->pagination($selector, $params);
        }
        return $selector->findAll();
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function saveProductComment($data)
    {
        if (isset($data['comment_id']) && $data['comment_id']) {
            \Db::table('ProductComment')->where(['comment_id' => $data['comment_id']])->update($data);
        } else {
            \Db::table('ProductComment')->insert($data);
        }
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function deleteProductComment($data)
    {
        \Db::table('ProductComment')->where($data)->update(['status' => 0]);
    }

}