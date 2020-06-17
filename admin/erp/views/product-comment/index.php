<div class="btn-box clearfix">
    <div class="btn btn-success pull-right share-all-btn"><i class="glyphicon glyphicon-share-alt"></i> 批量回复</div>
</div>
<form class="search-form" action="<?= \App::$urlManager->createUrl('erp/product-comment/index') ?>" method="get">
    <div class="form-content">
        <span class="col-form-label search-label">评价</span>
        <select class="form-control search-input" name="star">
            <option value="">请选择</option>
            <?php foreach ($this->starList as $k => $v): ?>
                <option value="<?= $k ?>" <?= $this->params['star'] == (string)$k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">是否显示</span>
        <select class="form-control search-input" name="is_show">
            <option value="">请选择</option>
            <?php foreach ($this->isShowList as $k => $v): ?>
                <option value="<?= $k ?>" <?= $this->params['is_show'] == (string)$k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">回复状态</span>
        <select class="form-control search-input" name="status">
            <option value="">请选择</option>
            <?php foreach ($this->statusList as $k => $v): ?>
                <option value="<?= $k ?>" <?= $this->params['status'] == (string)$k ? 'selected' : '' ?>><?= $v ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php $searchList = ['comment_id' => 'ID']; ?>
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
            <th><input type="checkbox" class="check-all"></th>
            <th>ID</th>
            <th>用户昵称</th>
            <th>商品名称</th>
            <th>评价</th>
            <th>是否显示</th>
            <th>评论时间</th>
            <th>操作</th>
        </tr>
        <?php foreach ($this->list as $v): ?>
            <tr>
                <td>
                    <input type="checkbox" class="check-one" data-status="<?= $v['status'] ?>"
                           value="<?= $v['comment_id'] ?>">
                </td>
                <td><?= $v['comment_id'] ?></td>
                <td><?= $this->operatorList[$v['user_type']][$v['user_id']] ?></td>
                <td><?= $this->productList[$v['product_id']] ?></td>
                <td><?= $this->starList[$v['star']] ?></td>
                <td>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input is-show-btn"
                               id="is-show<?= $v['comment_id'] ?>"
                               value="<?= $v['comment_id'] ?>" <?= $v['is_show'] ? 'checked' : '' ?>>
                        <label class="custom-control-label" for="is-show<?= $v['comment_id'] ?>"></label>
                    </div>
                </td>
                <td><?= date('Y-m-d H:i:s', $v['create_time']) ?></td>
                <td>
                    <?php if ($v['status'] == 1): ?>
                        <div class="btn btn-success btn-sm share-btn" data-id="<?= $v['comment_id'] ?>">
                            <i class="glyphicon glyphicon-share-alt"></i> 回复
                        </div>
                    <?php endif; ?>
                    <div class="btn btn-danger btn-sm remove-btn" data-comment_id="<?= $v['comment_id'] ?>">
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
<?php $this->appendScript('product-comment.js') ?>