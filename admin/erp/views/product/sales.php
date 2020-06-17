<form class="search-form" action="<?= \App::$urlManager->createUrl('erp/product/sales') ?>" method="get">
    <div class="form-content">
        <span class="col-form-label search-label">商品名称</span>
        <input type="text" class="form-control search-input" name="product_name" value="<?= $this->params['product_name'] ?>">
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">已售数量</span>
        <select class="form-control search-input" name="sales_number">
            <option value="1" <?= $this->params['sales_number'] == 1 ? 'selected' : '' ?>>大于0</option>
            <option value="2" <?= $this->params['sales_number'] == 2 ? 'selected' : '' ?>>所有</option>
        </select>
    </div>
    <?php $searchList = ['product_id' => '商品ID', 'product_code' => '商品SPU']; ?>
    <div class="form-content">
        <span class="col-form-label search-label">查询条件</span>
        <div class="clearfix" style="display: inline-flex;">
            <select class="form-control search-type" name="search_type">
                <option value="">请选择</option>
                <?php foreach ($searchList as $k => $v): ?>
                    <option value="<?= $k ?>" <?= $this->params['search_type'] == $k ? 'selected' : '' ?>><?= $v ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" class="form-control search-value" name="search_value" placeholder="关键词"
                   value="<?= $this->params['search_value'] ?>">
            <div class="btn btn-primary search-with-export-btn text-nowrap"><i class="glyphicon glyphicon-search"></i>
                搜索
            </div>
            <div class="btn btn-success export-btn text-nowrap"><i class="glyphicon glyphicon-export"></i> 导出</div>
        </div>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-bordered list-table text-nowrap text-center">
        <tbody>
        <tr>
            <th><input type="checkbox" class="check-all"></th>
            <th>所属会员</th>
            <th>商品名称</th>
            <th>商品SPU</th>
            <th>规格</th>
            <th>价格</th>
            <th>已售数量</th>
            <th>已售金额</th>
        </tr>
        <?php foreach ($this->list as $v): ?>
            <tr>
                <td><input type="checkbox" class="check-one" value="<?= $v['variation_id'] ?>"></td>
                <td><?= $this->userList[$v['user_id']] ?></td>
                <td><?= $v['product_name'] ?></td>
                <td><?= $v['product_code'] ?></td>
                <td><?= $v['rules_value']?></td>
                <td><?= $v['price']?></td>
                <td><?= $v['sale_number'] ?></td>
                <td><?= $v['sale_amount'] ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (!count($this->list)): ?>
            <tr>
                <td colspan="18" class="list-table-nodata">暂无相关数据</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->pagination ?>
<?php $this->appendScript('product.js') ?>