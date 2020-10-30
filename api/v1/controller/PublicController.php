<?php

namespace api\v1\controller;

use api\v1\service\UserService;

class PublicController extends BaseController
{
    /** @var UserService */
    public $userService;

    public function init()
    {
        $this->userService = new UserService();
        parent::init();
    }

    public function getUserInfoAction()
    {
        try {
            $params = \App::$request->params->toArray();
            if (empty($params['openid'])) {
                $checkList = [
                    'telephone' => '手机号不能为空',
                    'nickname' => '昵称不能为空',
                    'avatar' => '头像不能为空',
                    'code' => 'code不能为空',
                ];
                foreach ($checkList as $field => $message) {
                    if (!isset($params[$field]) || $params[$field] == '') {
                        throw new \Exception($message);
                    }
                }
            }
            $res = $this->userService->getUserInfo($params);
            return $this->success($res);
        } catch (\Exception $e) {
            return $this->error($e);
        }
    }

    public function indexAction()
    {
        $carousels = \Db::table('Carousel')
            ->field(['title', 'pic', 'link_type', 'link_id'])
            ->where(['carousel_type' => 1])
            ->where(['is_show' => 1])
            ->order('sort desc')
            ->findAll();
        $res = [
            'carousels' => $carousels
        ];
        return $this->success('success', $res);
    }
}