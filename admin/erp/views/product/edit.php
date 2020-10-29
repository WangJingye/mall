<style>
    .category-select {
        width: 8rem;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
        display: inline;
    }

    .add-rule-btn, .remove-rule-btn {
        margin-left: 0.4rem;
        cursor: pointer;
        display: inline-block;
        width: auto;
        height: 1.5rem;
        min-width: 16px;
        padding: 0 0.4rem 0.4rem 0.4rem;
        font-size: 14px;
        font-weight: normal;
        line-height: 1.4rem;
        text-align: center;
        text-shadow: 0 1px 0 #ffffff;
        background-color: #eeeeee;
        border: 1px solid #ccc;
    }

    .variation-name, .variation-value {
        width: 6.5rem;
        display: inline;
        margin-top: 0.5rem;
    }

    .variation-name {
        margin-right: 0.2rem;
    }

    .product_variation {
        width: 10.5rem;
    }

    .stock, .market_price, .price {
        width: 5.5rem;
    }
</style>
<form class="form-box col-12 col-sm-10 col-md-10 table-responsive" id="save-form"
      action="<?= \App::$urlManager->createUrl('erp/product/edit') ?>" method="post">
    <input type="hidden" name="product_id" value="<?= $this->model['product_id'] ?>">
    <div class="form-group row">
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>商品类型</label>
        <div class="col-sm-10">
            <?= \admin\extend\input\SelectInput::instance($this->productTypeList, isset($this->model['product_type']) ? $this->model['product_type'] : '1', 'product_type', 'radio')->show(); ?>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>商品名称</label>
        <div class="col-sm-10">
            <input type="text" name="product_name" class="form-control" value="<?= $this->model['product_name'] ?>"
                   placeholder="请输入商品名称">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 text-nowrap col-form-label form-label">副标题</label>
        <div class="col-sm-10">
            <input type="text" name="product_sub_name" class="form-control"
                   value="<?= $this->model['product_sub_name'] ?>" placeholder="请输入副标题">
        </div>
    </div>
    <div class="form-group row product-category-select-group">
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>商品分类</label>
        <div class="col-sm-8" style="display: inline-flex">
            <?php $selectedCategoryList = isset($this->model['extra']['category']) ? $this->model['extra']['category'] : []; ?>
            <?php $listCategory = array_slice($selectedCategoryList, 0, count($selectedCategoryList) - 1);
            array_unshift($listCategory, 0); ?>
            <?php foreach ($listCategory as $i => $pId): ?>
                <?php if (isset($this->categoryList[$pId])): ?>
                    <select ] class="form-control select2 category-select">
                        <option value="">请选择</option>
                        <?php foreach ($this->categoryList[$pId] as $key => $v): ?>
                            <option value="<?= $key ?>" <?= in_array($key, $selectedCategoryList) ? 'selected' : '' ?>><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            <?php endforeach; ?>
            <span class="sr-only"></span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>品牌</label>
        <div class="col-sm-10">
            <?= \admin\extend\input\SelectInput::instance($this->brandList, $this->model['brand_id'], 'brand_id', 'select2')->show(); ?>
        </div>
    </div>
    <div class="form-group row product_weight_group">
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>商品重量</label>
        <div class="col-sm-10">
            <input type="text" name="product_weight" class="form-control" value="<?= $this->model['product_weight'] ?>"
                   placeholder="请输入商品重量">
            <small class="text-muted">单位克（g）</small>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 text-nowrap col-form-label form-label">商品参数</label>
        <div class="col-sm-8 params-content">
            <div>
                <div class="btn btn-success add-product-params-btn"><i class="glyphicon glyphicon-plus"></i> 添加参数</div>
            </div>
            <div class="product-params-box" style="margin-top: 0.5rem">
                <small class="text-muted">参数名和参数内容都设置时该条记录才有效</small>
                <table class="table table-bordered text-nowrap">
                    <tr>
                        <td>参数名称</td>
                        <td>参数内容</td>
                        <td>操作</td>
                    </tr>
                    <?php $productParams = isset($this->model['extra']['product_params']) ? $this->model['extra']['product_params'] : [];
                    foreach ($productParams as $v): ?>
                        <tr class="params-data">
                            <td><input type="text" class="form-control params-name" value="<?= $v['name'] ?>"
                                       placeholder="输入名称"></td>
                            <td><input type="text" class="form-control params-value" value="<?= $v['value'] ?>"
                                       placeholder="输入内容"></td>
                            <td>
                                <div class="btn btn-outline-danger btn-sm params-remove-btn">删除</div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="last-tr sr-only" style="display: none"></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>商品规格</label>
        <div class="col-sm-10">
            <div>
                <small class="text-muted">最多添加3个相同的规格名，区分大小写</small>
                <div class="variation-custom" data-max="3">
                    <?php $productRules = isset($this->model['extra']['rules']) ? $this->model['extra']['rules'] : []; ?>
                    <?php foreach ($productRules as $vs): ?>
                        <?php foreach ($vs['value'] as $v): ?>
                            <div class="variation-box">
                                <input type="text" class="form-control variation-name" value="<?= $vs['name'] ?>"
                                       placeholder="规格名"><input type="text" class="form-control variation-value"
                                                                oninput="checkRules(this)" value="<?= $v ?>"
                                                                placeholder="规格值"><span
                                        class="add-rule-btn">+</span><span class="remove-rule-btn">-</span>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                    <?php if (empty($productRules)): ?>
                        <div class="variation-box">
                            <input type="text" class="form-control variation-name" placeholder="规格名"><input type="text"
                                                                                                            class="form-control variation-value"
                                                                                                            oninput="checkRules(this)"
                                                                                                            placeholder="规格值"><span
                                    class="add-rule-btn">+</span><span class="remove-rule-btn">-</span>
                        </div>
                    <?php endif; ?>
                </div>
                <div style="margin-top: 0.5rem">
                    <small class="text-muted">单条SKU记录的输入框内容都设置时，该记录才能有效保存</small>
                    <table class="table table-bordered text-nowrap product_variation_list">
                        <tr>
                            <td>规格</td>
                            <td>SKU编码</td>
                            <td>销售价</td>
                            <td>划线价</td>
                            <td>库存</td>
                        </tr>
                        <?php $noSkuProduct = empty($productRules) ? $this->variationList[0] : [] ?>
                        <tr class="empty-rule-sku" <?= empty($productRules) ? '' : 'style="display:none"' ?>>
                            <td><i style="color: #666">无规格</i></td>
                            <td><?= isset($noSkuProduct['variation_code']) ? $noSkuProduct['variation_code'] : '' ?></td>
                            <td><input type="number" class="form-control price"
                                       value="<?= isset($noSkuProduct['price']) ? $noSkuProduct['price'] : '' ?>"
                                       placeholder="销售价"></td>
                            <td><input type="number" class="form-control market_price"
                                       value="<?= isset($noSkuProduct['market_price']) ? $noSkuProduct['market_price'] : '' ?>"
                                       placeholder="划线价"></td>
                            <td><input type="number" class="form-control stock"
                                       value="<?= isset($noSkuProduct['stock']) ? $noSkuProduct['stock'] : '' ?>"
                                       placeholder="stock"></td>
                        </tr>
                        <?php if (!empty($productRules)): ?>
                            <?php foreach ($this->variationList as $v): ?>
                                <tr class="has-rule-sku" data-key="<?= $v['rules_name'] ?>">
                                    <td><input type="hidden" class="rules-name" value="<?= $v['rules_name'] ?>">
                                        <input type="hidden" class="rules-value" value="<?= $v['rules_value'] ?>">
                                        <?= $v['rules_value'] ?>
                                    </td>
                                    <td><input type="hidden" name="variation_code" value="<?= $v['variation_code'] ?>">
                                        <?= $v['variation_code'] ?></td>
                                    <td><input type="number" class="form-control price" value="<?= $v['price'] ?>"
                                               placeholder="销售价"></td>
                                    <td><input type="number" class="form-control market_price"
                                               value="<?= $v['market_price'] ?>" placeholder="划线价"></td>
                                    <td><input type="number" class="form-control stock" value="<?= $v['stock'] ?>"
                                               placeholder="stock"></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>商品图片</label>
        <div class="col-sm-10">
            <div>
                <small class="text-muted">注：第一张图为商品主图，最多添加6张，单张图片不能超过150k</small>
            </div>
            <?= \admin\extend\image\ImageInput::instance($this->model['extra']['images'], 'pic', 6)->show(); ?>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 text-nowrap col-form-label form-label">视频</label>
        <div class="col-sm-10">
            <div>
                <small class="text-muted">注意：视频格式为MP4格式，视频时长不超过过60秒；</small>
            </div>
            <?= \admin\extend\image\ImageInput::instance($this->model['media'], 'media', 1, 'video')->show(); ?>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 text-nowrap col-form-label form-label">商品详情</label>
        <div class="col-sm-10">
            <textarea name="detail" class="form-control kindeditor"
                      placeholder="请输入商品详情"><?= $this->model['detail'] ?></textarea>
        </div>
    </div>
    <div class="form-group row freight_group">
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>运费模版</label>
        <div class="col-sm-10">
            <?= \admin\extend\input\SelectInput::instance($this->freightList, $this->model['freight_id'], 'freight_id', 'select')->show(); ?>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>排序</label>
        <div class="col-sm-10">
            <input type="number" name="sort" class="form-control"
                   value="<?= $this->model['sort'] ? $this->model['sort'] : 0 ?>" placeholder="请输入排序">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>商品状态</label>
        <div class="col-sm-10">
            <?= \admin\extend\input\SelectInput::instance($this->statusList, isset($this->model['status']) ? $this->model['status'] : 1, 'status', 'radio')->show(); ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="offset-4 col-sm-8">
            <input class="btn btn-primary btn-lg" type="submit" value="保存"/>
        </div>
    </div>
</form>
<script>
    categoryList = <?=json_encode($this->categoryList)?>;
</script>
<?php $this->appendScript('user-search.js')->appendScript('product.js') ?>