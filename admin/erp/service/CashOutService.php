<?php

namespace admin\erp\service;

use admin\common\service\BaseService;
use common\extend\excel\SpreadExcel;

class CashOutService extends BaseService
{
    public $statusList = [
        '1' => '待审核',
        '2' => '审核通过',
        '3' => '审核拒绝',
    ];

    /**
     * @param $params
     * @throws \Exception
     */
    public function export($params)
    {
        if ($params['export_type'] == 1) {
            $list = $this->getList($params, false);
        } else {
            $list = \Db::table('CashOut')->where(['id' => ['in', explode(',', $params['ids'])]])->findAll();
        }
        if (count($list) > 10000) {
            throw new \Exception('最多导出1万条数据');
        }
        if (count($list) == 0) {
            throw new \Exception('没有符合条件的数据');
        }
        $userList = $this->getDataList('User', 'user_id', 'nickname', ['user_id' => ['in', array_column($list, 'user_id')]]);
        $operatorList = $this->getDataList('Admin', 'admin_id', 'realname', ['admin_id' => ['in', array_column($list, 'operator_id')]]);
        $data = [];
        foreach ($list as $v) {
            $arr = [
                $v['id'],
                $userList[$v['user_id']],
                $v['amount'],
                $this->statusList[$v['status']],
                $operatorList[$v['operator_id']],
                date('Y-m-d H:i:s', $v['create_time']),
                date('Y-m-d H:i:s', $v['verify_time']),
            ];
            $data[] = $arr;
        }
        $export = [];
        $export['table_name'] = '提现申请数据';
        $export['info'] = ['ID', '用户', '提现金额', '状态', '操作员', '创建时间', '审核时间'];
        $export['data'] = $data;
        SpreadExcel::exportExcel($export);
    }

    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getList($params, $ispage = true)
    {
        $selector = \Db::table('CashOut')->where(['status' => ['!=', 0]]);

        if (isset($params['id']) && $params['id'] != '') {
            $selector->where(['id' => $params['id']]);
        }
        if (isset($params['user_id']) && $params['user_id'] != '') {
            $selector->where(['user_id' => $params['user_id']]);
        }
        if (isset($params['amount']) && $params['amount'] != '') {
            $selector->where(['amount' => $params['amount']]);
        }
        if (isset($params['status']) && $params['status'] != '') {
            $selector->where(['status' => $params['status']]);
        }
        if (isset($params['operator']) && $params['operator'] != '') {
            $selector->where(['operator' => $params['operator']]);
        }
        if (isset($params['create_time']) && $params['create_time'] != '') {
            $selector->where(['create_time' => $params['create_time']]);
        }
        if (isset($params['update_time']) && $params['update_time'] != '') {
            $selector->where(['update_time' => $params['update_time']]);
        }
        $selector->order('id desc');
        if ($ispage) {
            return $this->pagination($selector, $params);
        }
        return $selector->findAll();
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function deleteCashOut($data)
    {
        \Db::table('CashOut')->where($data)->update(['status' => 0]);
    }

}