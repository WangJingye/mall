$(function () {
    $('#save-form').validate({
        rules: {
            category_name: {
                required: true
            },
            parent_id: {
                required: true
            }
        },
        messages: {
            category_name: {
                required: '请输入分类名称'
            },
            parent_id: {
                required: '请选择父级分类'
            }
        },
        submitHandler: function (e) {
            saveForm();
            return false;
        }
    });

    $('.list-table').on('click', '.category-name', function () {
        var $this = $(this);
        if ($this.hasClass('can-click') == 1) {
            var args = {
                id: $this.data('id'),
                level: $this.data('level')
            };
            if ($('.ajaxDropDownView[data-id="' + args.id + '"]').get(0)) {
                var type = 'hide';
                if ($('.ajaxDropDownView[data-id="' + args.id + '"]').is(':hidden')) {
                    type = 'show';
                    $this.parents('tr').find('.has-child-icon i').removeClass('glyphicon-triangle-right').addClass('glyphicon-triangle-bottom');
                } else {
                    $this.parents('tr').find('.has-child-icon i').removeClass('glyphicon-triangle-bottom').addClass('glyphicon-triangle-right');
                }
                listDropDown(args.id, 'category-name', type);
                return false;
            }
            $.loading('show');
            $.get($this.data('url'), args, function (res) {
                $.loading('hide');
                if (res.code == 200) {
                    if (res.data.is_empty == 1) {
                        $.error('暂无下级数据');
                        return false;
                    }
                    $this.parent('tr').after(res.data.html);
                    $this.parents('tr').find('.has-child-icon i').removeClass('glyphicon-triangle-right').addClass('glyphicon-triangle-bottom');
                } else {
                    $.error(res.message);
                }
            }, 'json');
        }
    }).on('click', '.remove-btn', function () {
        if (!confirm('是否删除此记录？')) {
            return false;
        }
        let $this = $(this);
        let args = {
            category_id: $(this).data('category_id'),
        };
        $.loading('show');
        POST('/erp/category/delete', args, function (res) {
            $.loading('hide');
            if (res.code == 200) {
                $.success(res.message);
                $this.parents('tr').remove();
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