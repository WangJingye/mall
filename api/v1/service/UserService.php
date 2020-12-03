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
                throw new \Exception('token有误，请重新登录', 999);
            }
        } else {
            $openid = Wechat::instance()->getOpenIdByCode($params['code']);
            $user = \Db::table('User')
                ->where(['openid' => $openid])
                ->find();
            if (!$user) {
                $user = [
                    'nickname' => $params['nickname'],
                    'city' => $params['city'],
                    'gender' => $params['gender'],
                    'avatar' => $params['avatar'],
                    'openid' => $openid,
                    'status' => 1,
                    'is_promoter'=>0
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
        $res['nickname'] = $user['nickname'];
        $res['avatar'] = $user['avatar'];
        $res['identity'] = $user['openid'];
        $res['is_promoter'] = $user['is_promoter'];
        \App::$user = $user;
        return $res;
    }
}