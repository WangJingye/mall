<?php

namespace admin\erp\service;

use admin\common\service\BaseService;
use common\extend\excel\SpreadExcel;

class UserService extends BaseService
{
    public $genderList = [
        '1' => '男',
        '2' => '女',
    ];

    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getList($params, $ispage = true)
    {
        $selector = \Db::table('User');

        if (isset($params['user_id']) && $params['user_id'] != '') {
            $selector->where(['user_id' => $params['user_id']]);
        }
        if (isset($params['level']) && $params['level'] != '') {
            $selector->where(['level' => $params['level']]);
        }
        if (isset($params['nickname']) && $params['nickname'] != '') {
            $selector->where(['nickname' => ['like', '%' . $params['nickname'] . '%']]);
        }
        if (isset($params['city']) && $params['city'] != '') {
            $selector->where(['city' => ['like', '%' . $params['city'] . '%']]);
        }
        if (isset($params['avatar']) && $params['avatar'] != '') {
            $selector->where(['avatar' => ['like', '%' . $params['avatar'] . '%']]);
        }
        if (isset($params['telephone']) && $params['telephone'] != '') {
            $selector->where(['telephone' => ['like', '%' . $params['telephone'] . '%']]);
        }
        if (isset($params['birthday']) && $params['birthday'] != '') {
            $selector->where(['birthday' => $params['birthday']]);
        }
        if (isset($params['openid']) && $params['openid'] != '') {
            $selector->where(['openid' => ['like', '%' . $params['openid'] . '%']]);
        }
        if (isset($params['gender']) && $params['gender'] != '') {
            $selector->where(['gender' => $params['gender']]);
        }
        if (isset($params['create_time']) && $params['create_time'] != '') {
            $selector->where(['create_time' => $params['create_time']]);
        }
        if (isset($params['upgrade_time']) && $params['upgrade_time'] != '') {
            $selector->where(['upgrade_time' => $params['upgrade_time']]);
        }
        if (isset($params['update_time']) && $params['update_time'] != '') {
            $selector->where(['update_time' => $params['update_time']]);
        }
        if (isset($params['is_promoter']) && $params['is_promoter'] != '') {
            $selector->where(['is_promoter' => $params['is_promoter']]);
        }
        if (isset($params['spread_id']) && $params['spread_id'] != '') {
            $selector->where(['spread_id' => $params['spread_id']]);
        }
        $selector->order('user_id desc');
        if ($ispage) {
            return $this->pagination($selector, $params);
        }
        return $selector->findAll();
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function saveUser($data)
    {
        if (isset($data['user_id']) && $data['user_id']) {
            \Db::table('User')->where(['user_id' => $data['user_id']])->update($data);
        } else {
            \Db::table('User')->insert($data);
        }
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function deleteUser($data)
    {
        \Db::table('User')->where($data)->delete();
    }

    public function export($params)
    {
        if ($params['export_type'] == 1) {
            $list = $this->getList($params, false);
        } else {
            $list = \Db::table('User')->where(['user_id' => ['in', explode(',', $params['ids'])]])->findAll();
        }
        if (count($list) > 10000) {
            throw new \Exception('最多导出1万条数据');
        }
        if (count($list) == 0) {
            throw new \Exception('没有符合条件的数据');
        }
        foreach ($list as $v) {
            $data[] = [
                $v['user_id'],
                $v['nickname'],
                $v['telephone'],
                $v['city'],
                $this->genderList[$v['gender']],
                $v['birthday'],
                date('Y-m-d H:i:s', $v['create_time'])
            ];
        }

        $export = [];
        $export['table_name'] = '普通会员数据';
        $export['info'] = ['会员ID', '昵称', '手机号', '城市', '性别', '生日', '注册时间'];
        $export['data'] = $data;
        SpreadExcel::exportExcel($export);
    }

    public function exportProfession($params)
    {
        if ($params['export_type'] == 1) {
            $list = $this->getList($params, false);
        } else {
            $list = \Db::table('User')->where(['user_id' => ['in', explode(',', $params['ids'])]])->findAll();
        }
        if (count($list) > 10000) {
            throw new \Exception('最多导出1万条数据');
        }
        if (count($list) == 0) {
            throw new \Exception('没有符合条件的数据');
        }
        foreach ($list as $v) {
            $data[] = [
                $v['user_id'],
                $v['nickname'],
                $v['telephone'],
                $v['company_name'],
                date('Y-m-d H:i:s', $v['create_time']),
                date('Y-m-d H:i:s', $v['upgrade_time'])
            ];
        }

        $export = [];
        $export['table_name'] = '企业会员数据';
        $export['info'] = ['会员ID', '昵称', '手机号', '公司', '注册时间', '升级时间'];
        $export['data'] = $data;
        SpreadExcel::exportExcel($export);
    }

}