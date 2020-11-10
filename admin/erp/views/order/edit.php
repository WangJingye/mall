<style>
    .input-td {
        width: 6rem;
    }

    .calc-price {
        width: 6rem;
    }
</style>
<form class="form-box col-12 col-sm-12 col-md-12" id="save-form"
      action="<?= \App::$urlManager->createUrl('erp/order/edit') ?>" method="post">
    <input type="hidden" name="order_id" value="<?= $this->model['order_id'] ?>">
    <div class="form-group row">
        <div class="col-sm-6 row form-col-inline">
            <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>订单标题</label>
            <div class="col-sm-8">
                <input type="text" name="order_title" class="form-control" value="<?= $this->model['order_title'] ?>"
                       placeholder="请输入订单标题">
            </div>
        </div>
        <div class="col-sm-6 row form-col-inline">
            <label class="col-sm-4 text-nowrap col-form-label form-label">订单编号</label>
            <div class="col-sm-8">
                <input type="text" name="order_code" class="form-control" value="<?= $this->model['order_code'] ?>"
                       disabled>
            </div>
        </div>
        <div class="col-sm-6 row form-col-inline">
            <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>订单类型</label>
            <div class="col-sm-8">
                <?= \admin\extend\input\SelectInput::instance($this->orderTypeList, $this->model['order_type'], 'order_type', 'select')->show(); ?>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-6 row form-col-inline">
            <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>用户</label>
            <div class="col-sm-8">
                <input type="text" class="form-control search-user"
                       value="<?= $this->user['nickname'] ?>"
                       readonly placeholder="请选择用户">
                <input type="hidden" name="user_id" value="<?= $this->user['user_id'] ?>">
                <small class="text-muted">点击选择用户</small>
            </div>
        </div>
        <div class="col-sm-6 row form-col-inline receiver-group">
            <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>收件人姓名</label>
            <div class="col-sm-8">
                <input type="text" name="receiver_name" class="form-control"
                       value="<?= $this->model['receiver_name'] ?>"
                       placeholder="请输入收件人">
            </div>
        </div>
        <div class="col-sm-6 row form-col-inline receiver-group">
            <label class="col-sm-4 text-nowrap col-form-label form-label"><span
                        style="color: red">*</span>收件人手机号</label>
            <div class="col-sm-8">
                <input type="text" name="receiver_mobile" class="form-control"
                       value="<?= $this->model['receiver_mobile'] ?>" placeholder="请输入收件人手机号">
            </div>
        </div>
        <div class="col-sm-6 row form-col-inline receiver-group">
            <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>收件人地址</label>
            <div class="col-sm-8">
                <input type="text" name="receiver_address" class="form-control"
                       value="<?= $this->model['receiver_address'] ?>" placeholder="请输入收件人地址">
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-sm-6 row form-col-inline">
            <label class="col-sm-4 text-nowrap col-form-label form-label">订单金额</label>
            <div class="col-sm-8">
                <input type="number" name="money" class="form-control"
                       value="<?= $this->model['money'] ? $this->model['money'] : '0.00' ?>" readonly>
            </div>
        </div>
        <div class="col-sm-6 row form-col-inline">
            <label class="col-sm-4 text-nowrap col-form-label form-label">运费</label>
            <div class="col-sm-8">
                <input type="text" name="freight_money" class="form-control"
                       value="<?= $this->model['freight_money'] ? $this->model['freight_money'] : 0 ?>"
                       readonly>
            </div>
        </div>
        <div class="col-sm-6 row form-col-inline">
            <label class="col-sm-4 text-nowrap col-form-label form-label">优惠券</label>
            <div class="col-sm-8">
                <div style="display: inline-flex;width: 100%">
                    <input type="text" class="form-control search-coupon"
                           value="<?= $this->model['coupon_id'] ? $this->coupon['coupon_name'] : '' ?>" readonly
                           placeholder="点击选择优惠券">
                    <?php if ($this->model['coupon_id']): ?>
                        <span class="search-clear-btn"><i class="glyphicon glyphicon-remove-circle"></i></span>
                    <?php endif; ?>
                    <input type="hidden" name="coupon_id"
                           value="<?= $this->model['coupon_id'] ? $this->model['coupon_id'] : 0 ?>">
                </div>
            </div>
        </div>
        <div class="col-sm-6 row form-col-inline">
            <label class="col-sm-4 text-nowrap col-form-label form-label">优惠金额</label>
            <div class="col-sm-8">
                <input type="number" name="rate_money" class="form-control" readonly
                       value="<?= $this->model['rate_money'] ? $this->model['rate_money'] : 0 ?>"
                       placeholder="请输入优惠金额">
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 row form-col-inline">
            <label class="col-sm-2 text-nowrap col-form-label form-label">备注</label>
            <div class="col-sm-8">
                <textarea name="remark" class="form-control" cols="30" rows="3"><?= $this->model['remark'] ?></textarea>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 row form-col-inline">
            <label class="col-sm-2 text-nowrap col-form-label form-label">商品数据</label>
            <div class="col-sm-8">
                <div class="btn btn-info search-product-variation"><i class="glyphicon glyphicon-plus"></i> 添加数据</div>
                <div class="btn btn-danger remove-product-variation"><i class="glyphicon glyphicon-remove"></i> 清空数据
                </div>
            </div>
        </div>
        <div class="col-sm-12 row form-col-inline">
            <div class="offset-2 col-sm-10">
                <table class="table table-bordered text-nowrap list-table product-list text-center">
                    <tr>
                        <td>商品ID</td>
                        <td>商品名称</td>
                        <td>SKU</td>
                        <td>规格</td>
                        <td class="input-td">销售数量</td>
                        <td class="input-td">销售单价</td>
                        <td>小计</td>
                        <td>操作</td>
                    </tr>
                    <?php foreach ($this->variationList as $v): ?>
                        <?php $product = $this->productList[$v['product_id']]; ?>
                        <tr class="order-detail-variation" data-id="<?= $v['variation_code'] ?>">
                            <td>
                                <input type="hidden" class="product_weight"
                                       value="<?= $product['product_weight'] ?>">
                                <input type="hidden" class="freight_id"
                                       value="<?= $product['freight_id'] ?>">
                                <?= $v['product_id'] ?>
                            </td>
                            <td class="text-wrap text-break" style="min-width: 200px"><?= $v['product_name'] ?></td>
                            <td><?= $v['variation_code'] ?></td>
                            <td><?= $v['rules_value'] ?></td>
                            <td><input type="number" class="form-control number calc-price" value="<?= $v['number'] ?>"></td>
                            <td><input type="number" class="form-control price calc-price" value="<?= $v['price'] ?>">
                            </td>
                            <td class="calc-total"><?= round($v['number'] * $v['price'], 2) ?></td>
                            <td>
                                <div class="btn btn-dark btn-sm remove-variation-btn"><i
                                            class="glyphicon glyphicon-remove"></i> 删除
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="empty-variation-tr" <?= !count($this->variationList) ? '' : 'style="display:none"' ?>>
                        <td colspan="10" class="list-table-nodata">暂无相关数据</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-12 row form-col-inline">
            <div class="offset-2 col-sm-10">
                <input class="btn btn-primary btn-lg" type="submit" value="保存"/>
            </div>
        </div>
    </div>
</form>
<script>
    var freightList = <?=json_encode($this->freightList)?>;
</script>
<?php $this->appendScript('coupon-search.js')->appendScript('user-search.js')->appendScript('product-search.js')->appendScript('order.js') ?>