<form class="form-box col-12 col-sm-8 col-md-6" id="save-form"
      action="<?= \App::$urlManager->createUrl('erp/freight-template/edit') ?>" method="post">
    <input type="hidden" name="freight_id" value="<?= $this->model['freight_id'] ?>">
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">模版名称</label>
        <div class="col-sm-8">
            <input type="text" name="template_name" class="form-control" value="<?= $this->model['template_name'] ?>"
                   placeholder="请输入模版名称">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">计价方式</label>
        <div class="col-sm-8">
            <?= \admin\extend\input\SelectInput::instance($this->freightTypeList, $this->model['freight_type'], 'freight_type', 'radio')->show(); ?>
            <small class="text-muted">
                按重量计费时，数量单位克(g)
            </small>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">数量</label>
        <div class="col-sm-8">
            <input type="number" name="number" class="form-control" value="<?= $this->model['number'] ?>"
                   placeholder="请输入数量">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">起步价</label>
        <div class="col-sm-8">
            <input type="number" name="start_price" class="form-control" value="<?= $this->model['start_price'] ?>"
                   placeholder="请输入起步价">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">增加数量</label>
        <div class="col-sm-8">
            <input type="number" name="step_number" class="form-control" value="<?= $this->model['step_number'] ?>"
                   placeholder="请输入增加数量">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">增加费用</label>
        <div class="col-sm-8">
            <input type="number" name="step_price" class="form-control" value="<?= $this->model['step_price'] ?>"
                   placeholder="请输入增加费用">
        </div>
    </div>
    <div class="form-group row">
        <div class="offset-4 col-sm-8">
            <input class="btn btn-primary btn-lg" type="submit" value="保存"/>
        </div>
    </div>
</form>
<?php $this->appendScript('freight-template.js') ?>