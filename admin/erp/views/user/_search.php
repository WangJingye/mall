<div class="modal-event-body">
    <div class="table-responsive">
        <div class="search-form">
            <input type="hidden" name="multiple" value="<?= $this->params['multiple'] ?>">
            <input type="hidden" name="level" value="<?= $this->params['level'] ?>">
            <input type="hidden" name="page" value="<?= $this->params['page'] ?>">
            <div class="form-content">
                <span class="col-form-label search-label">昵称</span>
                <input type="text" class="form-control search-input" name="nickname"
                       value="<?= $this->params['nickname'] ?>">
            </div>
            <div class="form-content">
                <span class="col-form-label search-label">手机号</span>
                <input type="text" class="form-control search-input" name="telephone"
                       value="<?= $this->params['telephone'] ?>">
            </div>
            <?php $searchList = ['user_id' => '会员ID']; ?>
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
                    <div class="btn btn-primary user-search-btn search-btn text-nowrap"><i
                                class="glyphicon glyphicon-search"></i> 搜索
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered list-table text-nowrap text-center">
                <tbody>
                <tr>
                    <th><?php if ($this->params['multiple']): ?>
                            <input type="checkbox" class="check-all">
                        <?php endif; ?>
                    </th>
                    <th>会员ID</th>
                    <th>昵称</th>
                    <th>头像</th>
                    <th>手机号</th>
                </tr>

                <?php foreach ($this->list as $v): ?>
                    <tr class="user-search-tr" style="cursor: pointer">
                        <td><input type="checkbox" class="check-one" name="ids" value="<?= $v['user_id'] ?>"
                                   data-info='<?= json_encode($v, JSON_HEX_APOS) ?>'></td>
                        <td><?= $v['user_id'] ?></td>
                        <td class="nickname"><?= $v['nickname'] ?></td>
                        <td>
                            <?php if ($v['avatar']): ?>
                                <img src="<?= \App::$urlManager->staticUrl($v['avatar']) ?>"
                                     style="width: 40px;height: 40px;">
                            <?php endif; ?>
                        </td>
                        <td><?= $v['telephone'] ?></td>
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
    </div>
</div>
<?php if (count($this->list)): ?>
    <div class="modal-event-footer modal-event-search-footer">
        <div>
            <?php if ($this->params['multiple']): ?>
                <div class="btn btn-outline-success modal-event-confirm-multiple">添加并继续</div>
            <?php else: ?>
                <div class="btn btn-outline-success modal-event-confirm-one">添加</div>
            <?php endif; ?>
            <div class="btn btn-outline-primary modal-event-close">关闭</div>
        </div>
        <div>
            <?= $this->pagination ?>
        </div>
    </div>
<?php endif; ?>