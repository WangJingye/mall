<form class="form-box col-12 col-sm-8 col-md-6" id="save-form"
      action="<?= \App::$urlManager->createUrl('erp/carousel/edit') ?>" method="post">
    <input type="hidden" name="carousel_id" value="<?= $this->model['carousel_id'] ?>">
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>轮播类型</label>
        <div class="col-sm-8">
            <?= \admin\extend\input\SelectInput::instance($this->carouselTypeList, $this->model['carousel_type'], 'carousel_type', 'select')->show(); ?>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>标题</label>
        <div class="col-sm-8">
            <input type="text" name="title" class="form-control" value="<?= $this->model['title'] ?>"
                   placeholder="请输入标题">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>图片</label>
        <div class="col-sm-8">
            <?= \admin\extend\image\ImageInput::instance($this->model['pic'], 'pic')->show(); ?>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>排序</label>
        <div class="col-sm-8">
            <input type="number" name="sort" class="form-control"
                   value="<?= $this->model['sort'] ? $this->model['sort'] : '0' ?>" placeholder="请输入排序">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label"><span style="color: red">*</span>链接到</label>
        <div class="col-sm-8">
            <?= \admin\extend\input\SelectInput::instance($this->linkTypeList, $this->model['link_type'], 'link_type', 'select')->show(); ?>
        </div>
    </div>
    <div class="form-group row link-type-form"
         data-id="1"<?= $this->model['link_type'] != 1 ? ' style="display: none"' : '' ?>>
        <label class="col-sm-4 text-nowrap col-form-label form-label">链接详情</label>
        <div class="col-sm-8">
            <div class="btn btn-outline-danger search-product-btn"><i
                        class="glyphicon glyphicon-plus"></i> 添加
            </div>
            <table class="table table-bordered text-nowrap search-product-box" style="margin-top: 0.5rem">
                <tr class="search-product-title">
                    <td>商品ID</td>
                    <td>商品名称</td>
                    <td>主图</td>
                    <td>操作</td>
                </tr>
                <?php if ($this->product): ?>
                    <tr class="search-product-data">
                        <td><?= $this->product['product_id'] ?></td>
                        <td><input type="hidden" class="link_id" value="<?=$this->product['product_id']?>"><?= $this->product['product_name'] ?></td>
                        <td>
                            <?php if ($this->product['pic']): ?>
                                <img src="<?= \App::$urlManager->staticUrl($this->product['pic']) ?>" style="width: 40px;height: 40px;">
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn btn-sm btn-danger search-product-btn">修改</div>
                        </td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
    <div class="form-group row link-type-form"
         data-id="2"<?= $this->model['link_type'] != 2 ? ' style="display: none"' : '' ?>>
        <label class="col-sm-4 text-nowrap col-form-label form-label">链接详情</label>
        <div class="col-sm-8">
            <?= \admin\extend\input\SelectInput::instance(array_column($this->categoryList, 'name', 'id'), $this->model['link_id'], null, 'select2')->show(); ?>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-4 text-nowrap col-form-label form-label">是否展示</label>
        <div class="col-sm-8">
            <?= \admin\extend\input\SelectInput::instance($this->isShowList, isset($this->model['is_show']) ? $this->model['is_show'] : 1, 'is_show', 'radio')->show(); ?>
        </div>
    </div>
    <div class="form-group row">
        <div class="offset-4 col-sm-8">
            <input class="btn btn-primary btn-lg" type="submit" value="保存"/>
        </div>
    </div>
</form>

<?php $this->appendScript('product-search.js')->appendScript('carousel.js') ?>