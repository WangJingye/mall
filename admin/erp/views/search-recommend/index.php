<div class="btn-box clearfix">
    <a href="<?= \App::$urlManager->createUrl('erp/search-recommend/edit') ?>">
        <div class="btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i> 创建</div>
    </a>
</div>
<form class="search-form" action="<?= \App::$urlManager->createUrl('erp/search-recommend/index') ?>" method="get">
    <div class="form-content">
        <span class="col-form-label search-label">ID</span>
        <input type="text" class="form-control search-input" name="id" value="<?= $this->params['id'] ?>">
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">标题</span>
        <input type="text" class="form-control search-input" name="title" value="<?= $this->params['title'] ?>">
    </div>
    <?php $searchList = []; ?>
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
            <th>标题</th>
            <th>排序</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        <?php foreach ($this->list as $v): ?>
            <tr>
                <td><?= $v['id'] ?></td>
                <td><?= $v['title'] ?></td>
                <td class="sort"><?= $v['sort'] ?></td>
                <td><?= date('Y-m-d H:i:s', $v['create_time']) ?></td>
                <td>
                    <a class="btn btn-primary btn-sm"
                       href="<?= \App::$urlManager->createUrl('erp/search-recommend/edit', ['id' => $v['id']]) ?>">
                        <i class="glyphicon glyphicon-pencil"></i> 编辑
                    </a>
                    <div class="btn btn-danger btn-sm remove-btn" data-id="<?= $v['id'] ?>">
                        <i class="glyphicon glyphicon-trash"></i> 删除
                    </div>
                    <div class="btn btn-info btn-sm set-sort-btn" data-id="<?= $v['id'] ?>">
                        <i class="glyphicon glyphicon-sort"></i> 设置排序
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
<?php $this->appendScript('search-recommend.js') ?>