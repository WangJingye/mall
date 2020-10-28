<form class="form-box col-12 col-sm-8 col-md-6" id="save-form"
      action="<?= \App::$urlManager->createUrl('erp/coupon/edit') ?>" method="post">
    <input type="hidden" name="coupon_id" value="<?= $this->model['coupon_id'] ?>">
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>优惠券标题</label>
        <div class="col-sm-8">
            <input type="text" name="title" class="form-control" value="<?= $this->model['title'] ?>"
                   placeholder="请输入优惠券标题">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>优惠券类型</label>
        <div class="col-sm-8">
            <?= \admin\extend\input\SelectInput::instance($this->typeList, isset($this->model['type']) ? $this->model['type'] : 1, 'type')->show(); ?>
        </div>
    </div>
    <div class="form-group row type-list type-2" <?= $this->model['type'] == 2 ? '' : 'style="display: none"' ?>>
        <label class="col-sm-4 text-nowrap col-form-label form-label">商品分类</label>
        <div class="col-sm-8">
            <?= \admin\extend\input\SelectInput::instance(array_column($this->categoryList, 'name', 'id'), $this->model['type'] == 2 ? $this->model['relation_id'] : '', 'category_id', 'select2')->show(); ?>
        </div>
    </div>
    <div class="form-group row type-list type-3" <?= $this->model['type'] == 3 ? '' : 'style="display: none"' ?>>
        <label class="col-sm-4 text-nowrap col-form-label form-label">商品</label>
        <div class="col-sm-8">
            <input type="text" readonly class="form-control search-product" placeholder="点击选择商品"
                   value="<?= $this->product['product_name'] ?>">
            <input type="hidden" name="product_id"
                   value="<?= $this->model['type'] == 3 ? $this->model['relation_id'] : '' ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>面值</label>
        <div class="col-sm-8">
            <input type="number" name="price" class="form-control" value="<?= $this->model['price'] ?>"
                   placeholder="请输入面值">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>所需积分</label>
        <div class="col-sm-8">
            <input type="number" name="points" class="form-control" value="<?= $this->model['points'] ?>"
                   placeholder="请输入所需积分">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>最小使用价格</label>
        <div class="col-sm-8">
            <input type="number" name="min_price" class="form-control" value="<?= $this->model['min_price'] ?>"
                   placeholder="请输入最小使用价格">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>有效期</label>
        <div class="col-sm-8">
            <input type="number" name="expire" class="form-control" value="<?= $this->model['expire'] ?>"
                   placeholder="请输入有效期">
            <small class="text-muted">单位 分钟</small>
        </div>
    </div>
    <div class="form-group row">
        <div class="offset-4 col-sm-8">
            <input class="btn btn-primary btn-lg" type="submit" value="保存"/>
        </div>
    </div>
</form>
<?php $this->appendScript('product-search.js')->appendScript('coupon.js') ?>