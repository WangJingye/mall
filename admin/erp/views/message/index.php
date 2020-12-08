<div class="btn-box clearfix">
    <a href="<?= \App::$urlManager->createUrl('erp/message/edit') ?>">
        <div class="btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i> 创建</div>
    </a>
</div>
<form class="search-form" action="<?= \App::$urlManager->createUrl('erp/message/index') ?>" method="get">
    <div class="form-content">
        <span class="col-form-label search-label">消息分类</span>
        <select class="form-control search-input" name="category_id">
            <option value="">请选择</option>
            <?php foreach ($this->categoryList as $k => $v): ?>
                <option value="<?= $k ?>" <?= $this->params['category_id'] == (string)$k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">用户</span>
        <input type="text" class="form-control search-input" name="user_id" value="<?= $this->params['user_id'] ?>">
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
            <th>ID</th>
            <th>消息分类</th>
            <th>用户</th>
            <th>内容</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        <?php foreach ($this->list as $v): ?>
            <tr>
                <td><?= $v['id'] ?></td>
                <td><?= $this->categoryList[$v['category_id']] ?></td>
                <td><?= $v['user_id'] ?></td>
                <td><?= $v['content'] ?></td>
                <td><?= date('Y-m-d H:i:s', $v['create_time']) ?></td>
                <td>
                    <a class="btn btn-primary btn-sm"
                       href="<?= \App::$urlManager->createUrl('erp/message/edit', ['id' => $v['id']]) ?>">
                        <i class="glyphicon glyphicon-pencil"></i> 编辑
                    </a>
                    <div class="btn btn-danger btn-sm remove-btn" data-id="<?= $v['id'] ?>">
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
<?php $this->appendScript('message.js') ?>