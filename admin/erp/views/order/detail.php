<style>
    .detail-title {
        font-size: 1rem;
        font-weight: bold;
        margin: 0.5rem 0;
    }

    .detail-info {
        min-width: 10rem;
        margin: auto;
        text-align: left;
    }

    .detail-info div span {
        margin-left: 0.2rem;
    }
</style>
<div class="alert alert-success" role="alert">
    <div>
        当前订单状态：<?= $this->statusList[$this->order['order_type']][$this->order['order_group']][$this->order['status']] ?></div>
</div>
<div class="table-responsive" style="width: 80%;margin: auto">
    <div class="detail-title">基本信息</div>
    <div class="base-info">
        <table class="table table-bordered text-nowrap text-center">
            <tr>
                <td>客户信息</td>
                <td>订单信息</td>
                <?php if ($this->order['order_type'] == 1): ?>
                    <td>联系人信息</td>
                <?php endif; ?>
            </tr>
            <tr>
                <td>
                    <div class="detail-info">
                        <div><span>昵称:</span><span><?= $this->user['nickname'] ?></span></div>
                        <div><span>手机号:</span><span><?= $this->user['telephone'] ?></span></div>
                    </div>
                </td>
                <td>
                    <div class="detail-info">
                        <div>
                            <span>订单类型:</span><span><?= $this->orderTypeList[$this->order['order_type']] . ' | ' . $this->orderGroupList[$this->order['order_group']] ?></span>
                        </div>
                        <div><span>订单编号:</span><span><?= $this->order['order_code'] ?></div>
                        <div><span>下单时间:</span><span><?= date('Y-m-d H:i:s', $this->order['create_time']) ?></span>
                        </div>
                        <?php if ($this->order['pay_time']): ?>
                            <div><span>付款时间:</span><span><?= date('Y-m-d H:i:s', $this->order['pay_time']) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ($this->order['order_type'] == \admin\extend\Constant::ORDER_TYPE_REAL): ?>
                            <?php if ($this->order['deliver_time']): ?>
                                <div>
                                    <span>发货时间:</span><span><?= date('Y-m-d H:i:s', $this->order['deliver_time']) ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($this->order['receive_time']): ?>
                                <div>
                                    <span>收货时间:</span><span><?= date('Y-m-d H:i:s', $this->order['receive_time']) ?></span>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if ($this->order['receive_time']): ?>
                                <div>
                                    <span>使用时间:</span><span><?= date('Y-m-d H:i:s', $this->order['receive_time']) ?></span>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <div><span>备注:</span><span><?= $this->order['remark'] ?></span></div>
                    </div>
                </td>
                <?php if ($this->order['order_type'] == 1): ?>
                    <td>
                        <div class="detail-info">
                            <div><span>姓名:</span><span><?= $this->order['receiver_name'] ?></span></div>
                            <div><span>手机号:</span><span><?= $this->order['receiver_mobile'] ?></span></div>
                            <div><span>收货地址:</span><span><?= $this->order['receiver_address'] ?></span></div>
                            <div><span>邮编:</span><span><?= $this->order['receiver_postal'] ?></span></div>
                        </div>
                    </td>
                <?php endif; ?>
            </tr>
        </table>
    </div>
    <div class="detail-title">商品信息</div>
    <div class="product-info">
        <table class="table table-bordered list-table text-nowrap text-center">
            <tr>
                <td>商品主图</td>
                <td>商品名称</td>
                <td>规格</td>
                <td>SKU编号</td>
                <td>价格</td>
                <td>数量</td>
                <td>小计</td>
            </tr>
            <?php foreach ($this->variationList as $v): ?>
                <tr>
                    <td> <?php if ($v['pic']): ?>
                            <img src="<?= $v['pic'] ?>" style="width: 40px;height: 40px;">
                        <?php endif; ?>
                    </td>
                    <td><?= $v['product_name'] ?></td>
                    <td><?= $v['rules_value'] ? $v['rules_value'] : '<i style="color: #666">无规格</i>' ?></td>
                    <td><?= $v['variation_code'] ?></td>
                    <td><?= $v['price'] ?></td>
                    <td><?= $v['number'] ?></td>
                    <td><?= $v['price'] * $v['number'] ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!count($this->variationList)): ?>
                <tr>
                    <td colspan="100" class="list-table-nodata">暂无相关数据</td>
                </tr>
            <?php endif; ?>
            <tfooter>
                <tr>
                    <td colspan="7" class="text-right">
                        <div><span>商品总价:</span><span style="color:#dc3545"><?= $this->order['product_money'] ?></span>
                        </div>
                        <?php if ($this->order['order_type'] == \admin\extend\Constant::ORDER_TYPE_REAL): ?>
                            <div><span>运费:</span><span style="color:#dc3545"><?= $this->order['freight_money'] ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ($this->coupon): ?>
                            <div><span>优惠券:</span><span style="color:#dc3545"><?= $this->coupon['coupon_name'] ?></span>
                            </div>
                        <?php endif; ?>
                        <div><span>优惠:</span><span style="color:#dc3545"><?= $this->order['rate_money'] ?></span></div>
                        <div><span>订单总金额:</span><span style="color:#dc3545"><?= $this->order['money'] ?></span></div>
                    </td>
                </tr>
            </tfooter>
        </table>
    </div>
    <?php if ($this->order['order_type'] == \admin\extend\Constant::ORDER_TYPE_REAL): ?>
        <div class="detail-title">物流信息</div>
        <div class="transport-info">
            <table class="table table-bordered list-table text-nowrap text-center">
                <tr>
                    <td>物流方式</td>
                    <td>物流单号</td>
                </tr>
                <?php if ($this->order['status'] >= \admin\extend\Constant::ORDER_STATUS_SHIPPED && $this->transport): ?>
                    <tr>
                        <td><?= $this->transport['transport_name'] ?></td>
                        <td><?= $this->order['transport_order'] ?></td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    <?php endif; ?>
    <div class="detail-title">订单日志</div>
    <div class="log-info">
        <table class="table table-bordered list-table text-nowrap text-center">
            <tr>
                <td>操作</td>
                <td>操作人</td>
                <td>操作时间</td>
            </tr>
            <?php foreach ($this->traceList as $v): ?>
                <tr>
                    <td><?= $v['detail'] ?></td>
                    <td><?= $this->operatorList[$v['user_type']][$v['create_userid']] ?></td>
                    <td><?= date('Y-m-d H:i:s', $v['create_time']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>