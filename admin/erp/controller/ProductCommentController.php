<?php

namespace admin\erp\controller;

use admin\common\controller\BaseController;
use admin\erp\service\ProductCommentService;

class ProductCommentController extends BaseController
{
    /** @var ProductCommentService */
    public $productCommentService;

    public function init()
    {
        $this->productCommentService = new ProductCommentService();
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
        /** @var ProductCommentService $res */
        $res = $this->productCommentService->getList($params);
        $list = $res->list;
        $productIdList = array_column($list, 'product_id');
        $productList = $this->productCommentService->getDataList('Product', 'product_id', 'product_name', ['product_id' => ['in', $productIdList]]);
        $this->assign('productList', $productList);
        $operatorList = $this->productCommentService->getOperateUserList($list, 'user_id');
        $this->assign('operatorList', $operatorList);
        $this->assign('params', $params);
        $this->assign('pagination', $this->pagination($res));
        $this->assign('list', $list);
        $this->assign('starList', $this->productCommentService->starList);
        $this->assign('isShowList', $this->productCommentService->isShowList);
        $this->assign('statusList', $this->productCommentService->statusList);
    }

    /**
     * @throws \Exception
     */
    public function editAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                $this->productCommentService->saveProductComment($params);
                return $this->success('保存成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
        $this->title = '创建评论';
        if (isset($params['comment_id']) && $params['comment_id']) {
            $model = \Db::table('ProductComment')->where(['comment_id' => $params['comment_id']])->find();
            if (!$model) {
                throw new \Exception('数据不存在');
            }
            $this->assign('model', $model);
            $this->title = '编辑评论 - ' . $model['comment_id'];
        }
        $this->assign('isShowList', $this->productCommentService->isShowList);
    }

    /**
     * @throws \Exception
     */
    public function deleteAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                if (!isset($params['comment_id']) || $params['comment_id'] == '') {
                    throw new \Exception('非法请求');
                }
                $this->productCommentService->deleteProductComment($params);
                return $this->success('删除成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }

    /**
     * 设置是否显示
     */
    public function setShowAction()
    {
        try {
            $params = \App::$request->params->toArray();
            if (empty($params['id']) || !isset($params['is_show']) || $params['is_show'] == '') {
                throw new \Exception('非法请求');
            }
            \Db::table('ProductComment')->where(['comment_id' => $params['id']])->update(['is_show' => $params['is_show']]);
            return $this->success('设置成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * 回复
     */
    public function replyAction()
    {
        try {
            $params = \App::$request->params->toArray();
            if (empty($params['ids']) || !isset($params['reply']) || $params['reply'] == '') {
                throw new \Exception('非法请求');
            }
            \Db::table('ProductComment')
                ->where(['comment_id' => ['in', explode(',', $params['ids'])]])
                ->where(['status' => 1])
                ->update([
                    'reply' => $params['reply'],
                    'operator' => \App::$user['admin_id'],
                    'status' => 2
                ]);
            return $this->success('已回复');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }


}