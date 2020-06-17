<?php

namespace api\v1\service;


use api\common\service\BaseService;
use common\extend\wechat\Wechat;

class UserService extends BaseService
{
    /**
     * @param $params
     * @return array|mixed
     * @throws \Exception
     */
    public function getUserInfo($params)
    {
        if (!empty($params['openid'])) {
            $user = \Db::table('User')->where(['openid' => $params['openid']])->find();
            if (!$user) {
                throw new \Exception('openid有误，请重新登录', 999);
            }
        } else {
            $user = \Db::table('User')->where(['telephone' => $params['telephone']])->find();
            if (!$user) {
                $user = [
                    'telephone' => $params['telephone'],
                    'nickname' => $params['nickname'],
                    'city' => $params['city'],
                    'level' => 1,
                    'avatar' => $params['avatar'],
                    'birthday' => $params['birthday'],
                    'openid' => Wechat::instance()->getOpenIdByCode($params['code']),
                    'gender' => $params['gender'],
                    'is_promoter' => 0,
                    'status' => 1
                ];
                $spread = !empty(\App::$config['site_info']['spread']) ? json_decode(\App::$config['site_info']['spread'], true) : [];
                if ($spread['type'] == 2) {
                    $user['is_promoter'] = 1;
                }
                $user['user_id'] = \Db::table('User')->insert($user);
                \Db::table('UserWallet')->insert(['user_id' => $user['user_id']]);
            }
        }
        if ($user['status'] == 0) {
            throw new \Exception('用户已禁用，请联系管理员');
        }
        $res = [];
        $res['user_id'] = $user['user_id'];
        $res['level'] = $user['level'];
        $res['nickname'] = $user['nickname'];
        $res['avatar'] = $user['avatar'];
        $res['telephone'] = $user['telephone'];
        $res['openid'] = $user['openid'];
        $res['is_promoter'] = $user['is_promoter'];
        if ($user['level'] == 2) {
            $res['realname'] = $user['realname'];
            $res['company_name'] = $user['company_name'];
            $res['industry_id'] = $user['industry_id'];
        }
        \App::$user = $user;
        return $res;
    }
}