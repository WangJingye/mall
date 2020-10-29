<form class="search-form" action="<?= \App::$urlManager->createUrl('erp/spread/promoter') ?>" method="get">
    <div class="form-content">
        <span class="col-form-label search-label">昵称</span>
        <input type="text" class="form-control search-input" name="nickname" value="<?= $this->params['nickname'] ?>">
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">手机号</span>
        <input type="text" class="form-control search-input" name="telephone" value="<?= $this->params['telephone'] ?>">
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">上级推广人</span>
        <input type="text" class="form-control search-input search-user" readonly
               value="<?= isset($this->spreadList[$this->params['spread_id']]) ? $this->spreadList[$this->params['spread_id']] : '' ?>">
        <input type="hidden" name="spread_id" value="<?= $this->params['spread_id'] ?>">
        <?php if ($this->params['spread_id']): ?>
            <span class="search-clear-btn"><i class="glyphicon glyphicon-remove-circle"></i></span>
        <?php endif; ?>
    </div>
    <?php $searchList = ['user_id' => '会员ID']; ?>
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
            <th>会员ID</th>
            <th>头像</th>
            <th>昵称</th>
            <th>手机号</th>
            <th>冻结金额</th>
            <th>推广用户数量</th>
            <th>推广订单金额</th>
            <th>佣金</th>
            <th>已提现金额</th>
            <th>上级推广人</th>
            <th>操作</th>
        </tr>
        <?php foreach ($this->list as $v):
            $wallet = isset($this->walletList[$v['user_id']]) ? $this->walletList[$v['user_id']] : []; ?>
            <tr>
                <td><input type="checkbox" class="check-one" value="<?= $v['user_id'] ?>"></td>
                <td><?= $v['user_id'] ?></td>
                <td>
                    <?php if ($v['avatar']): ?>
                        <img src="<?= \App::$urlManager->staticUrl($v['avatar']) ?>" style="width: 40px;height: 40px;">
                    <?php endif; ?>
                </td>
                <td><?= $v['nickname'] ?></td>
                <td><?= $v['telephone'] ?></td>
                <td><?= isset($wallet['frozen_money']) ? $wallet['frozen_money'] : 0 ?></td>
                <td><?= isset($this->childUserCount[$v['user_id']]) ? $this->childUserCount[$v['user_id']] : 0 ?></td>
                <td><?= isset($wallet['spread_order_money']) ? $wallet['spread_order_money'] : 0 ?></td>
                <td><?= isset($wallet['spread_money']) ? $wallet['spread_money'] : 0 ?></td>
                <td><?= isset($wallet['cash_out_money']) ? $wallet['cash_out_money'] : 0 ?></td>
                <td><?= isset($this->spreadList[$v['spread_id']]) ? $this->spreadList[$v['spread_id']] : '' ?></td>
                <td>
                    <a href="<?= \App::$urlManager->createUrl('erp/spread/promoter', ['spread_id' => $v['user_id']]) ?>"
                       class="btn btn-info btn-sm">查看推广成员</a>
                    <a href="<?= \App::$urlManager->createUrl('erp/spread/order', ['spread_id' => $v['user_id']]) ?>"
                       class="btn btn-info btn-sm">查看推广订单</a>
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
<?php $this->appendScript('user-search.js')->appendScript('spread.js') ?>