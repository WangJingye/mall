<form class="form-box col-12 col-sm-8 col-md-6" id="save-form" action="<?= \App::$urlManager->createUrl('erp/message-category/edit') ?>" method="post">
    <input type="hidden" name="category_id" value="<?= $this->model['category_id'] ?>">
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">分类名称</label>
        <div class="col-sm-8">
            <input type="text" name="category_name" class="form-control" value="<?= $this->model['category_name']?>" placeholder="请输入分类名称">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">图标</label>
        <div class="col-sm-8">
            <?= \admin\extend\image\ImageInput::instance($this->model['pic'], 'pic', 1)->show(); ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="offset-4 col-sm-8">
            <input class="btn btn-primary btn-lg" type="submit" value="保存"/>
        </div>
    </div>
</form>
<?php $this->appendScript('message-category.js') ?>