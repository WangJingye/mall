<?php

namespace admin\erp\service;

use admin\common\service\BaseService;
use common\extend\excel\SpreadExcel;

class ProductService extends BaseService
{
    public $productTypeList = [
        '1' => '实物商品',
        '2' => '虚拟商品',
    ];
    public $statusList = [
        '1' => '上架',
        '2' => '下架',
    ];
    public $verifyStatusList = [
        '0' => '未审核',
        '1' => '已通过',
        '2' => '已拒绝',
    ];

    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    public function getList($params, $ispage = true)
    {
        $selector = \Db::table('Product')->where(['status' => ['!=', 0]]);

        if (isset($params['product_id']) && $params['product_id'] != '') {
            $selector->where(['product_id' => $params['product_id']]);
        }
        if (isset($params['product_name']) && $params['product_name'] != '') {
            $selector->where(['product_name' => ['like', '%' . $params['product_name'] . '%']]);
        }
        if (isset($params['product_type']) && $params['product_type'] != '') {
            $selector->where(['product_type' => $params['product_type']]);
        }
        if (isset($params['product_sub_name']) && $params['product_sub_name'] != '') {
            $selector->where(['product_sub_name' => ['like', '%' . $params['product_sub_name'] . '%']]);
        }
        if (isset($params['category_id']) && $params['category_id'] != '') {
            $selector->where(['category_id' => ['in', explode(',', $params['category_id'])]]);
        }
        if (isset($params['category_name']) && $params['category_name'] != '') {
            $selector->where(['category_name' => ['like', '%' . $params['category_name'] . '%']]);
        }
        if (isset($params['brand_id']) && $params['brand_id'] != '') {
            $selector->where(['brand_id' => $params['brand_id']]);
        }
        if (isset($params['product_weight']) && $params['product_weight'] != '') {
            $selector->where(['product_weight' => $params['product_weight']]);
        }
        if (isset($params['pic']) && $params['pic'] != '') {
            $selector->where(['pic' => ['like', '%' . $params['pic'] . '%']]);
        }
        if (isset($params['media']) && $params['media'] != '') {
            $selector->where(['media' => ['like', '%' . $params['media'] . '%']]);
        }
        if (isset($params['detail']) && $params['detail'] != '') {
            $selector->where(['detail' => ['like', '%' . $params['detail'] . '%']]);
        }
        if (isset($params['freight_id']) && $params['freight_id'] != '') {
            $selector->where(['freight_id' => $params['freight_id']]);
        }
        if (isset($params['extra']) && $params['extra'] != '') {
            $selector->where(['extra' => ['like', '%' . $params['extra'] . '%']]);
        }
        if (isset($params['sort']) && $params['sort'] != '') {
            $selector->where(['sort' => $params['sort']]);
        }
        if (isset($params['status']) && $params['status'] != '') {
            $selector->where(['status' => $params['status']]);
        }
        if (isset($params['create_time']) && $params['create_time'] != '') {
            $selector->where(['create_time' => $params['create_time']]);
        }
        if (isset($params['verify_status']) && $params['verify_status'] != '') {
            $selector->where(['verify_status' => $params['verify_status']]);
        }
        if (isset($params['update_time']) && $params['update_time'] != '') {
            $selector->where(['update_time' => $params['update_time']]);
        }
        $selector->order('product_id desc');
        if ($ispage) {
            return $this->pagination($selector, $params);
        }
        return $selector->findAll();
    }

