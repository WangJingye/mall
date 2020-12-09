<?php

namespace admin\common\Service;

class BaseService extends \common\service\BaseService
{
    /**
     * @param int $parent_id
     * @param int $i
     * @param $table
     * @param string $idField
     * @param string $nameField
     * @param array $additionWhere
     * @param string $order
     * @return array
     * @throws \Exception
     */
    public function getChildList($parent_id = 0, $i = 0, $table, $idField = 'id', $nameField = 'name', $additionWhere = [], $order = '')
    {
        $types = [];
        if ($parent_id == 0) {
            $types[] = ['id' => $parent_id, 'name' => '顶级目录'];
        }
        $selector = \Db::table($table)->where(['parent_id' => $parent_id]);
        if (empty($additionWhere)) {
            $additionWhere = ['status' => 1];
        }
        $selector->where($additionWhere);
        if (empty($order)) {
            $order = $idField . ' asc';
        }
        $rows = $selector->order($order)->findAll();
        $i++;
        foreach ($rows as $v) {
            $name = str_pad($v[$nameField], (strlen($v[$nameField]) + $i * 2), '--', STR_PAD_LEFT);
            $types[] = ['id' => $v[$idField], 'name' => $name];
            $childTypes = $this->getChildList($v[$idField], $i, $table, $idField, $nameField, $additionWhere);
            $types = array_merge($types, $childTypes);
        }
        return $types;
    }

    /**
     * 获取子级id列表
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function getChildIdList($id, $table, $idField)
    {
        $arr = [];
        $categoryList = \Db::table($table)->field([$idField])
            ->where(['parent_id' => $id])->findAll();
        foreach ($categoryList as $v) {
            $arr[] = $v[$idField];
            $arr = array_merge($arr, $this->getChildIdList($v[$idField], $table, $idField));
        }
        return $arr;
    }

    /**
     * 获取父级id列表
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function getParentIdList($id, $table, $idField)
    {
        if ($id == 0) {
            return [];
        }
        $arr = [];
        $category = \Db::table($table)->field(['parent_id'])
            ->where([$idField => $id])->find();
        $arr[] = $category['parent_id'];
        $arr = array_merge($arr, $this->getParentIdList($category['parent_id'], $table, $idField));
        return $arr;
    }

    /**
     * 获取搜索下拉树状结构
     * @param $table
     * @param array $checked
     * @param string $idField
     * @param string $nameField
     * @param array $additionWhere
     * @param string $order
     * @return array
     * @throws \Exception
     */
    public function getTreeList($table, $checked = [], $idField = 'id', $nameField = 'name', $additionWhere = [], $order = '')
    {
        if (!is_array($checked)) {
            $checked = explode(',', $checked);
        }
        $needList = [];
        $depotList = \Db::table($table)->where(['status' => 1])->findAll();
        foreach ($depotList as $v) {
            $need = [];
            $need['id'] = $v[$idField];
            $need['pId'] = $v['parent_id'];
            $need['name'] = $v[$nameField];
            if (in_array($need['id'], $checked)) {
                $need['checked'] = true;
            }
            $needList[] = $need;
        }
        return $needList;
    }


    /**
     * 修复层级结构等级标志
     * @param $pid
     * @param $level
     * @param $table
     * @param $idField
     * @param $levelField
     * @throws \Exception
     */
    public function repairLevel($pid, $level, $table, $idField, $levelField)
    {
        $level++;
        $list = \Db::table($table)->field([$idField])->where(['parent_id' => $pid])->findAll();
        if (!count($list)) {
            return;
        }
        \Db::table($table)->where(['parent_id' => $pid])->update([$levelField => $level]);
        foreach ($list as $v) {
            $this->repairLevel($v[$idField], $level, $table, $idField, $levelField);
        }
    }

    /**
     * 生成单号
     * @param string $prefix
     * @return string
     */
    public function generateCode($prefix = '')
    {
        return $prefix . date('YmdHis') . str_pad(rand(000000, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * 商品追踪日志
     * @param $detail
     * @param $params
     * @param int $userType
     * @throws \Exception
     */
    public function productTrace($detail, $params, $userType = 1)
    {
        if (empty($params['product_id'])) {
            return;
        }
        $data = [
            'product_id' => $params['product_id'],
            'create_userid' => $userType == 1 ? \App::$user['admin_id'] : \App::$user['user_id'],
            'user_type' => $userType,
            'detail' => $detail,
            'params' => json_encode($params)
        ];
        \Db::table('ProductTrace')->insert($data);
    }

    /**
     * 订单日志
     * @param $detail
     * @param $params
     * @param int $userType
     * @throws \Exception
     */
    public function orderTrace($detail, $orderId, $userType = 1)
    {
        if (empty($orderId)) {
            return;
        }
        $data = [
            'order_id' => $orderId,
            'create_userid' => $userType == 1 ? \App::$user['admin_id'] : \App::$user['user_id'],
            'user_type' => $userType,
            'detail' => $detail,
        ];
        \Db::table('OrderTrace')->insert($data);
    }

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