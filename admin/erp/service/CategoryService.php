<?php

namespace admin\erp\service;

use admin\common\service\BaseService;

class CategoryService extends BaseService
{
    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getList($params, $ispage = true)
    {
        $selector = \Db::table('ProductCategory')->where(['status' => 1]);
        $flag = 1;
        if (isset($params['category_id']) && $params['category_id'] != '') {
            $selector->where(['category_id' => $params['category_id']]);
            $flag = 0;
        }
        if (isset($params['category_name']) && $params['category_name'] != '') {
            $selector->where(['category_name' => ['like', '%' . $params['category_name'] . '%']]);
            $flag = 0;
        }
        if (isset($params['level']) && $params['level'] != '') {
            $selector->where(['level' => $params['level']]);
            $flag = 0;
        }
        if (isset($params['has_child']) && $params['has_child'] != '') {
            $selector->where(['has_child' => $params['has_child']]);
            $flag = 0;
        }
        if (isset($params['parent_id']) && $params['parent_id'] != '') {
            $selector->where(['parent_id' => $params['parent_id']]);
            $flag = 0;
        }
        if ($flag == 1) {
            $selector->where(['parent_id' => 0]);
        }

        $selector->order('category_id desc');
        if ($ispage) {
            return $this->pagination($selector, $params);
        }
        return $selector->findAll();
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function saveCategory($data)
    {
        if (!empty($data['parent_id'])) {
            $pLevel = \Db::table('ProductCategory')->field(['level'])
                ->where(['category_id' => $data['parent_id'], 'has_child' => 1])->find();
            if (!$pLevel) {
                throw new \Exception('数据有误，请重试');
            }
            $data['level'] = $pLevel['level'] + 1;
        } else {
            $data['level'] = 1;
        }
        if (isset($data['category_id']) && $data['category_id']) {
            \Db::table('ProductCategory')->where(['category_id' => $data['category_id']])->update($data);
        } else {
            $data['category_id'] = \Db::table('ProductCategory')->insert($data);
        }
        $this->repairLevel($data['category_id'], $data['level'], 'ProductCategory', 'category_id', 'level');
    }


    /**
     * @param $data
     * @throws \Exception
     */
    public function deleteCategory($data)
    {
        \Db::table('ProductCategory')->where($data)->update(['status' => 0]);
    }

    public function getChild($excludeId = 0, $parent_id = 0, $i = 0)
    {
        $where = ['status' => 1, 'has_child' => 1];
        if (!empty($excludeId)) {
            $where['category_id'] = ['!=', $excludeId];
        }
        return parent::getChildList($parent_id, $i, 'ProductCategory', 'category_id', 'category_name', $where);
    }

    public function getAllCategory()
    {
        $where = ['status' => 1];
        $list = parent::getChildList(0, -1, 'ProductCategory', 'category_id', 'category_name', $where);
        array_shift($list);
        return $list;
    }
}