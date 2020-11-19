<?php

namespace admin\erp\controller;

use admin\common\controller\BaseController;
use admin\erp\service\CategoryService;
use admin\erp\service\ProductService;

class ProductController extends BaseController
{
    /** @var ProductService */
    public $productService;
    /** @var CategoryService */
    public $categoryService;

    public $belongList = [
        '0' => '平台自营',
        '1' => '企业用户',
    ];

    public function init()
    {
        $this->productService = new ProductService();
        $this->categoryService = new CategoryService();
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
        if (!empty($params['export_type'])) {
            $this->productService->export($params);
        }
        $params['verify_status'] = ['!=', 0];
        /** @var ProductService $res */
        $res = $this->productService->getList($params);
        $this->assign('params', $params);
        $this->assign('pagination', $this->pagination($res));
        $this->assign('list', $res->list);
        $this->assign('productTypeList', $this->productService->productTypeList);
        $categoryList = $this->productService->getDataList('ProductCategory', 'category_id', 'category_name');
        $this->assign('categoryList', $categoryList);
        $categoryTrees = $this->productService->getTreeList('ProductCategory', $params['category_id'], 'category_id', 'category_name');
        $this->assign('categoryTrees', $categoryTrees);
        $brandList = $this->productService->getDataList('Brand', 'brand_id', 'brand_name');
        $this->assign('brandList', $brandList);
        $this->assign('belongList', $this->belongList);
        $freightList = $this->productService->getDataList('FreightTemplate', 'freight_id', 'template_name');
        $this->assign('freightList', $freightList);
        $this->assign('statusList', $this->productService->statusList);
    }

