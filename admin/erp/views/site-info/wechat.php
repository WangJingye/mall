<form class="form-box col-12 col-sm-8 col-md-6" id="save-form"
      action="<?= \App::$urlManager->createUrl('erp/site-info/edit') ?>" method="post">
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">微信APPID</label>
        <div class="col-sm-8">
            <input type="text" name="wechat_app_id" class="form-control"
                   value="<?= $this->model['wechat_app_id'] ?>"
                   placeholder="请输入微信APPID">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">微信密钥</label>
        <div class="col-sm-8">
            <input type="text" name="wechat_app_secret" class="form-control"
                   value="<?= $this->model['wechat_app_secret'] ?>" placeholder="请输入微信密钥">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">微信支付商户ID</label>
        <div class="col-sm-8">
            <input type="text" name="wechat_mch_id" class="form-control"
                   value="<?= $this->model['wechat_mch_id'] ?>"
                   placeholder="请输入微信支付商户ID">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">微信支付商户密钥</label>
        <div class="col-sm-8">
            <input type="text" name="wechat_pay_key" class="form-control"
                   value="<?= $this->model['wechat_pay_key'] ?>"
                   placeholder="请输入微信支付商户密钥">
        </div>
    </div>
    <div class="form-group row">
        <div class="offset-4 col-sm-8">
            <input class="btn btn-primary btn-lg" type="submit" value="保存"/>
        </div>
    </div>
</form>
<?php $this->appendScript('site-info.js') ?>