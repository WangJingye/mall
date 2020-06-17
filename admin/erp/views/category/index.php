<div class="btn-box clearfix">
    <a href="<?= \App::$urlManager->createUrl('erp/category/edit') ?>">
        <div class="btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i> 创建</div>
    </a>
</div>
<form class="search-form" action="<?= \App::$urlManager->createUrl('erp/category/index') ?>" method="get">
    <div class="form-content">
        <span class="col-form-label search-label">分类名称</span>
        <input type="text" class="form-control search-input" name="category_name" value="<?= $this->params['category_name'] ?>">
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">是否有下级</span>
        <select class="form-control search-input" name="has_child">
            <option value="">请选择</option>
            <?php foreach ($this->hasChildList as $k => $v): ?>
                <option value="<?= $k ?>" <?= $this->params['has_child'] == (string)$k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
        </select>
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
    <table class="table table-bordered list-table text-nowrap text-center">
        <tbody>
        <tr>
            <th>分类名称</th>
            <th>图片</th>
            <th>分类级别</th>
            <th>是否有下级</th>
            <th>操作</th>
        </tr>
        <?php foreach ($this->list as $v): ?>
            <tr>
                <td class="category-name <?= $v['has_child'] ? 'can-click' : '' ?>" data-id="<?= $v['category_id'] ?>"
                    data-url="<?= \App::$urlManager->createUrl('erp/category/get-child-list') ?>">
                    <?php if($v['has_child']):?><span class="has-child-icon"><i class="glyphicon glyphicon-triangle-right"></i></span><?php endif;?>
                    <span style="color: #CCC"><?= str_pad('', ($v['level'] - 1) * 2, '-') ?></span>
                    <?= $v['category_name'] ?>
                </td>
                <td>
                    <?php if ($v['pic']): ?>
                        <img src="<?= $v['pic'] ?>" style="width: 40px;height: 40px;">
                    <?php endif; ?>
                </td>
                <td><?= $v['level'] ?></td>
                <td><?= $this->hasChildList[$v['has_child']] ?></td>
                <td>
                    <a class="btn btn-primary btn-sm"
                       href="<?= \App::$urlManager->createUrl('erp/category/edit', ['category_id' => $v['category_id']]) ?>">
                        <i class="glyphicon glyphicon-pencil"></i> 编辑
                    </a>
                    <?php if ($v['has_child']): ?>
                        <a class="btn btn-outline-success btn-sm"
                           href="<?= \App::$urlManager->createUrl('erp/category/edit', ['add_type' => 'add_new', 'parent_id' => $v['category_id']]) ?>">
                            <i class="glyphicon glyphicon-plus"></i> 添加下级
                        </a>
                    <?php endif; ?>
                    <div class="btn btn-danger btn-sm remove-btn" data-category_id="<?= $v['category_id'] ?>">
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
<?php $this->appendScript('category.js') ?>