<div class="btn-box clearfix">
    <a href="<?= \App::$urlManager->createUrl('erp/freight-template/edit') ?>">
        <div class="btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i> 创建</div>
    </a>
</div>
<form class="search-form" action="<?= \App::$urlManager->createUrl('erp/freight-template/index') ?>" method="get">
    <div class="form-content">
        <span class="col-form-label search-label">模版名称</span>
        <input type="text" class="form-control search-input" name="template_name" value="<?= $this->params['template_name'] ?>">
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">计价方式</span>
        <select class="form-control search-input" name="freight_type">
            <option value="">请选择</option>
            <?php foreach ($this->freightTypeList as $k => $v): ?>
                <option value="<?= $k ?>" <?= $this->params['freight_type'] == (string)$k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php $searchList = ['freight_id' => 'ID']; ?>
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
            <th>模版名称</th>
            <th>计价方式</th>
            <th>数量</th>
            <th>起步价</th>
            <th>增加数量</th>
            <th>增加费用</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        <?php foreach ($this->list as $v): ?>
            <tr>
                <td><?= $v['freight_id'] ?></td>
                <td><?= $v['template_name'] ?></td>
                <td><?= $this->freightTypeList[$v['freight_type']] ?></td>
                <td><?= $v['number'] ?></td>
                <td><?= $v['start_price'] ?></td>
                <td><?= $v['step_number'] ?></td>
                <td><?= $v['step_price'] ?></td>
                <td><?= date('Y-m-d H:i:s', $v['create_time']) ?></td>
                <td>
                    <a class="btn btn-primary btn-sm" href="<?= \App::$urlManager->createUrl('erp/freight-template/edit', ['freight_id' => $v['freight_id']]) ?>">
                        <i class="glyphicon glyphicon-pencil"></i> 编辑
                    </a>
                    <div class="btn btn-danger btn-sm remove-btn" data-freight_id="<?= $v['freight_id'] ?>">
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
<?php $this->appendScript('freight-template.js')?>