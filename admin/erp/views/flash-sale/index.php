<div class="btn-box clearfix">
    <a href="<?= \App::$urlManager->createUrl('erp/flash-sale/edit') ?>">
        <div class="btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i> 创建</div>
    </a>
</div>
<form class="search-form" action="<?= \App::$urlManager->createUrl('erp/flash-sale/index') ?>" method="get">
    <div class="form-content">
        <span class="col-form-label search-label">标题</span>
        <input type="text" class="form-control search-input" name="title" value="<?= $this->params['title'] ?>">
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">所属商品</span>
        <input type="hidden" name="product_id" value="<?= $this->params['product_id']?>">
        <input type="text" class="form-control search-input search-product" readonly value="<?= $this->product['product_name']?>" placeholder="点击选择商品">
        <?php if ($this->params['product_id']): ?>
            <span class="search-clear-btn"><i class="glyphicon glyphicon-remove-circle"></i></span>
        <?php endif; ?>
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">sku</span>
        <input type="text" class="form-control search-input" name="variation_code" value="<?= $this->params['variation_code'] ?>">
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">状态</span>
        <select class="form-control search-input" name="status">
            <option value="">请选择</option>
            <?php foreach ($this->statusList as $k => $v): ?>
                <option value="<?= $k ?>" <?= $this->params['status'] == (string)$k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php $searchList = ['flash_id' => 'ID']; ?>
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
            <div class="btn btn-primary search-btn text-nowrap"><i class="glyphicon glyphicon-search"></i> 搜索</div>
        </div>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-bordered list-table text-center text-nowrap">
        <tbody>
        <tr>
            <th>ID</th>
            <th>标题</th>
            <th>商品</th>
            <th>sku</th>
            <th>秒杀价</th>
            <th>原价</th>
            <th>库存</th>
            <th>开始时间</th>
            <th>结束时间</th>
            <th>状态</th>
            <th>创建人</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        <?php foreach ($this->list as $v): ?>
            <tr>
                <td><?= $v['flash_id'] ?></td>
                <td><?= $v['title'] ?></td>
                <td><?= $this->productList[$v['product_id']] ?></td>
                <td><?= $v['variation_code'] ?></td>
                <td><?= $v['price'] ?></td>
                <td><?= $v['product_price'] ?></td>
                <td><?= $v['stock'] ?></td>
                <td><?= date('Y-m-d H:i:s', $v['start_time']) ?></td>
                <td><?= date('Y-m-d H:i:s', $v['end_time']) ?></td>
                <td><?= $this->statusList[$v['status']] ?></td>
                <td><?= $this->userList[$v['create_userid']] ?></td>
                <td><?= date('Y-m-d H:i:s', $v['create_time']) ?></td>
                <td>
                    <a class="btn btn-primary btn-sm"
                       href="<?= \App::$urlManager->createUrl('erp/flash-sale/edit', ['flash_id' => $v['flash_id']]) ?>">
                        <i class="glyphicon glyphicon-pencil"></i> 编辑
                    </a>
                    <div class="btn btn-danger btn-sm remove-btn" data-id="<?= $v['flash_id'] ?>">
                        <i class="glyphicon glyphicon-trash"></i> 删除
                    </div>
                </td>
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
<?php $this->appendScript('product-search.js')->appendScript('flash-sale.js') ?>