$(function () {
    $('#save-form').validate({
        rules: {
            title: {
                required: true
            },
            product_id: {
                required: true
            },
            variation_code: {
                required: true
            },
            product_price: {
                required: '请输入原价'
            },
            price: {
                required: true
            },
            start_time: {
                required: true
            },
            end_time: {
                required: true
            }
        },
        messages: {
            title: {
                required: '请输入标题'
            },
            product_name: {
                required: '请选择商品'
            },
            variation_code: {
                required: '请选择sku'
            },
            price: {
                required: '请输入秒杀价'
            }, product_price: {
                required: '请输入原价'
            },
            start_time: {
                required: '请输入开始时间'
            },
            end_time: {
                required: '请输入结束时间'
            }
        },
        submitHandler: function (e) {
            saveForm();
            return false;
        }
    });

    $('.remove-btn').click(function () {
        if (!confirm('是否删除此记录？')) {
            return false;
        }
        let $this = $(this);
        let args = {
            id: $(this).data('id'),
        };
        $.loading('show');
        $.post('/erp/flash-sale/delete', args, function (res) {
            $.loading('hide');
            if (res.code == 200) {
                $.success(res.message);
                $this.parents('tr').remove();
            } else {
                $.error(res.message);
            }
        }, 'json');
    });
    $('.search-product').click(function () {
        var $this = $(this);
        getProductSearch({
            multiple: false,
            callback: function (data) {
                var info = data['info'];
                $this.val(info['product_name']);
                $this.parent().find('input[name=product_id]').val(info['product_id']);
                if (!$this.parent().find('.search-clear-btn').get(0)) {
                    $this.after('<span class="search-clear-btn"><i class="glyphicon glyphicon-remove-circle"></i></span>');
                }
            }
        });
    });
    $('.form-search-product').click(function () {
        var $this = $(this);
        getProductSearch({
            multiple: false,
            callback: function (data) {
                var info = data['info'];
                $this.val(info['product_name']);
                $this.parent().find('input[name=product_id]').val(info['product_id']);
                $('input[name=title]').val(info['product_name'])
            }
        });
    });
    $('.form-search-product-variation').click(function () {
        var $this = $(this);
        var productId = $('input[name=product_id]').val();
        if (!productId.length) {
            $.error('请选择商品');
            return false;
        }
        getProductVariationSearch({
            multiple: false,
            args: {
                product_id: productId,
                search: false
            },
            callback: function (data) {
                var info = data['info'];
                $this.val(info['variation_code']);
                $('input[name=product_price]').val(info['price']);
            }
        });
    });
});

function saveForm() {
    var form = $('#save-form');
    var formData = new FormData();
    var data = form.serializeArray();
    for (var i in data) {
        formData.append(data[i].name, data[i].value);
    }
    form.find('input[type=file]').each(function () {
        if ($(this).val().length) {
            formData.append($(this).attr('name'), $(this)[0].files[0]);
        }
    });
    $.loading('show');
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: formData,
        dataType: 'json',
        contentType: false,
        processData: false,
        success: function (res) {
            if (res.code == 200) {
                $.success(res.message);
            } else {
                $.error(res.message);
            }
        },
        complete: function () {
            $.loading('hide');
        }
    });
}