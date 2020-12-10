<style>
    .publish-preview {
        border: 1px solid #eeeef3;
        border-radius: 5px;
    }

    .publish-title {
        padding: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .title-left {
        align-items: center;
        display: flex;
    }

    .publish-title-icon {
        height: 20px;
        border-radius: 10px;
        width: 20px;
    }

    .publish-title-text {
        margin-left: 10px;
    }

    .publish-jumbotron {
        position: relative;
        border-bottom: 1px solid #eeeef3;
    }

    .jumbotron-image {
        width: 100%;
    }

    .jumbotron-text {
        position: absolute;
        bottom: 10px;
        left: 10px;
        color: #fff;
    }

    .publish-child {
        display: flex;
        justify-content: space-between;
        padding: 10px;
        border-bottom: 1px solid #eeeef3;
    }

    .publish-child:last-child {
        border-bottom: none;
    }

    .child-image {
        width: 40px;
        height: 40px;
    }
</style>
<div class="btn-box clearfix">
    <a href="<?= \App::$urlManager->createUrl('erp/message-activity-child/edit') ?>">
        <div class="btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i> 创建</div>
    </a>
    <div class="btn btn-success pull-right share-all-btn"><i class="glyphicon glyphicon-share-alt"></i> 发布内容</div>
</div>
<form class="search-form" action="<?= \App::$urlManager->createUrl('erp/message-activity-child/index') ?>" method="get">
    <input type="hidden" name="activity_id" value="<?= $this->params['activity_id'] ?>">
    <div class="form-content">
        <span class="col-form-label search-label">所属活动</span>
        <select class="form-control search-input" name="activity_id">
            <option value="">请选择</option>
            <?php foreach ($this->activityList as $k => $v): ?>
                <option value="<?= $v ?>" <?= $this->params['activity_id'] == (string)$k ? 'selected' : '' ?>><?= $v['title'] ?></option>
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
            <th>所属活动</th>
            <th>内容</th>
            <th>图片</th>
            <th>链接到</th>
            <th>链接内容</th>
            <th>状态</th>
            <th>排序</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        <?php foreach ($this->list as $v): ?>
            <tr>
                <td>
                    <input type="checkbox" class="check-one" data-p="<?= $v['activity_id'] ?>"
                           data-status="<?= $v['status'] ?>" value="<?= $v['id'] ?>">
                </td>
                <td><?= $v['id'] ?></td>
                <td><?= $this->activityList[$v['activity_id']]['title'] ?></td>
                <td class="content"><?= $v['title'] ?></td>
                <td>
                    <?php if ($v['pic']): ?>
                        <img class="pic" src="<?= $v['pic'] ?>" style="width: 60px;height: 60px;">
                    <?php endif; ?>
                </td>
                <td><?= $this->linkTypeList[$v['link_type']] ?></td>
                <td><?= $v['link'] ?></td>
                <td><?= $this->statusList[$v['status']] ?></td>
                <td class="sort"><?= $v['sort'] ?></td>
                <td><?= date('Y-m-d H:i:s', $v['create_time']) ?></td>
                <td>
                    <a class="btn btn-primary btn-sm"
                       href="<?= \App::$urlManager->createUrl('erp/message-activity-child/edit', ['id' => $v['id']]) ?>">
                        <i class="glyphicon glyphicon-pencil"></i> 编辑
                    </a>
                    <div class="btn btn-info btn-sm set-sort-btn" data-id="<?= $v['id'] ?>">
                        <i class="glyphicon glyphicon-sort"></i> 设置排序
                    </div>
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
<script>
    var activityList = <?=json_encode($this->activityList)?>;
</script>
<?php $this->appendScript('message-activity-child.js') ?>