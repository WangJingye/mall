<div class="btn-box clearfix">
    <a href="<?= \App::$urlManager->createUrl('erp/order/edit') ?>">
        <div class="btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i> 创建</div>
    </a>
</div>
<form class="search-form" action="<?= \App::$urlManager->createUrl('erp/order/index') ?>" method="get">
    <div class="form-content">
        <span class="col-form-label search-label">用户昵称</span>
        <input type="text" class="form-control search-input" name="nickname" value="<?= $this->params['nickname'] ?>">
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">订单类型</span>
        <select class="form-control search-input" name="order_type">
            <option value="">请选择</option>
            <?php foreach ($this->orderTypeList as $k => $v): ?>
                <option value="<?= $k ?>" <?= $this->params['order_type'] == (string)$k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-content">
        <?= \admin\extend\input\TimeSearch::instance('create_time', '下单时间', $this->params)->show() ?>
    </div>
    <?php $searchList = ['order_id' => '订单ID', 'order_code' => '订单编号']; ?>
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
    <table class="table table-bordered list-table text-center text-nowrap">
        <tbody>
        <tr>
            <th><input type="checkbox" class="check-all"></th>
            <th>订单ID</th>
            <th>订单类型</th>
            <th>订单信息</th>
            <th>下单时间</th>
            <th>用户昵称</th>
            <th>用户手机号</th>
            <th>订单金额</th>
            <th>支付金额</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        <?php foreach ($this->list as $v): ?>
            <tr>
                <td><input type="checkbox" class="check-one" value="<?= $v['order_id'] ?>"></td>
                <td><?= $v['order_id'] ?></td>
                <td>
                    <?= $this->orderTypeList[$v['order_type']] ?>
                    <hr style="margin: 0;padding: 0;">
                    <?= $this->orderGroupList[$v['order_group']] ?>
                </td>
                <td>
                    <div><?= $v['order_title'] ?></div>
                    <hr style="margin: 0;padding: 0;">
                    <div><?= $v['order_code'] ?></div>
                </td>
                <td><?= date('Y-m-d H:i:s', $v['create_time']) ?></td>
                <td><?= $this->userList[$v['user_id']]['nickname'] ?></td>
                <td><?= $this->userList[$v['user_id']]['telephone'] ?></td>
                <td><?= $v['money'] ?></td>
                <td><?= $v['pay_money'] ?></td>
                <td><?= $this->statusList[$v['order_type']][$v['order_group']][$v['status']] ?></td>
                <td>
                    <div>
                        <a class="btn btn-outline-success btn-sm"
                           href="<?= \App::$urlManager->createUrl('erp/order/detail', ['order_id' => $v['order_id']]) ?>">
                            <i class="glyphicon glyphicon-eye-open"></i> 查看
                        </a>
                        <?php if ($v['status'] != \common\helper\Constant::ORDER_STATUS_CLOSE): ?>
                            <a class="btn btn-primary btn-sm"
                               href="<?= \App::$urlManager->createUrl('erp/order/edit', ['order_id' => $v['order_id']]) ?>">
                                <i class="glyphicon glyphicon-pencil"></i> 编辑
                            </a>
                        <?php else: ?>
                            <div class="btn btn-danger btn-sm remove-btn" data-id="<?= $v['order_id'] ?>">
                                <i class="glyphicon glyphicon-trash"></i> 删除
                            </div>
                        <?php endif; ?>
                    </div>
                    <div style="margin-top: 0.2rem">
                        <?php if ($v['status'] == \common\helper\Constant::ORDER_STATUS_CREATED): ?>
                            <div class="btn btn-outline-danger btn-sm pay-btn" data-id="<?= $v['order_id'] ?>">
                                <i class="iconfont icon-pay"></i> 收款
                            </div>
                            <div class="btn btn-outline-danger btn-sm close-btn" data-id="<?= $v['order_id'] ?>">
                                <i class="glyphicon glyphicon-remove"></i> 关闭
                            </div>
                        <?php elseif ($v['status'] == \common\helper\Constant::ORDER_STATUS_PENDING): ?>
                            <div class="btn btn-outline-primary btn-sm ship-btn" data-id="<?= $v['order_id'] ?>">
                                <i class="glyphicon glyphicon-send"></i> 订单发货
                            </div>
                        <?php elseif ($v['status'] == \common\helper\Constant::ORDER_STATUS_SHIPPED): ?>
                            <?php if ($v['order_type'] == \common\helper\Constant::ORDER_TYPE_VIRTUAL): ?>
                                <div class="btn btn-outline-danger btn-sm confirm-virtual-btn"
                                     data-id="<?= $v['order_id'] ?>">
                                    <i class="glyphicon glyphicon-ok"></i> 确认使用
                                </div>
                            <?php else: ?>
                                <div class="btn btn-outline-danger btn-sm confirm-btn" data-id="<?= $v['order_id'] ?>">
                                    <i class="glyphicon glyphicon-ok"></i> 确认收货
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
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
    var transportList = <?=json_encode($this->transportList)?>;
    var payMethodList = <?=json_encode($this->payMethodList)?>;
</script>
<?php $this->appendScript('order.js') ?>