    /**
     * @throws \Exception
     */
    public function editAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                $params['pic'] = $this->parseFileOrUrl('pic', 'erp/product');
                $params['images'] = $this->parseFileOrUrl('images', 'erp/product');
                if (empty($params['pic'])) {
                    throw new \Exception('请选择商品图片');
                }
                $params['media'] = $this->parseFileOrUrl('media', 'erp/product');
                $params['v_pic'] = $this->parseFileOrUrl('v_pic', 'erp/product','array');
                \Db::startTrans();
                $this->productService->saveProduct($params);
                \Db::commit();
                return $this->success('保存成功');
            } catch (\Exception $e) {
                \Db::rollback();
                return $this->error($e->getMessage());
            }
        }
        $this->title = '创建商品';
        if (isset($params['product_id']) && $params['product_id']) {
            $model = \Db::table('Product')->where(['product_id' => $params['product_id']])->find();
            if (!$model) {
                throw new \Exception('数据不存在');
            }
            $model['extra'] = json_decode($model['extra'], true);
            $variationList = \Db::table('ProductVariation')
                ->where(['product_id' => $params['product_id']])
                ->where(['status' => 1])
                ->findAll();
            $this->assign('model', $model);
            $this->assign('variationList', $variationList);
            $this->title = '编辑商品 - ' . $model['product_id'];
        }
        $this->assign('productTypeList', $this->productService->productTypeList);
        $categories = \Db::table('ProductCategory')->field(['category_id', 'category_name', 'parent_id'])->findAll();
        $categoryList = [];
        foreach ($categories as $v) {
            $categoryList[$v['parent_id']][$v['category_id']] = $v['category_name'];
        }
        $this->assign('categoryList', $categoryList);
        $brandList = $this->productService->getDataList('Brand', 'brand_id', 'brand_name');
        $this->assign('brandList', $brandList);
        $this->assign('belongList', $this->belongList);
        $freightList = $this->productService->getDataList('FreightTemplate', 'freight_id', 'template_name');
        $this->assign('freightList', $freightList);
        $this->assign('statusList', $this->productService->statusList);
    }

    /**
     * @throws \Exception
     */
    public function deleteAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                if (!isset($params['product_id']) || $params['product_id'] == '') {
                    throw new \Exception('非法请求');
                }
                \Db::startTrans();
                $this->productService->deleteProduct($params);
                \Db::commit();
                return $this->success('删除成功');
            } catch (\Exception $e) {
                \Db::rollback();
                return $this->error($e->getMessage());
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function setStatusAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                if (!isset($params['product_id']) || $params['product_id'] == '') {
                    throw new \Exception('非法请求');
                }
                \Db::startTrans();
                $this->productService->productTrace($params['status'] == 1 ? '上架' : '下架', $params);
                \Db::table('Product')->where(['product_id' => $params['product_id']])->update(['status' => $params['status']]);
                \Db::commit();
                return $this->success($params['status'] == 1 ? '已上架' : '已下架');
            } catch (\Exception $e) {
                \Db::rollback();
                return $this->error($e->getMessage());
            }
        }
    }

    public function showLogAction()
    {
        $params = \App::$request->params->toArray();
        if (\App::$request->isAjax() && \App::$request->isPost()) {
            try {
                $list = \Db::table('ProductTrace')->field(['id', 'product_id', 'create_time', 'detail', 'create_userid', 'user_type'])
                    ->where(['product_id' => $params['product_id']])
                    ->order('id desc')->findAll();
                $userList = $this->productService->getOperateUserList($list);
                $this->assign('userList', $userList);
                $this->assign('id', $params['product_id']);
                $this->assign('list', $list);
                $html = $this->renderPartial('erp/product/_info');
                return $this->success('success', ['html' => $html]);
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }

    /**
     * 商品查询
     * @throws \Exception
     */
    public function searchAction()
    {
        $params = \App::$request->params;
        $params['page'] = \App::$request->getParams('page', 1);
        $params['pageSize'] = \App::$request->getParams('pageSize', 5);
        if (!empty($params['search_type'])) {
            $params[$params['search_type']] = $params['search_value'];
        }
        /** @var ProductService $res */
        $res = $this->productService->getList($params);
        $this->assign('pagination', $this->paginationJs($res));
        $this->assign('list', $res->list);
        $this->assign('params', $params);
        $this->assign('statusList', $this->productService->statusList);
        $this->assign('productTypeList', $this->productService->productTypeList);
        $categoryList = $this->productService->getDataList('ProductCategory', 'category_id', 'category_name');
        $this->assign('categoryList', $categoryList);
        $brandList = $this->productService->getDataList('Brand', 'brand_id', 'brand_name');
        $this->assign('brandList', $brandList);
        $this->assign('belongList', $this->belongList);
        $html = $this->renderPartial('erp/product/_search');
        return $this->success('success', ['html' => $html]);
    }

    /**
     * 商品Variation查询
     * @throws \Exception
     */
    public function variationSearchAction()
    {
        $params = \App::$request->params;
        $params['page'] = \App::$request->getParams('page', 1);
        $params['pageSize'] = \App::$request->getParams('pageSize', 5);
        if (!empty($params['search_type'])) {
            $params[$params['search_type']] = $params['search_value'];
        }
        /** @var ProductService $res */
        $res = $this->productService->getVariationList($params);
        $list = $res->list;
        $this->assign('pagination', $this->paginationJs($res));
        $this->assign('list', $list);
        $this->assign('params', $params);
        $this->assign('statusList', $this->productService->statusList);
        $categoryList = $this->productService->getDataList('ProductCategory', 'category_id', 'category_name');
        $this->assign('categoryList', $categoryList);
        $brandList = $this->productService->getDataList('Brand', 'brand_id', 'brand_name');
        $this->assign('brandList', $brandList);
        $html = $this->renderPartial('erp/product/_variation_search');
        return $this->success('success', ['html' => $html]);
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
            \Db::table('Product')->where(['product_id' => $params['id']])->update(['sort' => $params['sort']]);
            return $this->success('设置成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function generateSpuAction(){
        return $this->productService->generateCode();
    }
}