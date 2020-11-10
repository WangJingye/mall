<?php

namespace common\service;

class BaseService extends \Service
{
    /**
     * @param $data
     * @param string $idField
     * @throws \Exception
     */
    public function getOperateUserList($data, $idField = 'create_userid')
    {
        $adminIdList = $userIdList = [];
        foreach ($data as $v) {
            if ($v['user_type'] == 1) {
                $adminIdList[] = $v[$idField];
            } else {
                $userIdList[] = $v[$idField];
            }
        }
        return [
            1 => $this->getDataList('Admin', 'admin_id', 'realname', ['admin_id' => ['in', $adminIdList]]),
            2 => $this->getDataList('User', 'user_id', 'nickname', ['user_id' => ['in', $userIdList]])
        ];
    }

    /**
     * 获取数据
     * @param $table
     * @param string $idField
     * @param string $nameField
     * @param array $where
     * @return array
     * @throws \Exception
     */
    public function getDataList($table, $idField = 'id', $nameField = 'name', $where = ['status' => 1])
    {
        $selector = \Db::table($table)->field([$idField, $nameField]);
        if (!empty($where)) {
            $selector->where($where);
        }
        $list = $selector->findAll();
        return array_column($list, $nameField, $idField);
    }
}