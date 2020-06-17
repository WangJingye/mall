<style>
    .detail-title {
        font-size: 1rem;
        font-weight: bold;
        margin: 0.5rem 0;
    }

    .base-info-left {
        width: 10rem;
        display: inline-block;
        text-align: center;
    }

    .base-info-right {
        display: inline-block;
        width: 25rem;
    }

    .base-info-right table {
        margin-bottom: 0;
    }

    .set-default {
        cursor: pointer;
    }

    .show-more {
        cursor: pointer;
    }
</style>
<input type="hidden" id="user_id" value="<?= $this->model['user_id'] ?>">
<div class="table-responsive" style="width: 80%;margin: auto">
    <div class="detail-title">基本信息</div>
    <div class="base-info">
        <div class="base-info-left">
            <div>
                <?php if ($this->model['avatar']): ?>
                    <img src="<?= $this->model['avatar'] ?>" style="width: 5rem;height:5rem">
                <?php endif; ?>
            </div>
            <div class="text-center" style="margin-top: 0.5rem"><?= $this->model['nickname'] ?></div>
            <div class="text-center"><?= $this->model['telephone'] ?></div>
        </div>
        <div class="base-info-right">
            <table class="table table-bordered text-nowrap">
                <tr>
                    <td style="background-color: #e9ecef">用户ID</td>
                    <td><?= $this->model['user_id'] ?></td>
                    <td style="background-color: #e9ecef">城市</td>
                    <td><?= $this->model['city'] ?></td>
                </tr>
                <tr>
                    <td style="background-color: #e9ecef">性别</td>
                    <td><?= $this->genderList[$this->model['gender']] ?></td>
                    <td style="background-color: #e9ecef">注册时间</td>
                    <td><?= date('Y-m-d H:i:s', $this->model['create_time']) ?></td>
                </tr>
                <tr>
                    <td style="background-color: #e9ecef">生日</td>
                    <td><?= $this->model['birthday'] ?></td>
                    <td style="background-color: #e9ecef"></td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="detail-title">收货地址</div>
    <div class="address">
        <table class="table table-bordered list-table text-nowrap text-center">
            <tr>
                <td>姓名</td>
                <td>手机号码</td>
                <td>详细地址</td>
                <td>邮编</td>
                <td>默认地址</td>
            </tr>
            <?php foreach ($this->addressList as $v): ?>
                <tr>
                    <td><?= $v['receiver_name'] ?></td>
                    <td><?= $v['receiver_mobile'] ?></td>
                    <td><?= $v['detail_address'] ?></td>
                    <td><?= $v['postal'] ?></td>
                    <td class="set-default"><input type="radio" name="is_default" class="is_default"
                                                   value="<?= $v['address_id'] ?>" <?= $v['is_default'] == 1 ? 'checked' : '' ?>>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!count($this->addressList)): ?>
                <tr>
                    <td colspan="100" class="list-table-nodata">暂无相关数据</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
    <div class="detail-title">订单记录</div>
    <div class="order_info">
        <table class="table table-bordered list-table text-nowrap text-center">
            <tr>
                <td>订单类型</td>
                <td>订单信息</td>
                <td>下单时间</td>
                <td>订单金额</td>
                <td>支付金额</td>
                <td>订单状态</td>
                <td>操作</td>
            </tr>
            <?php foreach ($this->orderList as $v): ?>
                <tr>
                    <td><?= $this->orderTypeList[$v['order_type']] ?></td>
                    <td>
                        <?= $v['order_title'] ?>
                        <hr style="margin: 0;padding: 0;">
                        <?= $v['order_code'] ?>
                    </td>
                    <td><?= date('Y-m-d H:i:s', $v['create_time']) ?></td>
                    <td><?= $v['money'] ?></td>
                    <td><?= $v['pay_money'] ?></td>
                    <td><?= $v['order_type'] == \admin\extend\Constant::ORDER_TYPE_REAL ? $this->orderStatusList[$v['status']] : $this->orderVirtualStatusList[$v['status']] ?></td>
                    <td>
                        <a href="<?= \App::$urlManager->createUrl('erp/order/detail', ['order_id' => $v['order_id']]) ?>"
                           target="_blank" class="btn btn-sm btn-outline-success">查看</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!count($this->orderList)): ?>
                <tr>
                    <td colspan="100" class="list-table-nodata">暂无相关数据</td>
                </tr>
            <?php elseif (count($this->orderList) == 5): ?>
                <tr>
                    <td colspan="18" class="show-more" data-id="<?= $this->model['user_id'] ?>"
                        data-url="<?= \App::$urlManager->createUrl('erp/user/get-order-list') ?>"
                        data-page="2">
                        点击显示更多
                    </td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>
<script>
    var orderTypeList =<?=json_encode($this->orderTypeList);?>;
    var orderStatusList =<?=json_encode($this->orderStatusList);?>;
    var orderVirtualStatusList =<?=json_encode($this->orderVirtualStatusList);?>;
</script>
<?php $this->appendScript('user.js') ?>