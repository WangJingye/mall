<form class="form-box col-12 col-sm-8 col-md-6" id="save-form"
      action="<?= \App::$urlManager->createUrl('erp/spread/config') ?>" method="post">
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">分销模式</label>
        <div class="col-sm-8">
            <?= \admin\extend\input\SelectInput::instance($this->typeList, isset($this->model['type']) ? $this->model['type'] : 1, 'type')->show(); ?>
            <small class="text-muted">开启人人分销后注册的用户默认是推广员</small>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>分销深度</label>
        <div class="col-sm-8">
            <input type="number" name="depth" class="form-control"
                   value="<?= isset($this->model['depth']) ? $this->model['depth'] : '' ?>"
                   placeholder="请输入分销深度">
        </div>
    </div>
    <?php $depth = isset($this->model['depth']) ? $this->model['depth'] : 0; ?>
    <?php for ($i = 0; $i < $depth; $i++) : ?>
        <div class="form-group row back-money-group">
            <label class="col-sm-4 text-nowrap col-form-label form-label">第<?= $i + 1 ?>级返佣比例</label>
            <div class="col-sm-8">
                <input type="number" name="back[<?= $i ?>]" class="form-control" value="<?= $this->model['back'][$i] ?>"
                       placeholder="请输入返佣比例">
            </div>
        </div>
    <?php endfor; ?>
    <div class="form-group row">
        <div class="offset-4 col-sm-8">
            <input class="btn btn-primary btn-lg" type="submit" value="保存"/>
        </div>
    </div>
</form>
<?php $this->appendScript('spread.js') ?>