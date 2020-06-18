<form class="form-box col-12 col-sm-8 col-md-6" id="save-form" action="<?= \App::$urlManager->createUrl('erp/cash-out/edit') ?>" method="post">
    <input type="hidden" name="id" value="<?= $this->model['id'] ?>">
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">用户</label>
        <div class="col-sm-8">
            <input type="text" name="user_id" class="form-control" value="<?= $this->model['user_id']?>" placeholder="请输入用户">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">提现金额</label>
        <div class="col-sm-8">
            <input type="text" name="amount" class="form-control" value="<?= $this->model['amount']?>" placeholder="请输入提现金额">
        </div>
    </div>
    <div class="form-group row">
        <div class="offset-4 col-sm-8">
            <input class="btn btn-primary btn-lg" type="submit" value="保存"/>
        </div>
    </div>
</form>
<?php $this->appendScript('cash-out.js') ?>