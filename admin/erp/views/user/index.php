<form class="search-form" action="<?= \App::$urlManager->createUrl('erp/user/index') ?>" method="get">
    <div class="form-content">
        <span class="col-form-label search-label">昵称</span>
        <input type="text" class="form-control search-input" name="nickname" value="<?= $this->params['nickname'] ?>">
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">手机号</span>
        <input type="text" class="form-control search-input" name="telephone" value="<?= $this->params['telephone'] ?>">
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
            <th>昵称</th>
            <th>头像</th>
            <th>手机号</th>
            <th>注册时间</th>
            <th>消费金额</th>
            <th>订单数量</th>
            <th>操作</th>
        </tr>
        <?php foreach ($this->list as $v): ?>
            <tr>
                <td><input type="checkbox" class="check-one" value="<?= $v['user_id'] ?>"></td>
                <td><?= $v['user_id'] ?></td>
                <td><?= $v['nickname'] ?></td>
                <td>
                    <?php if ($v['avatar']): ?>
                        <img src="<?= \App::$urlManager->staticUrl($v['avatar']) ?>" style="width: 40px;height: 40px;">
                    <?php endif; ?>
                </td>
                <td><?= $v['telephone'] ?></td>
                <td><?= date('Y-m-d H:i:s', $v['create_time']) ?></td>
                <td><?= isset($this->saleList[$v['user_id']]['amount']) ? $this->saleList[$v['user_id']]['amount'] : 0 ?></td>
                <td><?= isset($this->saleList[$v['user_id']]['number']) ? $this->saleList[$v['user_id']]['number'] : 0 ?></td>
                <td>
                    <a class="btn btn-outline-success btn-sm"
                       href="<?= \App::$urlManager->createUrl('erp/user/detail', ['user_id' => $v['user_id']]) ?>">
                        <i class="glyphicon glyphicon-eye-open"></i> 查看
                    </a>
                    <a class="btn btn-primary btn-sm"
                       href="<?= \App::$urlManager->createUrl('erp/user/edit', ['user_id' => $v['user_id']]) ?>">
                        <i class="glyphicon glyphicon-pencil"></i> 编辑
                    </a>
                    <?php if ($v['status'] == 1): ?>
                        <div class="btn btn-danger btn-sm set-status-btn" data-id="<?= $v['user_id'] ?>"
                             data-status="0">
                            <i class="glyphicon glyphicon-remove-circle"></i> <span>禁用</span>
                        </div>
                    <?php else: ?>
                        <div class="btn btn-success btn-sm set-status-btn" data-id="<?= $v['user_id'] ?>"
                             data-status="1">
                            <i class="glyphicon glyphicon-ok-circle"></i> <span>解禁</span>
                        </div>
                    <?php endif; ?>
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
<?php $this->appendScript('user.js') ?>