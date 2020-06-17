<form class="form-box col-12 col-sm-8 col-md-6" id="save-form"
      action="<?= \App::$urlManager->createUrl('erp/brand/edit') ?>" method="post">
    <input type="hidden" name="brand_id" value="<?= $this->model['brand_id'] ?>">
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">品牌名称</label>
        <div class="col-sm-8">
            <input type="text" name="brand_name" class="form-control" value="<?= $this->model['brand_name'] ?>"
                   placeholder="请输入品牌名称">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">图标</label>
        <div class="col-sm-8">
            <?= \admin\extend\image\ImageInput::instance($this->model['logo'], 'logo')->show(); ?>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">排序</label>
        <div class="col-sm-8">
            <input type="text" name="sort" class="form-control"
                   value="<?= $this->model['sort'] ? $this->model['sort'] : '0' ?>" placeholder="请输入排序">
        </div>
    </div>
    <div class="form-group row">
        <div class="offset-4 col-sm-8">
            <input class="btn btn-primary btn-lg" type="submit" value="保存"/>
        </div>
    </div>
</form>
<?php $this->appendScript('brand.js') ?>