    /**
     * @param $params
     * @throws \Exception
     */
    public function export($params)
    {
        if ($params['export_type'] == 1) {
            $list = $this->getList($params, false);
        } else {
            $list = \Db::table('Product')->where(['product_id' => ['in', explode(',', $params['ids'])]])->findAll();
        }
        if (count($list) > 10000) {
            throw new \Exception('最多导出1万条数据');
        }
        if (count($list) == 0) {
            throw new \Exception('没有符合条件的数据');
        }
        $data = [];
        $brandList = $this->getDataList('Brand', 'brand_id', 'brand_name');
        foreach ($list as $v) {
            $arr = [
                $v['product_id'],
                $v['product_name'],
                $v['category_name'],
                $brandList[$v['brand_id']],
                $this->productTypeList[$v['product_type']],
                $this->statusList[$v['status']]
            ];
            $data[] = $arr;
        }
        $export = [];
        $export['table_name'] = '商品数据';
        $export['info'] = ['商品ID', '商品名称', '分类', '品牌', '类型', '状态'];
        $export['data'] = $data;
        SpreadExcel::exportExcel($export);
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function saveProduct($data)
    {
        $params = $data;
        $data['category_id'] = json_decode($data['category_id'], true);
        $extra = [
            'category' => $data['category_id'],
            'images' => $data['pic'],
            'product_params' => json_decode($data['product_params'], true),
            'rules' => json_decode($data['rules'], true),
        ];
        $categoryList = \Db::table('ProductCategory')
            ->field(['category_id', 'category_name'])->where(['category_id' => ['in', $data['category_id']]])
            ->order('level asc')
            ->findAll();
        $categoryList = array_column($categoryList, 'category_name', 'category_id');
        $categoryNames = [];
        foreach ($data['category_id'] as $v) {
            $categoryNames[] = $categoryList[$v];
        }
        $data['category_id'] = end($data['category_id']);
        $data['category_name'] = implode(',', $categoryNames);
        $data['pic'] = explode(',', $data['pic'])[0];
        $variations = json_decode($data['variations'], true);
        if (empty($variations)) {
            throw new \Exception('必须添加一个SKU');
        }
        //商品价格取第一个variation的价格
        $data['price'] = $variations[0]['price'];
        $data['extra'] = json_encode($extra);
        unset($data['product_params'], $data['rules'], $data['variations']);
        if (isset($data['product_id']) && $data['product_id']) {
            \Db::table('Product')->where(['product_id' => $data['product_id']])->update($data);
            $this->productTrace('编辑', $params);
        } else {
            $data['product_id'] = \Db::table('Product')->insert($data);
            $params['product_id'] = $data['product_id'];
            $this->productTrace('创建', $params);
        }
        $variationList = \Db::table('ProductVariation')->where(['product_id' => $data['product_id']])->findAll();
        $variationList = array_column($variationList, null, 'variation_code');
        //添加
        foreach ($variations as $key => $v) {
            //不存在SKU
            if (empty($v['variation_code'])) {
                $v['variation_code'] = $this->getVariationCode();
                $variations[$key] = $v;
                $new = [
                    'product_id' => $data['product_id'],
                    'rules_name' => $v['rules_name'],
                    'rules_value' => $v['rules_value'],
                    'variation_code' => $v['variation_code'],
                    'stock' => $v['stock'],
                    'price' => $v['price'],
                    'market_price' => $v['market_price'],
                    'status' => 1
                ];
                \Db::table('ProductVariation')->insert($new);
            } else if (isset($variationList[$v['variation_code']])) {
                $variation = $variationList[$v['variation_code']];
                $update = [
                    'rules_name' => $v['rules_name'],
                    'rules_value' => $v['rules_value'],
                    'stock' => $v['stock'],
                    'price' => $v['price'],
                    'market_price' => $v['market_price'],
                    'status' => 1
                ];
                \Db::table('ProductVariation')->where(['variation_id' => $variation['variation_id']])->update($update);
            }
        }
        $codes = array_column($variations, 'variation_code');
        foreach ($variationList as $key => $v) {
            if (!in_array($key, $codes)) {
                \Db::table('ProductVariation')->where(['variation_id' => $v['variation_id']])->update(['status' => 0]);
            }
        }
    }

    /**
     * @param $data
     * @throws \Exception
     */
    public function deleteProduct($data)
    {
        $this->productTrace('删除', $data);
        \Db::table('Product')->where($data)->update(['status' => 0]);
    }

    /**
     * @param $params
     * @param bool $ispage
     * @return ProductService|mixed
     * @throws \Exception
     */
    public function getVariationList($params, $ispage = true)
    {
        $selector = \Db::table('ProductVariation')->rename('a')
            ->join(['b' => 'Product'], 'b.product_id = a.product_id')
            ->field(['b.product_name', 'a.variation_code', 'b.product_id', 'a.variation_id',
                'a.rules_value', 'a.price', 'a.market_price', 'a.stock', 'b.status', 'a.create_time', 'b.freight_id', 'b.product_weight'])
            ->where(['a.status' => 1])
            ->where(['b.status' => ['!=', 0]]);
        if (isset($params['product_name']) && $params['product_name'] != '') {
            $selector->where(['b.product_name' => ['like', '%' . $params['product_name'] . '%']]);
        }
        if (!empty($params['product_id'])) {
            $selector->where(['a.product_id' => $params['product_id']]);
        }
        if (isset($params['product_type']) && $params['product_type'] != '') {
            $selector->where(['b.product_type' => $params['product_type']]);
        }
        $selector->order('variation_id desc');
        if ($ispage) {
            return $this->pagination($selector, $params);
        }
        return $selector->findAll();
    }

    public static function getVariationCode()
    {
        $variation = \Db::table('ProductVariation')->order('variation_id desc')->limit(1)->find();
        if (!empty($variation)) {
            $code = (int)substr($variation['variation_code'], 0, 12) + 1;
        } else {
            $code = 351500000000;   //若货品列表没东西则基于此自增生成SKU
        }
        $arr = str_split($code, 1);
        $tmp1 = $arr[11] + $arr[9] + $arr[7] + $arr[5] + $arr[3] + $arr[1];
        $tmp2 = $tmp1 * 3;
        $tmp3 = $arr[10] + $arr[8] + $arr[6] + $arr[4] + $arr[2] + $arr[0];
        $tmp4 = $tmp2 + $tmp3;
        $tmp5 = ($tmp4 % 10);
        if ($tmp5 > 0) {
            $tmp5 = 10 - $tmp5;
        }
        $code .= $tmp5;
        if (strlen($code) != 13) {
            throw new \Exception('SKU编码长度不为13位!');
        }
        return $code;
    }
}