<div class="modal-event-body">
    <div class="table-responsive">
        <div class="search-form">
            <input type="hidden" name="page" value="<?= $this->params['page'] ?>">
            <input type="hidden" name="multiple" value="<?= $this->params['multiple'] ?>">
            <input type="hidden" name="product_type" value="<?= $this->params['product_type'] ?>">
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
                <span class="col-form-label search-label">品牌</span>
                <select class="form-control search-input select2" name="brand_id">
                    <option value="">请选择</option>
                    <?php foreach ($this->brandList as $k => $v): ?>
                        <option value="<?= $k ?>" <?= $this->params['brand_id'] == (string)$k ? 'selected' : '' ?>><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php $searchList = ['product_id' => '商品ID']; ?>
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
                    <div class="btn btn-primary product-search-btn search-btn text-nowrap"><i
                                class="glyphicon glyphicon-search"></i> 搜索
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered list-table text-nowrap text-center">
                <tbody>
                <tr>
                    <th><?php if ($this->params['multiple']): ?>
                            <input type="checkbox" class="check-all">
                        <?php endif; ?>
                    </th>
                    <th>商品ID</th>
                    <th>商品名称</th>
                    <th>分类名称</th>
                    <th>品牌</th>
                    <th>主图</th>
                    <th>状态</th>
                    <th>创建时间</th>
                </tr>

                <?php foreach ($this->list as $v): ?>
                    <tr class="product-search-tr" style="cursor: pointer">
                        <td><input type="checkbox" class="check-one" name="ids" value="<?= $v['product_id'] ?>"
                                   data-info='<?= json_encode($v, JSON_HEX_APOS) ?>'></td>
                        <td class="product_id"><?= $v['product_id'] ?></td>
                        <td class="product_name"><?= $v['product_name'] ?></td>
                        <td><?= $v['category_name'] ?></td>
                        <td><?= $this->brandList[$v['brand_id']] ?></td>
                        <td class="pic" data-value="<?= $v['pic'] ?>">
                            <?php if ($v['pic']): ?>
                                <img src="<?= \App::$urlManager->staticUrl($v['pic']) ?>" style="width: 40px;height:40px;">
                            <?php endif; ?>
                        </td>
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
</div>
<?php if (count($this->list)): ?>
    <div class="modal-event-footer modal-event-search-footer">
        <div>
            <?php if ($this->params['multiple']): ?>
                <div class="btn btn-outline-success modal-event-confirm-multiple">添加并继续</div>
            <?php else: ?>
                <div class="btn btn-outline-success modal-event-confirm-one">添加</div>
            <?php endif; ?>
            <div class="btn btn-outline-primary modal-event-close">关闭</div>
        </div>
        <div>
            <?= $this->pagination ?>
        </div>
    </div>
<?php endif; ?>