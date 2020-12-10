<?php

namespace admin\erp\controller;

use admin\common\controller\BaseController;
use admin\erp\service\CarouselService;

class CarouselController extends BaseController
{
    /** @var CarouselService */
    public $carouselService;

    public $carouselTypeList = [
        '1' => '首页轮播',
    ];
    public $linkTypeList = [
        '0' => '无跳转',
        '1' => '商品详情页',
        '2' => '搜索结果页',
    ];
    public $isShowList = [
        '0' => '否',
        '1' => '是',
    ];

    public function init()
    {
        $this->carouselService = new CarouselService();
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
        /** @var CarouselService $res */
        $res = $this->carouselService->getList($params);
        $this->assign('params', $params);
        $this->assign('pagination', $this->pagination($res));
        $this->assign('list', $res->list);
        $this->assign('carouselTypeList', $this->carouselTypeList);
        $this->assign('linkTypeList', $this->linkTypeList);
        $this->assign('isShowList', $this->isShowList);
    }

    /**
     * @throws \Exception
     */
    public function editAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                $params['pic'] = $this->parseFileOrUrl('pic', 'erp/carousel');
                $this->carouselService->saveCarousel($params);
                return $this->success('保存成功');
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
        $this->title = '创建轮播';
        if (isset($params['carousel_id']) && $params['carousel_id']) {
            $model = \Db::table('Carousel')->where(['carousel_id' => $params['carousel_id']])->find();
            if (!$model) {
                throw new \Exception('数据不存在');
            }
            $this->assign('model', $model);

            if ($model['link_type'] == 1) {
                $product = \Db::table('Product')->where(['product_id' => $model['link']])->find();
                $this->assign('product', $product);
            }
            $this->title = '编辑轮播 - ' . $model['carousel_id'];
        }
        $this->assign('carouselTypeList', $this->carouselTypeList);
        $this->assign('linkTypeList', $this->linkTypeList);
        $this->assign('isShowList', $this->isShowList);
    }

    /**
     * @throws \Exception
     */
    public function deleteAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                if (!isset($params['carousel_id']) || $params['carousel_id'] == '') {
                    throw new \Exception('非法请求');
                }
                $this->carouselService->deleteCarousel($params);
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
            \Db::table('Carousel')->where(['carousel_id' => $params['id']])->update(['is_show' => $params['is_show']]);
            return $this->success('设置成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function setSortAction()
    {
        try {
            $params = \App::$request->params->toArray();
            if (empty($params['id']) || empty($params['sort'])) {
                throw new \Exception('非法请求');
            }
            \Db::table('Carousel')->where(['carousel_id' => $params['id']])->update(['sort' => $params['sort']]);
            return $this->success('设置成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}