$(function () {
    $('#save-form').validate({
        rules: {
            template_name: {
                required: true
            },
            freight_type: {
                required: true
            },
            number: {
                required: true
            },
            start_price: {
                required: true
            },
            step_number: {
                required: true
            },
            step_price: {
                required: true
            }
        },
        messages: {
            template_name: {
                required: '请输入模版名称'
            },
            freight_type: {
                required: '请输入计价方式'
            },
            number: {
                required: '请输入数量'
            },
            start_price: {
                required: '请输入起步价'
            },
            step_number: {
                required: '请输入增加数量'
            },
            step_price: {
                required: '请输入增加费用'
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
            freight_id: $(this).data('freight_id'),
        };
        $.loading('show');
        $.post('/erp/freight-template/delete', args, function (res) {
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