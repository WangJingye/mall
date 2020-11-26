<?php

namespace api\v1\controller;

use common\helper\ProductESHelper;

class SearchController extends BaseController
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        $params = \App::$request->params->toArray();
        if (empty($params['content'])) {
            return $this->success('success', ['page' => 1, 'total_page' => 0, 'list' => []]);
        }
        $page = 1;
        if (!empty($params['page'])) {
            $page = $params['page'];
        }
        $size = 10;
        $sort = [];
        if (!empty($params['sort'])) {
            $sort = $params['sort'];
            $sortList = [
                'new' => 'created_at',
                'comment' => 'comment_number',
                'sale' => 'sale_number',
            ];
            if (isset($sortList[$sort['name']])) {
                $sort['name'] = $sortList[$sort['name']];
            } else {
                $sort = [];
            }
        }
        $res = ProductESHelper::instance()->search($params['content'], $page, $size, $sort);
        return $this->success('success', $res);
    }

    /**
     * 获取推荐内容
     */
    public function recommendAction()
    {
        $params = \App::$request->params->toArray();
        $selector = \Db::table('SearchRecommend')
            ->field(['title'])
            ->order('sort desc,id desc');
        $page = 1;
        if (isset($params['page'])) {
            $page = $params['page'];
        }
        $pageSize = 20;
        $total = $selector->count();
        $totalPage = (int)ceil($total / $pageSize);
        $page = min($page, $totalPage);
        $page = max($page, 1);
        $list = $selector
            ->limit((($page - 1) * $pageSize) . ',' . $pageSize)
            ->findAll();
        $list = array_column($list, 'title');
        return $this->success('message', ['list' => $list, 'page' => $page, 'total_page' => $totalPage]);
    }
}