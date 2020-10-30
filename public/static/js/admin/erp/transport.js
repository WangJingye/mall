$(function () {
    $('#save-form').validate({
        rules: {
            transport_name: {
                required: true
            },
            status: {
                required: true
            }
        },
        messages: {
            transport_name: {
                required: '请输入物流方式名称'
            },
            status: {
                required: '请输入状态'
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
            transport_id: $(this).data('transport_id'),
        };
        $.loading('show');
        POST('/erp/transport/delete', args, function (res) {
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
        POST('/erp/transport/set-status', args, function (res) {
            $.loading('hide');
            if (res.code == 200) {
                $.success(res.message);
                if (args.status == 2) {
                    data = {
                        'btn_class': 'btn-success',
                        'class_name': 'glyphicon-ok-circle',
                        'status': '1',
                        'name': '启用',
                        'title': '禁用中',
                    };
                }
                if (args.status == 1) {
                    data = {
                        'btn_class': 'btn-danger',
                        'class_name': 'glyphicon-remove-circle',
                        'status': '2',
                        'name': '禁用',
                        'title': '启用中',
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
});

function saveForm() {
    var form = $('#save-form');
    var formData = new FormData();
    var data = form.serializeArray();
    for (var i in data) {
        formData.append(data[i].name, data[i].value);
    }
    form.find('input[type=file]').each(function () {
        if($(this).val().length) {
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