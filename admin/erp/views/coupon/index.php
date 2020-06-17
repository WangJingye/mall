<div class="btn-box clearfix">
    <a href="<?= \App::$urlManager->createUrl('erp/coupon/edit') ?>">
        <div class="btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i> 创建</div>
    </a>
</div>
<form class="search-form" action="<?= \App::$urlManager->createUrl('erp/coupon/index') ?>" method="get">
    <div class="form-content">
        <span class="col-form-label search-label">标题</span>
        <input type="text" class="form-control search-input" name="title" value="<?= $this->params['title'] ?>">
    </div>
    <div class="form-content">
        <span class="col-form-label search-label">类型</span>
        <select class="form-control search-input" name="type">
            <option value="">请选择</option>
            <?php foreach ($this->typeList as $k => $v): ?>
                <option value="<?= $k ?>" <?= $this->params['type'] == $k ? 'selected' : '' ?>><?= $v ?></option>
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
    <?php $searchList = ['coupon_id' => 'ID']; ?>
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
            <th>类型</th>
            <th>面值</th>
            <th>所需积分</th>
            <th>最小使用价格</th>
            <th>有效期</th>
            <th>状态</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        <?php foreach ($this->list as $v): ?>
            <tr>
                <td><?= $v['coupon_id'] ?></td>
                <td><?= $v['title'] ?></td>
                <td><?= $this->typeList[$v['type']] ?></td>
                <td><?= $v['price'] ?></td>
                <td><?= $v['points'] ?></td>
                <td><?= $v['min_price'] ?></td>
                <td><?= showTime($v['expire']) ?></td>
                <td class="status"><?= $this->statusList[$v['status']] ?></td>
                <td><?= date('Y-m-d H:i:s', $v['create_time']) ?></td>
                <td>
                    <a class="btn btn-primary btn-sm"
                       href="<?= \App::$urlManager->createUrl('erp/coupon/edit', ['coupon_id' => $v['coupon_id']]) ?>">
                        <i class="glyphicon glyphicon-pencil"></i> 编辑
                    </a>
                    <div class="btn btn-danger btn-sm remove-btn" data-coupon_id="<?= $v['coupon_id'] ?>">
                        <i class="glyphicon glyphicon-trash"></i> 删除
                    </div>
                    <?php if ($v['status'] == 1): ?>
                        <div class="btn btn-danger btn-sm set-status-btn" data-id="<?= $v['coupon_id'] ?>"
                             data-url="<?= \App::$urlManager->createUrl('erp/coupon/set-status') ?>"
                             data-status="2">
                            <i class="glyphicon glyphicon-remove-circle"></i> <span>禁用</span>
                        </div>
                    <?php else: ?>
                        <div class="btn btn-success btn-sm set-status-btn" data-id="<?= $v['coupon_id'] ?>"
                             data-url="<?= \App::$urlManager->createUrl('erp/coupon/set-status') ?>"
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
<?php $this->appendScript('coupon.js') ?>
<?php function showTime($time)
{
    $time = $time * 60;
    $days = (int)floor($time / (24 * 3600));
    $time = $time - $days * 24 * 3600;
    $hours = (int)floor($time / 3600);
    $minutes = ($time - $hours * 3600) / 60;
    $res = $days > 0 ? $days . '天' : '';
    $res .= $hours > 0 ? $hours . '小时' : '';
    $res .= $minutes > 0 ? $minutes . '分钟' : '';
    if ($res == '') {
        return '0分钟';
    }
    return $res;
}
