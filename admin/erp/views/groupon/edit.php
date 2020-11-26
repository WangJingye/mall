<style>
    .groupon-variation-box td {
        padding: 0.5rem;
    }

    .variation-price, .variation-stock, .variation-product-price {
        width: 5rem;
        display: inline-block;
    }

    @media (min-width: 992px) {
        .groupon-variation-box.table-responsive {
            overflow-x: inherit;
        }
    }
</style>
<form class="form-box col-12 col-sm-10 col-md-10" id="save-form"
      action="<?= \App::$urlManager->createUrl('erp/groupon/edit') ?>" method="post">
    <input type="hidden" name="id" value="<?= $this->model['id'] ?>">
    <div class="form-group row">
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>所属商品</label>
        <div class="col-sm-10">
            <input type="text" name="product_name" readonly class="form-control form-search-product"
                   placeholder="点击选择商品"
                   value="<?= $this->product['product_name'] ?>">
            <input type="hidden" name="product_id" value="<?= $this->product['product_id'] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>标题</label>
        <div class="col-sm-10">
            <input type="text" name="title" class="form-control" value="<?= $this->model['title'] ?>"
                   placeholder="请输入标题">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>开始时间</label>
        <div class="col-sm-10">
            <input type="text" name="start_time" class="form-control"
                   value="<?= $this->model['start_time'] ? date('Y-m-d H:i:s', $this->model['start_time']) : '' ?>"
                   placeholder="开始时间，格式为2019-01-01 00:00:00">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>成团人数</label>
        <div class="col-sm-10">
            <input type="number" name="group_user_number" class="form-control"
                   value="<?= $this->model['group_user_number'] ? $this->model['group_user_number'] : 1 ?>"
                   placeholder="请输入成团人数">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>结束时间</label>
        <div class="col-sm-10">
            <input type="text" name="end_time" class="form-control"
                   value="<?= $this->model['end_time'] ? date('Y-m-d H:i:s', $this->model['end_time']) : '' ?>"
                   placeholder="结束时间，格式为2019-01-01 00:00:00">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>显示到首页</label>
        <div class="col-sm-10">
            <?= \admin\extend\input\SelectInput::instance($this->boolList, $this->model['show_home'], 'show_home', 'radio')->show(); ?>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>排序</label>
        <div class="col-sm-10">
            <input type="number" name="sort" class="form-control"
                   value="<?= $this->model['sort'] ? $this->model['sort'] : '0' ?>" placeholder="请输入排序">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>sku</label>
        <div class="col-sm-10">
            <div>
                <div class="btn btn-success add-sku-btn"><i
                            class="glyphicon glyphicon-plus"></i> 添加sku
                </div>
            </div>
            <div class="groupon-variation-box table-responsive" style="margin-top: 0.5rem">
                <table class="table table-bordered text-nowrap text-center">
                    <tbody>
                    <tr>
                        <td>SKU</td>
                        <td>规格</td>
                        <td>单独成团价</td>
                        <td>价格</td>
                        <td>库存</td>
                        <td>操作</td>
                    </tr>
                    <?php foreach ($this->variationList as $v): ?>
                        <tr class="data-tr">
                            <td>
                                <input type="hidden" class="variation-code" value="<?= $v['variation_code'] ?>">
                                <?= $v['variation_code'] ?>
                            </td>
                            <td><?= $v['rules_value'] !== '' ? $v['rules_value'] : '<i style="color: #666">无规格</i>' ?></td>
                            <td><input type="number" class="form-control variation-product-price"
                                       value="<?= $v['product_price'] ?>">
                            <td><input type="number" class="form-control variation-price" value="<?= $v['price'] ?>">
                            </td>
                            <td><input type="number" class="form-control variation-stock" value="<?= $v['stock'] ?>">
                            </td>
                            <td>
                                <div class="btn btn-sm btn-danger remove-variation-btn">
                                    <i class="glyphicon glyphicon-remove"></i>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="last-tr sr-only" style="display: none"></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="offset-2 col-sm-10">
            <input class="btn btn-primary btn-lg" type="submit" value="保存"/>
        </div>
    </div>
</form>
<?php $this->appendScript('product-search.js')->appendScript('groupon.js') ?>