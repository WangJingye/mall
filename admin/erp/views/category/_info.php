<?php foreach ($this->list as $v): ?>
    <tr class="ajaxDropDownView" data-id="<?= $this->pid ?>">
        <td class="category-name <?= $v['has_child'] ? 'can-click' : '' ?>" data-id="<?= $v['category_id'] ?>">
            <?php if ($v['has_child']): ?><span class="has-child-icon"><i
                        class="glyphicon glyphicon-triangle-right"></i></span><?php endif; ?>
            <span style="color: #CCC"><?= str_pad('', ($v['level'] - 1) * 2, '-') ?></span>
            <?= $v['category_name'] ?>
        </td>
        <td>
            <?php if ($v['pic']): ?>
                <img src="<?= \App::$urlManager->staticUrl($v['pic']) ?>" style="width: 40px;height: 40px;">
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

