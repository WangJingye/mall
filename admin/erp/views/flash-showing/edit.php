<form class="form-box col-12 col-sm-8 col-md-6" id="save-form"
      action="<?= \App::$urlManager->createUrl('erp/flash-showing/edit') ?>" method="post">
    <input type="hidden" name="show_id" value="<?= $this->model['show_id'] ?>">
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">开始时间</label>
        <div class="col-sm-8">
            <input type="text" name="start_time" class="form-control" value="<?= $this->model['start_time'] ?>"
                   placeholder="开始时间，格式为12:00">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">结束时间</label>
        <div class="col-sm-8">
            <input type="text" name="end_time" class="form-control" value="<?= $this->model['end_time'] ?>"
                   placeholder="结束时间，格式为14:00">
        </div>
    </div>
    <div class="form-group row">
        <div class="offset-4 col-sm-8">
            <input class="btn btn-primary btn-lg" type="submit" value="保存"/>
        </div>
    </div>
</form>
<?php $this->appendScript('flash-showing.js') ?>