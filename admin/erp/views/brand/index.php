<div class="btn-box clearfix">
    <a href="<?= \App::$urlManager->createUrl('erp/brand/edit') ?>">
        <div class="btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i> 创建</div>
    </a>
</div>
<form class="search-form" action="<?= \App::$urlManager->createUrl('erp/brand/index') ?>" method="get">
    <div class="form-content">
        <span class="col-form-label search-label">品牌名称</span>
        <input type="text" class="form-control search-input" name="brand_name" value="<?= $this->params['brand_name'] ?>">
    </div>
    <?php $searchList = ['brand_id' => 'ID']; ?>
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
    <table class="table table-bordered list-table text-nowrap text-center">
        <tbody>
        <tr>
            <th>ID</th>
            <th>品牌名称</th>
            <th>图标</th>
            <th>排序</th>
            <th>操作</th>
        </tr>
        <?php foreach ($this->list as $v): ?>
            <tr>
                <td><?= $v['brand_id'] ?></td>
                <td><?= $v['brand_name'] ?></td>
                <td>
                    <?php if ($v['logo']): ?>
                        <img src="<?= \App::$urlManager->staticUrl($v['logo']) ?>" style="width: 60px;height: 60px;">
                    <?php endif; ?>
                </td>
                <td class="sort"><?= $v['sort'] ?></td>
                <td>
                    <a class="btn btn-primary btn-sm" href="<?= \App::$urlManager->createUrl('erp/brand/edit', ['brand_id' => $v['brand_id']]) ?>">
                        <i class="glyphicon glyphicon-pencil"></i> 编辑
                    </a>
                    <div class="btn btn-info btn-sm set-sort-btn" data-id="<?= $v['brand_id'] ?>">
                        <i class="glyphicon glyphicon-sort"></i> 设置排序
                    </div>
                    <div class="btn btn-danger btn-sm remove-btn" data-brand_id="<?= $v['brand_id'] ?>">
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
<?php $this->appendScript('brand.js')?>