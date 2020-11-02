<form class="form-box col-12 col-sm-8 col-md-6" id="save-form"
      action="<?= \App::$urlManager->createUrl('erp/category/edit') ?>" method="post">
    <input type="hidden" name="category_id" value="<?= $this->model['category_id'] ?>">
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>分类名称</label>
        <div class="col-sm-8">
            <input type="text" name="category_name" class="form-control" value="<?= $this->model['category_name'] ?>"
                   placeholder="请输入分类名称">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>父级分类</label>
        <div class="col-sm-8">
            <?php if (empty($this->add_type)): ?>
                <?= \admin\extend\input\SelectInput::instance($this->childList, $this->model['parent_id'], 'parent_id', 'select2')->show(); ?>
            <?php else: ?>
                <input type="text" class="form-control" value="<?= $this->childList[$this->pid] ?>" disabled>
                <input type="hidden" name="parent_id" value="<?= $this->pid ?>">
            <?php endif; ?>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">图片</label>
        <div class="col-sm-8">
            <?= \admin\extend\image\ImageInput::instance($this->model['pic'], 'pic')->show(); ?>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>是否有下级</label>
        <div class="col-sm-8">
            <?= \admin\extend\input\SelectInput::instance($this->boolList, $this->model['has_child'], 'has_child', 'radio')->show(); ?>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>显示到首页</label>
        <div class="col-sm-8">
            <?= \admin\extend\input\SelectInput::instance($this->boolList, $this->model['show_home'], 'show_home', 'radio')->show(); ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="offset-4 col-sm-8">
            <input class="btn btn-primary btn-lg" type="submit" value="保存"/>
        </div>
    </div>
</form>
<?php $this->appendScript('category.js') ?>