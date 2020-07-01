$(function () {
    $('#save-form').validate({
        rules: {},
        messages: {},
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
        $.post('/erp/user-level-verify/delete', args, function (res) {
            $.loading('hide');
            if (res.code == 200) {
                $.success(res.message);
                $this.parents('tr').remove();
            } else {
                $.error(res.message);
            }
        }, 'json');
    });
    $('.search-user').click(function () {
        var $this = $(this);
        getUserSearch({
            'multiple': false,
            'callback': function (data) {
                var info = data['info'];
                $this.val(info['nickname']);
                $this.parent().find('input[name=user_id]').val(info['user_id']);
                if (!$this.parent().find('.search-clear-btn').get(0)) {
                    $this.after('<span class="search-clear-btn"><i class="glyphicon glyphicon-remove-circle"></i></span>');
                }
            }
        });
    });
    $('.check-btn').click(function () {
        var $this = $(this);
        var html = '<form><div class="form-group row">' +
            '<label class="col-sm-3 col-form-label pt-0">审核结果</label>' +
            '<div class="col-sm-9">' +
            '<div class="form-check form-check-inline">' +
            '<input class="form-check-input" type="radio" name="status" id="status2" checked value="2">' +
            '<label class="form-check-label" for="status2">审核通过</label>' +
            '</div>' +
            '<div class="form-check form-check-inline">' +
            '<input class="form-check-input" type="radio" name="status" id="status3" value="3">' +
            '<label class="form-check-label" for="status3">审核拒绝</label>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="form-group row">' +
            '<label for="verify-reply" class="col-sm-3 col-form-label">审核备注</label>' +
            '<div class="col-sm-9">' +
            '<textarea class="form-control" name="remark"></textarea>' +
            '<small class="text-muted">审核拒绝时必填</small>' +
            '</div>' +
            '</div></form>';
        $.showModal({
            title: '审核', content: html, width: '30vw', okCallback: function () {
                var args = {
                    id: $this.data('id'),
                    status: $('#modal-event').find('input[name=status]:checked').val(),
                    remark: $('#modal-event').find('[name=remark]').val()
                };
                if (!args.status.length) {
                    $.error('请选择审核结果');
                    return false;
                }
                if (args.status == 3 && !args.remark.length) {
                    $.error('请输入审核备注');
                    return false;
                }
                $.loading('show');
                $.post('/erp/user-level-verify/verify', args, function (res) {
                    $.loading('hide');
                    if (res.code == 200) {
                        $.success(res.message);
                        $this.remove();
                    } else {
                        $.error(res.message);
                    }
                }, 'json')
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