<div class="modal-event-body">
    <div class="table-responsive">
        <table class="table table-bordered list-table text-nowrap text-center">
            <tbody>
            <tr>
                <th><?php if ($this->params['multiple']): ?>
                        <input type="checkbox" class="check-all">
                    <?php endif; ?>
                </th>
                <th>优惠券名称</th>
                <th>类型</th>
                <th>最小使用价格</th>
            </tr>
            <?php foreach ($this->list as $v): ?>
                <tr class="coupon-search-tr" style="cursor: pointer">
                    <td><input type="checkbox" class="check-one" name="ids" value="<?= $v['id'] ?>"
                               data-info='<?= json_encode($v, JSON_HEX_APOS) ?>'></td>
                    <td><?= $v['coupon_name'] ?></td>
                    <td><?= $this->typeList[$v['type']] ?></td>
                    <td><?= $v['min_price'] ?></td>
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