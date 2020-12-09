<form class="form-box col-12 col-sm-8 col-md-6" id="save-form"
      action="<?= \App::$urlManager->createUrl('erp/product-comment/edit') ?>" method="post">
    <input type="hidden" name="comment_id" value="<?= $this->model['comment_id'] ?>">
    <div class="form-group row">
        <div class="offset-4 col-sm-8">
            <input class="btn btn-primary btn-lg" type="submit" value="保存"/>
        </div>
    </div>
</form>
<?php $this->appendScript('product-comment.js') ?>