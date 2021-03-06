$(function () {
    $('#save-form').validate({
        rules: {
            title: {
                required: true
            },
            type: {
                required: true
            },
            price: {
                required: true,
                number: true,
                min: 0
            },
            points: {
                required: true,
                digits: true,
                min: 0,
            },
            min_price: {
                required: true,
                number: true,
                min: 0,
            },
            expire: {
                required: true,
                digits: true,
            },
        },
        messages: {
            title: {
                required: '请输入优惠券标题'
            },
            type: {
                required: '请选择优惠券类型'
            },
            price: {
                required: '请输入面值',
                number: '面值只能是数字',
                min: '面值不能小于0'
            },
            points: {
                required: '请输入所需积分',
                digits: '积分只能是正整数',
            },
            min_price: {
                required: '请输入最小使用价格',
                number: '最小使用价格只能是数字',
                min: '最小金额不能小于0'
            },
            expire: {
                required: '请输入有时间',
                digits: '有效时间只能是正整数',
            },
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
            coupon_id: $(this).data('coupon_id'),
        };
        $.loading('show');
        POST('/erp/coupon/delete', args, function (res) {
            $.loading('hide');
            if (res.code == 200) {
                $.success(res.message);
                $this.parents('tr').remove();
            } else {
                $.error(res.message);
            }
        }, 'json');
    });
    $('.set-status-btn').click(function () {
        let $this = $(this);
        let tr = $(this).parents('tr');
        let args = {
            id: $this.data('id'),
            status: $this.data('status')
        };
        $.loading('show');
        POST('/erp/coupon/set-status', args, function (res) {
            $.loading('hide');
            if (res.code == 200) {
                $.success(res.message);
                if (args.status == 1) {
                    data = {
                        'btn_class': 'btn-danger',
                        'class_name': 'glyphicon-remove-circle',
                        'status': '2',
                        'name': '禁用',
                        'title': '可用',
                    };
                }
                if (args.status == 2) {
                    data = {
                        'btn_class': 'btn-success',
                        'class_name': 'glyphicon-ok-circle',
                        'status': '1',
                        'name': '可用',
                        'title': '禁用',
                    };
                }
                tr.find('.status').html(data.title);
                $this.data('status', data.status);
                $this.removeClass('btn-success').removeClass('btn-danger').addClass(data.btn_class);
                $this.find('.glyphicon').removeClass('glyphicon-remove-circle').removeClass('glyphicon-ok-circle').addClass(data.class_name);
                $this.find('span').html(data.name);
            } else {
                $.error(res.message);
            }
        }, 'json');
    });
    $('input[name=type]').change(function () {
        $('.type-list').hide();
        $('.type-' + $(this).val()).show();
    });
    $('.search-product').click(function () {
        var $this = $(this);
        getProductSearch({
            multiple: false,
            callback: function (data) {
                var info = data['info'];
                $this.val(info['product_name']);
                $this.parent().find('input[name=product_id]').val(info['product_id']);
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
    if (formData.get('type') == 2) {
        if (!formData.get('category_id').length) {
            $.error('请选择商品分类');
            return false;
        }
        formData.append('relation_id', formData.get('category_id'));

    } else if (formData.get('type') == 3) {
        if (!formData.get('product_id').length) {
            $.error('请选择商品');
            return false;
        }
        formData.append('relation_id', formData.get('product_id'));
    }
    formData.delete('product_id');
    formData.delete('category_id');
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