<?php

namespace admin\erp\controller;

use admin\common\controller\BaseController;
use admin\erp\service\MessageActivityChildService;

class MessageActivityChildController extends BaseController
{
    /** @var MessageActivityChildService */
    public $messageActivityChildService;

    public $linkTypeList = [
        '0' => '无跳转',
        '1' => '商品详情页',
        '2' => '搜索结果页',
    ];
    public $statusList = [
        '1' => '未发布',
        '2' => '发布中',
        '3' => '已发布',
    ];

    public function init()
    {
        $this->messageActivityChildService = new MessageActivityChildService();
        parent::init();
    }

    /**
     * @throws \Exception
     */
    public function indexAction()
    {
        $params = \App::$request->params;
        $params['page'] = \App::$request->getParams('page', 1);
        $params['pageSize'] = \App::$request->getParams('pageSize', 10);
        if (!empty($params['search_type'])) {
            $params[$params['search_type']] = $params['search_value'];
        }
        /** @var MessageActivityChildService $res */
        $res = $this->messageActivityChildService->getList($params);
        $list = $res->list;
        $ids = array_column($list, 'activity_id');
        $selector = \Db::table('MessageActivity')->where(['status' => 1]);
        if (count($ids)) {
            $selector->orWhere(['id' => ['in', $ids]]);
        }
        $activityList = $selector->findAll();
        $activityList = array_column($activityList, null, 'id');
        $this->assign('params', $params);
        $this->assign('pagination', $this->pagination($res));
        $this->assign('list', $list);
        $this->assign('linkTypeList', $this->linkTypeList);
        $this->assign('activityList', $activityList);
        $this->assign('statusList', $this->statusList);
    }

    /**
     * @throws \Exception
     */
    public function editAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                $params['pic'] = $this->parseFileOrUrl('pic', 'erp/message-activity-child');
                $this->messageActivityChildService->saveMessageActivityChild($params);
                return $this->success('保存成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
        $this->title = '创建活动消息';
        if (!empty($params['id'])) {
            $model = \Db::table('MessageActivityChild')->where(['id' => $params['id']])->find();
            if (!$model) {
                throw new \Exception('数据不存在');
            }

            if ($model['link_type'] == 1) {
                $product = \Db::table('Product')->where(['product_id' => $model['link']])->find();
                $this->assign('product', $product);
            }
            $this->assign('model', $model);
            $this->title = '编辑活动消息 - ' . $model['id'];
        }
        $activityList = $this->messageActivityChildService->getDataList('MessageActivity', 'id', 'title', ['status' => 1]);
        $this->assign('linkTypeList', $this->linkTypeList);
        $this->assign('activityList', $activityList);
    }

    /**
     * @throws \Exception
     */
    public function deleteAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                if (empty($params['id'])) {
                    throw new \Exception('非法请求');
                }
                \Db::table('MessageActivityChild')->where(['id' => $params['id']])->update(['status' => 0]);
                return $this->success('删除成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }


    }

    public function setSortAction()
    {
        try {
            $params = \App::$request->params->toArray();
            if (empty($params['id']) || empty($params['sort'])) {
                throw new \Exception('非法请求');
            }
            \Db::table('MessageActivityChild')->where(['id' => $params['id']])->update(['sort' => $params['sort']]);
            return $this->success('设置成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function publishAction()
    {
        try {
            $params = \App::$request->params->toArray();
            if (empty($params['ids']) || empty($params['activity_id'])) {
                throw new \Exception('非法请求');
            }
            $activity = \Db::table('MessageActivity')
                ->where(['id' => $params['activity_id']])
                ->find();
            $list = \Db::table('MessageActivityChild')
                ->where(['activity_id' => $params['activity_id']])
                ->where(['id' => ['in', explode(',', $params['ids'])]])
                ->where(['status' => 1])
                ->order('sort desc,id desc')
                ->findAll();
            if (!count($list)) {
                throw new \Exception('没有需要发布的内容');
            }
            \Db::table('MessageActivityChild')
                ->where(['activity_id' => $params['activity_id']])
                ->where(['id' => ['in', explode(',', $params['ids'])]])
                ->where(['status' => 1])
                ->update(['status' => 2, 'publish_version' => time()]);
            return $this->success('已加入发布队列');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}