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
                required: true,
                digits:true,
                min:1
            },
            start_price: {
                required: true,
                min:0,
            },
            step_number: {
                required: true,
                digits:true,
                min:1
            },
            step_price: {
                required: true,
                min:0
            }
        },
        messages: {
            template_name: {
                required: '请输入模版名称'
            },
            freight_type: {
                required: '请选择计价方式'
            },
            number: {
                required: '请输入起步数量',
                digits:'起步数量必须是正整数',
                min:'起步数量不能小于1',
            },
            start_price: {
                required: '请输入起步价',
                min:'起步价不能小于0'
            },
            step_number: {
                required: '请输入增加数量',
                digits:'增加数量必须是正整数',
                min:'增加数量不能小于1',
            },
            step_price: {
                required: '请输入增加费用',
                min:'增加费用不能小于0'
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
        POST('/erp/freight-template/delete', args, function (res) {
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