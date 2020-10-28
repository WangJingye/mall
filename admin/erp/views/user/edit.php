<form class="form-box col-12 col-sm-8 col-md-6" id="save-form"
      action="<?= \App::$urlManager->createUrl('erp/user/edit') ?>" method="post">
    <input type="hidden" name="user_id" value="<?= $this->model['user_id'] ?>">
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>昵称</label>
        <div class="col-sm-8">
            <input type="text" name="nickname" class="form-control" value="<?= $this->model['nickname'] ?>"
                   placeholder="请输入用户昵称">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>姓名</label>
        <div class="col-sm-8">
            <input type="text" name="realname" class="form-control" value="<?= $this->model['realname'] ?>"
                   placeholder="请输入姓名">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>城市</label>
        <div class="col-sm-8">
            <input type="text" name="city" class="form-control" value="<?= $this->model['city'] ?>"
                   placeholder="请输入城市">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">头像</label>
        <div class="col-sm-8">
            <?= \admin\extend\image\ImageInput::instance($this->model['avatar'], 'avatar')->show(); ?>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">性别</label>
        <div class="col-sm-8">
            <?= \admin\extend\input\SelectInput::instance($this->genderList, $this->model['gender'], 'gender')->show() ?>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">推广员</label>
        <div class="col-sm-8">
            <?= \admin\extend\input\SelectInput::instance([1 => '是', 0 => '否'], $this->model['is_promoter'], 'is_promoter')->show() ?>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>手机号</label>
        <div class="col-sm-8">
            <input type="tel" name="telephone" class="form-control" value="<?= $this->model['telephone'] ?>"
                   placeholder="请输入手机号">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>生日</label>
        <div class="col-sm-8">
            <input type="date" name="birthday" class="form-control" value="<?= $this->model['birthday'] ?>"
                   placeholder="请输入生日">
        </div>
    </div>
    <div class="form-group row">
        <div class="offset-4 col-sm-8">
            <input class="btn btn-primary btn-lg" type="submit" value="保存"/>
        </div>
    </div>
</form>
<?php $this->appendScript('user.js') ?>