<div class="btn-box clearfix">
    <div class="btn btn-success pull-right share-all-btn"><i class="glyphicon glyphicon-share-alt"></i> 批量回复</div>
</div>
<form class="search-form" action="<?= \App::$urlManager->createUrl('erp/suggest/index') ?>" method="get">
    <div class="form-content">
        <span class="col-form-label search-label">用户昵称</span>
        <input type="text" class="form-control search-input" name="nickname" value="<?= $this->params['nickname'] ?>">
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">状态</span>
        <select class="form-control search-input" name="status">
            <option value="">请选择</option>
            <?php foreach ($this->statusList as $k => $v): ?>
                <option value="<?= $k ?>" <?= $this->params['status'] == (string)$k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php $searchList = ['id' => 'ID']; ?>
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
            <th><input type="checkbox" class="check-all"></th>
            <th>ID</th>
            <th>用户</th>
            <th>内容</th>
            <th>状态</th>
            <th>回复内容</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        <?php foreach ($this->list as $v): ?>
            <tr>
                <td>
                    <input type="checkbox" class="check-one" data-status="<?= $v['status'] ?>"
                           value="<?= $v['id'] ?>">
                </td>
                <td><?= $v['id'] ?></td>
                <td><?= $this->userList[$v['user_id']] ?></td>
                <td><?= $v['content'] ?></td>
                <td class="status"><?= $this->statusList[$v['status']] ?></td>
                <td><?= $v['reply'] ?></td>
                <td><?= date('Y-m-d H:i:s', $v['create_time']) ?></td>
                <td>
                    <?php if ($v['status'] == 1): ?>
                    <div class="btn btn-success btn-sm share-btn"
                       data-url="<?= \App::$urlManager->createUrl('erp/suggest/reply') ?>"
                       data-id="<?= $v['id'] ?>">
                        <i class="glyphicon glyphicon-share-alt"></i> 回复
                    </div>
                    <?php endif;?>
                    <div class="btn btn-danger btn-sm remove-btn" data-id="<?= $v['id'] ?>">
                        <i class="glyphicon glyphicon-trash"></i> 删除
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!count($this->list)): ?>
            <tr>
                <td colspan="100" class="list-table-nodata">暂无相关数据</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->pagination ?>
<?php $this->appendScript('suggest.js') ?>