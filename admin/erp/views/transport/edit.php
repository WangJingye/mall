<form class="form-box col-12 col-sm-8 col-md-6" id="save-form" action="<?= \App::$urlManager->createUrl('erp/transport/edit') ?>" method="post">
    <input type="hidden" name="transport_id" value="<?= $this->model['transport_id'] ?>">
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>物流方式名称</label>
        <div class="col-sm-8">
            <input type="text" name="transport_name" class="form-control" value="<?= $this->model['transport_name']?>" placeholder="请输入物流方式名称">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">备注</label>
        <div class="col-sm-8">
            <input type="text" name="remark" class="form-control" value="<?= $this->model['remark']?>" placeholder="请输入备注">
        </div>
    </div>
    <div class="form-group row">
        <div class="offset-4 col-sm-8">
            <input class="btn btn-primary btn-lg" type="submit" value="保存"/>
        </div>
    </div>
</form>
<?php $this->appendScript('transport.js') ?>