$(function () {
    $('#save-form').validate({
        rules: {
            carousel_type: {
                required: true
            },
            title: {
                required: true
            },
            link_type: {
                required: true
            },
            is_show: {
                required: true
            }
        },
        messages: {
            carousel_type: {
                required: '请输入轮播类型'
            },
            title: {
                required: '请输入标题'
            },
            link_type: {
                required: '请选择链接类型'
            },
            is_show: {
                required: '请选择是否展示'
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
            carousel_id: $(this).data('carousel_id'),
        };
        $.loading('show');
        $.post('/erp/carousel/delete', args, function (res) {
            $.loading('hide');
            if (res.code == 200) {
                $.success(res.message);
                $this.parents('tr').remove();
            } else {
                $.error(res.message);
            }
        }, 'json');
    });

    $('select[name="link_type"]').change(function () {
        $('.link-type-form').hide();
        $('.link-type-form[data-id="' + $(this).val() + '"]').show();
    });
    $('.link-type-form').on('click', '.search-product-btn', function () {
        getProductSearch({
            'multiple': false,
            'callback': function (data) {
                $('.search-product-data').remove();
                var searchTable = $('.search-product-box');
                var html = '<tr class="search-product-data">' +
                    '<td><input type="hidden" class="link_id" value="' + data['product_id'] + '">' + data['product_name'] + '</td>' +
                    '<td>' + data['spu'] + '</td>' +
                    '<td>' + (data['pic'] ? '<img src="' + data['pic'] + '" style="width: 40px;height: 40px;">' : '') + '</td>' +
                    '<td><div class="btn btn-sm btn-danger search-product-btn">修改</div></td>' +
                    '</tr>';
                searchTable.find('.search-product-title').after(html)
            }
        });
    });

    $('.is-show-btn').change(function (e) {
        var args = {
            id: $(this).val(),
            is_show: $(this).prop('checked') ? 1 : 0
        };
        $.loading('show');
        $.post('/erp/carousel/set-show', args, function (res) {
            $.loading('hide');
            if (res.code == 200) {
                $.success(res.message);
            } else {
                $.error(res.message);
            }
        }, 'json');
    });

    $('.set-sort-btn').click(function () {
        var $this = $(this);
        var html = '<form><div class="form-group row">' +
            '<label for="sort-set" class="col-sm-3 col-form-label">设置排序</label>' +
            '<div class="col-sm-9">' +
            '<input type="number" class="form-control" id="sort-set">' +
            '</div>' +
            '</div></form>';
        $.showModal({
            title: '设置排序', content: html, width: '30vw', okCallback: function () {
                var args = {
                    id: $this.data('id'),
                    sort: $('#modal-event').find('#sort-set').val()
                };
                if (!args.sort.length) {
                    $.error('请输入排序值');
                    return false;
                }
                $.loading('show');
                $.post('/erp/carousel/set-sort', args, function (res) {
                    $.loading('hide');
                    if (res.code == 200) {
                        $.success(res.message);
                        $this.parents('tr').find('.sort').html(args.sort);
                    } else {
                        $.error(res.message);
                    }
                }, 'json')
            }
        })
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
    var linkType = formData.get('link_type');
    var linkId = 0;
    if (linkType == 2) {
        linkId = $('.link-type-form[data-id="' + linkType + '"]').find('select').val();
        if (!linkId) {
            $.error('请选择链接分类');
            return false;
        }
    } else if (linkType == 1) {
        linkId = $('.link-type-form[data-id="' + linkType + '"]').find('.link_id').val();
        if (!linkId) {
            $.error('请选择链接商品');
            return false;
        }
    }
    formData.append('link_id', linkId);
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