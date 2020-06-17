<div class="modal-event-body">
    <div class="table-responsive">
        <div class="search-form">
            <input type="hidden" name="page" value="<?= $this->params['page'] ?>">
            <input type="hidden" name="multiple" value="<?= $this->params['multiple'] ?>">
            <div class="form-content">
                <span class="col-form-label search-label">商品名称</span>
                <input type="text" class="form-control search-input" name="product_name"
                       value="<?= $this->params['product_name'] ?>">
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
            <?php $searchList = ['product_id' => '商品ID', 'product_code' => '商品SPU', 'variation_code' => '商品SKU']; ?>
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
                    <div class="btn btn-primary product-variation-search-btn search-btn text-nowrap"><i
                                class="glyphicon glyphicon-search"></i> 搜索
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-bordered list-table text-nowrap text-center">
            <tbody>
            <tr>
                <th><?php if ($this->params['multiple']): ?>
                        <input type="checkbox" class="check-all">
                    <?php endif; ?>
                </th>
                <th>商品名称</th>
                <th>商品SPU</th>
                <th>规格</th>
                <th>销售价格</th>
                <th>库存</th>
                <th>状态</th>
                <th>创建时间</th>
            </tr>

            <?php foreach ($this->list as $v): ?>
                <tr class="product-search-tr" style="cursor: pointer">
                    <td><input type="checkbox" class="check-one" name="ids" value="<?= $v['variation_id'] ?>"
                               data-info='<?= json_encode($v, JSON_HEX_APOS) ?>'></td>
                    <td><?= $v['product_name'] ?></td>
                    <td><?= $v['product_code'] ?></td>
                    <td><?= $v['rules_value'] ?></td>
                    <td><?= $v['price'] ?></td>
                    <td><?= $v['stock'] ?></td>
                    <td class="status"><?= $this->statusList[$v['status']] ?></td>
                    <td><?= date('Y-m-d H:i:s', $v['create_time']) ?></td>
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
</div>
<?php if (count($this->list)): ?>
    <div class="modal-event-footer modal-event-search-footer">
        <div>
            <div class="btn btn-outline-success modal-event-confirm-one">添加</div>
            <?php if ($this->params['multiple']): ?>
                <div class="btn btn-outline-success modal-event-confirm-multiple">添加并继续</div>
            <?php endif; ?>
            <div class="btn btn-outline-primary modal-event-close">关闭</div>
        </div>
        <div>
            <?= $this->pagination ?>
        </div>
    </div>
<?php endif; ?>