<form class="search-form" action="<?= \App::$urlManager->createUrl('erp/user-level-verify/index') ?>" method="get">
    <div class="form-content">
        <span class="col-form-label search-label">用户</span>
        <input type="text" class="form-control search-input search-user" readonly
               value="<?= isset($this->userList[$this->params['user_id']]) ? $this->userList[$this->params['user_id']] : '' ?>">
        <input type="hidden" name="user_id" value="<?= $this->params['user_id'] ?>">
        <?php if ($this->params['user_id']): ?>
            <span class="search-clear-btn"><i class="glyphicon glyphicon-remove-circle"></i></span>
        <?php endif; ?>
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">会员等级</span>
        <select class="form-control search-input" name="level">
            <option value="">请选择</option>
            <?php foreach ($this->levelList as $k => $v): ?>
                <option value="<?= $k ?>" <?= $this->params['level'] == (string)$k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">审核状态</span>
        <select class="form-control search-input" name="status">
            <option value="">请选择</option>
            <?php foreach ($this->statusList as $k => $v): ?>
                <option value="<?= $k ?>" <?= $this->params['status'] == (string)$k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php $searchList = ['verify_id' => 'ID']; ?>
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
            <div class="btn btn-primary search-btn text-nowrap"><i class="glyphicon glyphicon-search"></i> 搜索</div>
        </div>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-bordered list-table text-center text-nowrap">
        <tbody>
        <tr>
            <th>ID</th>
            <th>用户</th>
            <th>目标等级</th>
            <th>审核状态</th>
            <th>审核回复</th>
            <th>创建时间</th>
            <th>审核时间</th>
            <th>操作</th>
        </tr>
        <?php foreach ($this->list as $v): ?>
            <tr>
                <td><?= $v['verify_id'] ?></td>
                <td><?= $this->userList[$v['user_id']] ?></td>
                <td><?= $this->levelList[$v['level']] ?></td>
                <td><?= $this->statusList[$v['status']] ?></td>
                <td><?= $v['remark'] ?></td>
                <td><?= date('Y-m-d H:i:s', $v['create_time']) ?></td>
                <td><?= date('Y-m-d H:i:s', $v['verify_time']) ?></td>
                <td>
                    <?php if ($v['status'] == 1): ?>
                        <div class="btn btn-outline-primary btn-sm check-btn" data-id="<?= $v['verify_id'] ?>">
                            <i class="iconfont icon-check"></i> 审核
                        </div>
                    <?php endif; ?>
                    <div class="btn btn-danger btn-sm remove-btn" data-id="<?= $v['verify_id'] ?>">
                        <i class="glyphicon glyphicon-trash"></i> 删除
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
<?php $this->appendScript('user-search.js')->appendScript('user-level-verify.js') ?>