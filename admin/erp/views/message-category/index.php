<div class="btn-box clearfix">
    <a href="<?= \App::$urlManager->createUrl('erp/message-category/edit') ?>">
        <div class="btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i> 创建</div>
    </a>
</div>
<form class="search-form" action="<?= \App::$urlManager->createUrl('erp/message-category/index') ?>" method="get">
    <div class="form-content">
        <span class="col-form-label search-label">分类名称</span>
        <input type="text" class="form-control search-input" name="category_name" value="<?= $this->params['category_name'] ?>">
    </div>
    <?php $searchList = ['category_id' => 'ID']; ?>
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
            <th>分类名称</th>
            <th>图标</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        <?php foreach ($this->list as $v): ?>
            <tr>
                <td><?= $v['category_id'] ?></td>
                <td><?= $v['category_name'] ?></td>
                <td>
                    <?php if ($v['pic']): ?>
                        <img src="<?= $v['pic'] ?>" style="width: 60px;height: 60px;">
                    <?php endif; ?>
                </td>
                <td><?= date('Y-m-d H:i:s', $v['create_time']) ?></td>
                <td>
                    <a class="btn btn-primary btn-sm"
                       href="<?= \App::$urlManager->createUrl('erp/message-category/edit', ['category_id' => $v['category_id']]) ?>">
                        <i class="glyphicon glyphicon-pencil"></i> 编辑
                    </a>
                    <div class="btn btn-danger btn-sm remove-btn" data-id="<?= $v['category_id'] ?>">
                        <i class="glyphicon glyphicon-trash"></i> 删除
                    </div>
                    <?php if ($v['status'] == 1): ?>
                        <div class="btn btn-danger btn-sm set-status-btn" data-id="<?= $v['category_id'] ?>"
                             data-status="0">
                            <i class="glyphicon glyphicon-remove-circle"></i> <span>禁用</span>
                        </div>
                    <?php else: ?>
                        <div class="btn btn-success btn-sm set-status-btn" data-id="<?= $v['category_id'] ?>"
                             data-status="1">
                            <i class="glyphicon glyphicon-ok-circle"></i> <span>启用</span>
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
<?php $this->appendScript('message-category.js') ?>