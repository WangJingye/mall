<tr class="ajaxDropDownView" data-id="<?=$this->id?>">
    <td colspan="100">
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#operation-log">操作日志</a></li>
        </ul>
        <div class="tab-content" style="border:1px solid #dee2e6;border-top: none">
            <div class="tab-pane fade show active" role="tabpanel" id="operation-log">
                <table class="table table-bordered">
                    <tr>
                        <td>操作</td>
                        <td>操作人</td>
                        <td>操作时间</td>
                    </tr>
                    <?php foreach ($this->list as $v): ?>
                        <?php $name = isset($this->userList[$v['user_type']][$v['create_userid']]) ? $this->userList[$v['user_type']][$v['create_userid']] : ''; ?>
                        <tr>
                            <td><?= $v['detail'] ?></td>
                            <td><?= $name ?></td>
                            <td><?= date('Y-m-d H:i:s', $v['create_time']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>

    </td>
</tr>
