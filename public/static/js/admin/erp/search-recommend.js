$(function () {
    $('#save-form').validate({
        rules: {
            title: {
                required: true
            },
            sort: {
                required: true
            }
        },
        messages: {
            title: {
                required: '请输入标题'
            },
            sort: {
                required: '请输入排序'
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
        POST('/erp/search-recommend/delete', args, function (res) {
            $.loading('hide');
            if (res.code == 200) {
                $.success(res.message);
                $this.parents('tr').remove();
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
                POST('/erp/search-recommend/set-sort', args, function (res) {
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