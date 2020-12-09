<form class="form-box col-12 col-sm-8 col-md-6" id="save-form"
      action="<?= \App::$urlManager->createUrl('erp/coupon-user/edit') ?>" method="post">
    <input type="hidden" name="id" value="<?= $this->model['id'] ?>">
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">用户</label>
        <div class="col-sm-8">
            <input type="hidden" name="user_id" value="<?= $this->model['user_id'] ?>">
            <input type="text" class="form-control search-user" readonly value="<?= $this->user['nickname'] ?>"
                   placeholder="点击选择用户" style="display: inline">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>优惠券</label>
        <div class="col-sm-8">
            <?= \admin\extend\input\SelectInput::instance($this->couponList, $this->model['coupon_id'], 'coupon_id', 'select2')->show(); ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="offset-4 col-sm-8">
            <input class="btn btn-primary btn-lg" type="submit" value="保存"/>
        </div>
    </div>
</form>
<?php $this->appendScript('user-search.js')->appendScript('coupon-user.js') ?>