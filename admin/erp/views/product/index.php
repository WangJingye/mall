<div class="btn-box clearfix">
    <a href="<?= \App::$urlManager->createUrl('erp/product/edit') ?>">
        <div class="btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i> 创建</div>
    </a>
</div>
<form class="search-form" action="<?= \App::$urlManager->createUrl('erp/product/index') ?>" method="get">
    <div class="form-content">
        <span class="col-form-label search-label">商品名称</span>
        <input type="text" class="form-control search-input" name="product_name"
               value="<?= $this->params['product_name'] ?>">
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">商品类型</span>
        <select class="form-control search-input" name="product_type">
            <option value="">请选择</option>
            <?php foreach ($this->productTypeList as $k => $v): ?>
                <option value="<?= $k ?>" <?= $this->params['product_type'] == (string)$k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">副标题</span>
        <input type="text" class="form-control search-input" name="product_sub_name"
               value="<?= $this->params['product_sub_name'] ?>">
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">商品分类</span>
        <input type="text" class="select-ztree form-control  search-input" name="category_id"
               data-data_key="selectZtreeData" data-step="last">
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">品牌</span>
        <select class="form-control search-input select2" name="brand_id">
            <option value="">请选择</option>
            <?php foreach ($this->brandList as $k => $v): ?>
                <option value="<?= $k ?>" <?= $this->params['brand_id'] == (string)$k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
        </select>
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
            <th>商品ID</th>
            <th>商品名称</th>
            <th>商品SPU</th>
            <th>分类名称</th>
            <th>品牌</th>
            <th>主图</th>
            <th>排序</th>
            <th>状态</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        <?php foreach ($this->list as $v): ?>
            <tr>
                <td><input type="checkbox" class="check-one" value="<?= $v['product_id'] ?>"></td>
                <td><?= $v['product_id'] ?></td>
                <td><?= $v['product_name'] ?></td>
                <td><?= $v['product_code'] ?></td>
                <td><?= $v['category_name'] ?></td>
                <td><?= $this->brandList[$v['brand_id']] ?></td>
                <td>
                    <?php if ($v['pic']): ?>
                        <img src="<?= $v['pic'] ?>" style="width: 60px;height: 60px;">
                    <?php endif; ?>
                </td>
                <td class="sort"><?= $v['sort'] ?></td>
                <td class="status"><?= $this->statusList[$v['status']] ?></td>
                <td><?= date('Y-m-d H:i:s', $v['create_time']) ?></td>
                <td>
                    <div>

                        <a class="btn btn-primary btn-sm"
                           href="<?= \App::$urlManager->createUrl('erp/product/edit', ['product_id' => $v['product_id']]) ?>">
                            <i class="glyphicon glyphicon-pencil"></i> 编辑
                        </a>
                        <div class="btn btn-danger btn-sm remove-btn" data-product_id="<?= $v['product_id'] ?>">
                            <i class="glyphicon glyphicon-trash"></i> 删除
                        </div>
                        <?php if ($v['status'] == 1): ?>
                            <div class="btn btn-danger btn-sm set-status-btn" data-id="<?= $v['product_id'] ?>"
                                 data-url="<?= \App::$urlManager->createUrl('erp/product/set-status') ?>"
                                 data-status="2">
                                <i class="glyphicon glyphicon-remove-circle"></i> <span>下架</span>
                            </div>
                        <?php else: ?>
                            <div class="btn btn-success btn-sm set-status-btn" data-id="<?= $v['product_id'] ?>"
                                 data-url="<?= \App::$urlManager->createUrl('erp/product/set-status') ?>"
                                 data-status="1">
                                <i class="glyphicon glyphicon-ok-circle"></i> <span>上架</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div style="margin-top: 0.5rem">
                        <a class="btn btn-warning btn-sm"
                           href="<?= \App::$urlManager->createUrl('erp/groupon/edit', ['product_id' => $v['product_id']]) ?>">
                            <i class="glyphicon glyphicon-star"></i> 设置团购
                        </a>
                        <a class="btn btn-danger btn-sm"
                           href="<?= \App::$urlManager->createUrl('erp/flash-sale/edit', ['product_id' => $v['product_id']]) ?>">
                            <i class="glyphicon glyphicon glyphicon-fire"></i> 设置秒杀
                        </a>
                    </div>
                    <div style="margin-top: 0.5rem">
                        <div class="btn btn-info btn-sm set-sort-btn" data-id="<?= $v['product_id'] ?>">
                            <i class="glyphicon glyphicon-sort"></i> 设置排序
                        </div>
                        <div class="btn btn-outline-info btn-sm view-operation-btn" data-id="<?= $v['product_id'] ?>"
                             data-url="<?= \App::$urlManager->createUrl('erp/product/show-log') ?>">
                            <i class="glyphicon glyphicon-eye-open"></i> 操作日志
                        </div>
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
<script>
    var categoryTrees = <?= json_encode($this->categoryTrees)?>;
    var selectZtreeData = {
        'selectZtreeData': categoryTrees,
    };
</script>
<?php $this->appendScript('product.js') ?>