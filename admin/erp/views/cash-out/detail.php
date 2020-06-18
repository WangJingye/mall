<style>
    .detail-title {
        font-size: 1rem;
        font-weight: bold;
        margin: 0.5rem 0;
    }

    .table-thead {
        background-color: #e9ecef;
        width: 30%;
        text-align: right;
    }
</style>
<div class="table-responsive" style="width: 80%;margin: auto">
    <div class="detail-title">用户信息</div>
    <div class="order-info">
        <table class="table table-bordered text-nowrap">
            <tr>
                <td class="table-thead">用户昵称</td>
                <td><?= $this->user['nickname'] ?></td>
            </tr>
            <tr>
                <td class="table-thead">头像</td>
                <td> <?php if ($this->user['avatar']): ?>
                        <img src="<?= $this->user['avatar'] ?>" style="width: 40px;height: 40px;">
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td class="table-thead">所在地</td>
                <td><?= $this->user['city'] ?></td>
            </tr>
            <tr>
                <td class="table-thead">钱包余额</td>
                <td><?= $this->wallet['balance'] ?></td>
            </tr>
            <tr>
                <td class="table-thead">冻结金额</td>
                <td><?= $this->wallet['frozen_money'] ?></td>
            </tr>
            <tr>
                <td class="table-thead">已提现金额</td>
                <td><?= $this->wallet['cash_out_money'] ?></td>
            </tr>
        </table>
    </div>
    <div class="detail-title">审核信息</div>
    <div class="bill-info">
        <table class="table table-bordered list-table text-nowrap">
            <tr>
                <td class="table-thead">审核时间</td>
                <td><?= $this->model['verify_time'] ? date('Y-m-d H:i:s', $this->model['verify_time']) : '' ?></td>
            </tr>
            <tr>
                <td class="table-thead">审核状态</td>
                <td><?= $this->statusList[$this->model['status']] ?></td>
            </tr>
            <tr>
                <td class="table-thead">审核备注</td>
                <td><?= $this->model['remark'] ?></td>
            </tr>
        </table>
    </div>

</div>
