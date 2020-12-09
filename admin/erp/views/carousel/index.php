<div class="btn-box clearfix">
    <a href="<?= \App::$urlManager->createUrl('erp/carousel/edit') ?>">
        <div class="btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i> 创建</div>
    </a>
</div>
<form class="search-form" action="<?= \App::$urlManager->createUrl('erp/carousel/index') ?>" method="get">
    <div class="form-content">
        <span class="col-form-label search-label">轮播类型</span>
        <select class="form-control search-input" name="carousel_type">
            <option value="">请选择</option>
            <?php foreach ($this->carouselTypeList as $k => $v): ?>
                <option value="<?= $k ?>" <?= $this->params['carousel_type'] == (string)$k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">标题</span>
        <input type="text" class="form-control search-input" name="title" value="<?= $this->params['title'] ?>">
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">链接到</span>
        <select class="form-control search-input" name="link_type">
            <option value="">请选择</option>
            <?php foreach ($this->linkTypeList as $k => $v): ?>
                <option value="<?= $k ?>" <?= $this->params['link_type'] == (string)$k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">是否展示</span>
        <select class="form-control search-input" name="is_show">
            <option value="">请选择</option>
            <?php foreach ($this->isShowList as $k => $v): ?>
                <option value="<?= $k ?>" <?= $this->params['is_show'] == (string)$k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php $searchList = ['carousel_id' => 'ID']; ?>
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
            <th>轮播类型</th>
            <th>标题</th>
            <th>图片</th>
            <th>排序</th>
            <th>链接到</th>
            <th>是否展示</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        <?php foreach ($this->list as $v): ?>
            <tr>
                <td><?= $v['carousel_id'] ?></td>
                <td><?= $this->carouselTypeList[$v['carousel_type']] ?></td>
                <td><?= $v['title'] ?></td>
                <td>
                    <?php if ($v['pic']): ?>
                        <img src="<?= \App::$urlManager->staticUrl($v['pic']) ?>" style="width: 60px;height: 60px;">
                    <?php endif; ?>
                </td>
                <td class="sort"><?= $v['sort'] ?></td>
                <td><?= $this->linkTypeList[$v['link_type']] ?></td>
                <td>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input is-show-btn"
                               id="is-show<?= $v['carousel_id'] ?>"
                               value="<?= $v['carousel_id'] ?>" <?= $v['is_show'] ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="is-show<?= $v['carousel_id'] ?>"></label>
                    </div>
                </td>
                <td><?= date('Y-m-d H:i:s', $v['create_time']) ?></td>
                <td>
                    <a class="btn btn-primary btn-sm"
                       href="<?= \App::$urlManager->createUrl('erp/carousel/edit', ['carousel_id' => $v['carousel_id']]) ?>">
                        <i class="glyphicon glyphicon-pencil"></i> 编辑
                    </a>
                    <div class="btn btn-info btn-sm set-sort-btn" data-id="<?= $v['carousel_id'] ?>">
                        <i class="glyphicon glyphicon-sort"></i> 设置排序
                    </div>
                    <div class="btn btn-danger btn-sm remove-btn" data-carousel_id="<?= $v['carousel_id'] ?>">
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
<?php $this->appendScript('carousel.js') ?>