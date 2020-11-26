<form class="form-box col-12 col-sm-8 col-md-6" id="save-form" action="<?= \App::$urlManager->createUrl('erp/search-recommend/edit') ?>" method="post">
    <input type="hidden" name="id" value="<?= $this->model['id'] ?>">
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">标题</label>
        <div class="col-sm-8">
            <input type="text" name="title" class="form-control" value="<?= $this->model['title']?>" placeholder="请输入标题">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">排序</label>
        <div class="col-sm-8">
            <input type="text" name="sort" class="form-control" value="<?= $this->model['sort']??0?>" placeholder="请输入排序">
        </div>
    </div>
    <div class="form-group row">
        <div class="offset-4 col-sm-8">
            <input class="btn btn-primary btn-lg" type="submit" value="保存"/>
        </div>
    </div>
</form>
<?php $this->appendScript('search-recommend.js') ?>