<form class="search-form" action="<?= \App::$urlManager->createUrl('erp/user-verify/index') ?>" method="get">
    <div class="form-content">
        <span class="col-form-label search-label">用户昵称</span>
        <input type="text" class="form-control search-input" name="nickname" value="<?= $this->params['nickname'] ?>">
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">真实姓名</span>
        <input type="text" class="form-control search-input" name="realname" value="<?= $this->params['realname'] ?>">
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">行业</span>
        <select class="form-control search-input" name="industry_id">
            <option value="">请选择</option>
            <?php foreach ($this->industryList as $k => $v): ?>
                <option value="<?= $k ?>" <?= $this->params['industry_id'] == (string)$k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">公司名称</span>
        <input type="text" class="form-control search-input" name="company_name"
               value="<?= $this->params['company_name'] ?>">
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">手机号</span>
        <input type="text" class="form-control search-input" name="telephone" value="<?= $this->params['telephone'] ?>">
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">审核状态</span>
        <select class="form-control search-input" name="verify_status">
            <option value="">请选择</option>
            <?php foreach ($this->verifyStatusList as $k => $v): ?>
                <option value="<?= $k ?>" <?= $this->params['verify_status'] == (string)$k ? 'selected' : '' ?>><?= $v ?></option>
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
            <th>用户ID</th>
            <th>用户昵称</th>
            <th>姓名</th>
            <th>行业</th>
            <th>公司名称</th>
            <th>手机号</th>
            <th>审核状态</th>
            <th>提交时间</th>
            <th>审核时间</th>
            <th>操作</th>
        </tr>
        <?php
        $statusColorList = [0 => '#000', 1 => '#28a745', 2 => '#dc3545'];
        foreach ($this->list as $v): ?>
            <tr>
                <td><input type="checkbox" class="check-one" value="<?= $v['verify_id'] ?>"></td>
                <td><?= $v['user_id'] ?></td>
                <td><?= $v['nickname'] ?></td>
                <td><?= $v['realname'] ?></td>
                <td><?= $this->industryList[$v['industry_id']] ?></td>
                <td><?= $v['company_name'] ?></td>
                <td><?= $v['telephone'] ?></td>
                <td class="verify-status"
                    style="color:<?= $statusColorList[$v['verify_status']] ?>"><?= $this->verifyStatusList[$v['verify_status']] ?></td>
                <td><?= date('Y-m-d H:i:s', $v['create_time']) ?></td>
                <td><?= $v['verify_time'] ? date('Y-m-d H:i:s', $v['verify_time']) : '--' ?></td>
                <td>
                    <div class="btn btn-outline-success btn-sm" data-id="<?= $v['verify_id'] ?>">
                        <i class="glyphicon glyphicon-eye-open"></i> 查看
                    </div>
                    <?php if ($v['verify_status'] == 0): ?>
                        <div class="btn btn-outline-primary btn-sm verify-btn" data-id="<?= $v['verify_id'] ?>">
                            <i class="glyphicon glyphicon-check"></i> 审核
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
<?php $this->appendScript('user-verify.js') ?>