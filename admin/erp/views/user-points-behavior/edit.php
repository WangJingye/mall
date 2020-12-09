<form class="form-box col-12 col-sm-8 col-md-6" id="save-form"
      action="<?= \App::$urlManager->createUrl('erp/user-points-behavior/edit') ?>" method="post">
    <input type="hidden" name="behavior_id" value="<?= $this->model['behavior_id'] ?>">
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">行为名称</label>
        <div class="col-sm-8">
            <input type="text" name="behavior_name" class="form-control" value="<?= $this->model['behavior_name'] ?>"
                   placeholder="请输入行为名称">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">行为类型</label>
        <div class="col-sm-8">
            <?= \admin\extend\input\SelectInput::instance($this->typeList, $this->model['type'], 'type', 'select')->show(); ?>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">行为地址</label>
        <div class="col-sm-8">
            <input type="text" name="url" class="form-control" value="<?= $this->model['url'] ?>" placeholder="请输入行为地址">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">积分</label>
        <div class="col-sm-8">
            <input type="text" name="points" class="form-control" value="<?= $this->model['points'] ?>"
                   placeholder="请输入积分">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">次数</label>
        <div class="col-sm-8">
            <input type="text" name="number" class="form-control" value="<?= $this->model['number'] ?>"
                   placeholder="请输入次数">
        </div>
    </div>
    <div class="form-group row">
        <div class="offset-4 col-sm-8">
            <input class="btn btn-primary btn-lg" type="submit" value="保存"/>
        </div>
    </div>
</form>
<?php $this->appendScript('user-points-behavior.js') ?>