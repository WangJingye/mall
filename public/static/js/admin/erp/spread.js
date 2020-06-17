$(function () {
    $('#save-form').validate({
        rules: {},
        messages: {},
        submitHandler: function (e) {
            saveForm();
            return false;
        }
    });
    $('input[name=depth]').change(function () {
        $('.back-money-group').remove();
        var depth = $(this).val();
        var html = '';
        for (var i = 0; i < depth; i++) {
            html += '<div class="form-group row back-money-group">' +
                '<label class="col-sm-4 text-nowrap col-form-label form-label">第' + (i+1) + '级返佣比例</label>' +
                '<div class="col-sm-8">' +
                '<input type="number" name="back[' + i + ']" class="form-control" placeholder="请输入返佣比例">' +
                '</div>' +
                '</div>'
        }
        $(this).parents('.form-group').after(html);
    });
    $('.search-user').click(function () {
        var $this = $(this);
        getUserSearch({
            'multiple': false,
            'callback': function (data) {
                var info = data['info'];
                $this.val(info['nickname']);
                $this.parent().find('input[name=spread_id]').val(info['user_id']);
                if(!$this.parent().find('.search-clear-btn').get(0)) {
                    $this.after('<span class="search-clear-btn"><i class="glyphicon glyphicon-remove-circle"></i></span>');
                }
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