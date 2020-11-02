$(function () {
    $('#save-form').validate({
        rules: {
            product_name: {
                required: true
            },
            group_user_number: {
                required: true,
                number: true
            },
            title: {
                required: true
            },
            start_time: {
                required: true,
                datetime: true
            },
            end_time: {
                required: true,
                datetime: true
            },
            sort: {
                required: true,
                digits: true
            }
        },
        messages: {
            product_name: {
                required: '请选择所属商品'
            },
            group_user_number: {
                required: '请输入成团人数',
                number: '成团人数必须是数字'
            },
            title: {
                required: '请输入标题'
            },
            start_time: {
                required: '请输入开始时间',
                datetime: '开始时间格式有误'
            },
            end_time: {
                required: '请输入结束时间',
                datetime: '结束时间格式有误'
            },
            sort:{
                required: '请输入排序值',
                digits: '库存只能是正整数',
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
        POST('/erp/groupon/delete', args, function (res) {
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
    $('.add-sku-btn').click(function () {
        var productId = $('input[name=product_id]').val();
        if (!productId.length) {
            $.error('请选择商品');
            return false;
        }
        getProductVariationSearch({
            multiple: true,
            args: {
                product_id: productId,
                search: false
            },
            callback: function (data) {
                var html = '';
                var existList = [];
                $('.groupon-variation-box').find('.data-tr .variation-id').each(function () {
                    existList.push($(this).val());
                });
                for (var i in data) {
                    var info = data[i]['info'];
                    if (existList.indexOf(info['variation_id'].toString()) != -1) {
                        continue;
                    }
                    html += '<tr class="data-tr">' +
                        '<td><input type="hidden" class="variation-id" value="' + info['variation_id'] + '">' + info['variation_code'] + '</td>' +
                        '<td>' + (info['rules_value'] !== '' ? info['rules_value'] : '<i style="color: #666">无规格</i>') + '</td>' +
                        '<td><input type="number" class="form-control variation-product-price" value="' + info['price'] + '"></td>' +
                        '<td><input type="number" class="form-control variation-price"></td>' +
                        '<td><input type="number" class="form-control variation-stock"></td>' +
                        '<td><div class="btn btn-sm btn-danger remove-variation-btn"><i class="glyphicon glyphicon-remove"></i></div></td>' +
                        '</tr>';
                }
                $('.groupon-variation-box').find('.last-tr').before(html);
            }
        });
    });
    $('.groupon-variation-box').on('click', '.remove-variation-btn', function () {
        if (!confirm('是否删除此记录？')) {
            return false;
        }
        $(this).parents('tr.data-tr').remove();
    });
    $('.end-btn').click(function () {
        if (!confirm('是否立即结束团购？')) {
            return false;
        }
        let $this = $(this);
        let args = {
            id: $(this).data('id'),
        };
        $.loading('show');
        POST('/erp/groupon/stop', args, function (res) {
            $.loading('hide');
            if (res.code == 200) {
                $.success(res.message);
                $this.parents('tr').find('.status').html('已结束');
            } else {
                $.error(res.message);
            }
        }, 'json');
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
    var max = $('.groupon-variation-box').find('.data-tr').length;
    var vList = [];
    var map = {'product_price': 'variation-product-price', 'price': 'variation-price', 'stock': 'variation-stock'};
    var flag = 0;
    for (var i = 0; i < max; i++) {
        var tr = $('.groupon-variation-box').find('.data-tr').eq(i);
        var variation = {
            'variation_id': tr.find('input.variation-id').val(),
            'product_price': tr.find('input.variation-product-price').val(),
            'price': tr.find('input.variation-price').val(),
            'stock': tr.find('input.variation-stock').val(),
        };
        for (var m in map) {
            if (!variation[m].length) {
                flag = 1;
                tr.find('input.' + map[m]).removeClass('is-valid').addClass('is-invalid');
            }
        }
        vList.push(variation);
    }
    if (!vList.length) {
        $.error('请至少选择一个SKU');
        return false;
    }
    if (flag == 1) {
        $.error('请确认SKU信息完整');
        return false;
    }
    formData.append('variation', JSON.stringify(vList))
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