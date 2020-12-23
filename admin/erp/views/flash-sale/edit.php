<form class="form-box col-12  col-sm-10 col-md-10" id="save-form"
      action="<?= \App::$urlManager->createUrl('erp/flash-sale/edit') ?>" method="post">
    <input type="hidden" name="flash_id" value="<?= $this->model['flash_id'] ?>">
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
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>sku</label>
        <div class="col-sm-10">
            <input type="text" name="variation_code" readonly class="form-control form-search-product-variation"
                   placeholder="点击选择sku"
                   value="<?= $this->model['variation_code'] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>原价</label>
        <div class="col-sm-10">
            <input type="number" name="product_price" class="form-control" placeholder="原价"
                   value="<?= $this->model['product_price'] ?>">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>秒杀价</label>
        <div class="col-sm-10">
            <input type="number" name="price" class="form-control" value="<?= $this->model['price'] ?>"
                   placeholder="请输入秒杀价">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>库存</label>
        <div class="col-sm-10">
            <input type="number" name="stock" class="form-control" value="<?= $this->model['stock'] ?>"
                   placeholder="请输入库存">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>秒杀类型</label>
        <div class="col-sm-10">
            <?= \admin\extend\input\SelectInput::instance($this->typeList, $this->model['type'] ?? 1, 'type', 'radio')->show() ?>
        </div>
    </div>
    <div class="form-group row  show-type show-type1" <?= $this->model['type'] != 2 ? '' : 'style="display:none"' ?>>
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>开始时间</label>
        <div class="col-sm-10">
            <input type="text" name="start_time" class="form-control"
                   value="<?= $this->model['start_time'] ? date('Y-m-d H:i:s', $this->model['start_time']) : '' ?>"
                   placeholder="开始时间，格式为2019-01-01 00:00:00">
        </div>
    </div>
    <div class="form-group row  show-type show-type1" <?= $this->model['type'] != 2 ? '' : 'style="display:none"' ?>>
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>结束时间</label>
        <div class="col-sm-10">
            <input type="text" name="end_time" class="form-control"
                   value="<?= $this->model['end_time'] ? date('Y-m-d H:i:s', $this->model['end_time']) : '' ?>"
                   placeholder="结束时间，格式为2019-01-01 00:00:00">
        </div>
    </div>
    <div class="form-group row show-type show-type2" <?= $this->model['type'] == 2 ? '' : 'style="display:none"' ?>>
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>日期</label>
        <div class="col-sm-10">
            <input type="text" name="date" class="form-control"
                   value="<?= $this->model['date'] ? $this->model['date'] : date('Y-m-d', time() + 24 * 3600) ?>"
                   placeholder="秒杀日期，格式为2019-01-01">
        </div>
    </div>
    <div class="form-group row  show-type show-type2" <?= $this->model['type'] == 2 ? '' : 'style="display:none"' ?>>
        <label class="col-sm-2 text-nowrap col-form-label form-label"><span style="color: red">*</span>场次</label>
        <div class="col-sm-10">
            <select name="show_id" class="form-control">
                <option value="">请选择</option>
                <?php foreach ($this->showList as $v): ?>
                    <option value="<?= $v['show_id'] ?>" <?= $this->model['show_id'] == $v['show_id'] ? 'selected' : '' ?>><?= $v['start_time'] . ' ~ ' . $v['end_time'] ?></option>
                <?php endforeach; ?>
            </select>
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
        <div class="offset-2 col-sm-10">
            <input class="btn btn-primary btn-lg" type="submit" value="保存"/>
        </div>
    </div>
</form>
<?php $this->appendScript('product-search.js')->appendScript('flash-sale.js') ?>