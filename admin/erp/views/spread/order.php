<form class="search-form" action="<?= \App::$urlManager->createUrl('erp/spread/order') ?>" method="get">
    <input type="hidden" name="spread_id" value="<?= $this->params['spread_id'] ?>">
</form>
<div class="table-responsive">
    <table class="table table-bordered list-table text-nowrap text-center">
        <tbody>
        <tr>
            <th>订单编号</th>
            <th>用户信息</th>
            <th>时间</th>
            <th>返佣金额</th>
        </tr>
        <?php foreach ($this->list as $v):
            $user = $this->userList[$v['user_id']]; ?>
            <tr>
                <td><?= $v['order_code'] ?></td>
                <td>
                    <div><span>昵称：</span><span><?= $user['nickname'] ?></span></div>
                    <div><span>手机号：</span><span><?= $user['telephone'] ?></span></div>
                </td>
                <td><?= date('Y-m-d H:i:s', $v['create_time']) ?></td>
                <td><?= $v['back_money'] ?></td>
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
<?php $this->appendScript('user-search.js')->appendScript('spread.js') ?>