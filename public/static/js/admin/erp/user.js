$(function () {
    $('#save-form').validate({
        rules: {
            level: {
                required: true
            },
            nickname: {
                required: true
            },
            city:{
                required: true
            },
            telephone: {
                required: true
            },
            gender: {
                required: true
            },
            birthday:{
                required: true,
                dateISO:true
            }
        },
        messages: {
            level: {
                required: '请输入用户类型'
            },
            nickname: {
                required: '请输入昵称'
            },
            city:{
                required: '请输入城市'
            },
            telephone: {
                required: '请输入手机号'
            },
            gender: {
                required: '请输入性别'
            },
            birthday:{
                required: '请选择出生日期',
                dateISO:'出生日期格式有误'
            }
        },
        submitHandler: function (e) {
            saveForm();
            return false;
        }
    });

    $('.set-status-btn,.set-profession-status-btn').click(function () {
        let $this = $(this);
        let tr = $(this).parents('tr');
        let args = {
            id: $this.data('id'),
            status: $this.data('status')
        };
        $.loading('show');
        POST($this.data('url'), args, function (res) {
            $.loading('hide');
            if (res.code == 200) {
                $.success(res.message);
                var data = {
                    'btn_class': 'btn-success',
                    'class_name': 'glyphicon-ok-circle',
                    'status': '1',
                    'name': '解禁',
                };
                if (args.status == 1) {
                    data = {
                        'btn_class': 'btn-danger',
                        'class_name': 'glyphicon-remove-circle',
                        'status': '0',
                        'name': '禁用',
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

    $('.set-default').click(function (e) {
        e.preventDefault();
        var $this = $(this);
        var args = {
            id: $this.find('input[type=radio]').val(),
            user_id: $('#user_id').val()
        };
        $.loading('show');
        POST('/erp/user/set-default-address', args, function (res) {
            $.loading('hide');
            if (res.code == 200) {
                $.success(res.message);
                $('.address').find('input[type=radio]').prop('checked', false);
                $this.find('input[type=radio]').prop('checked', true);
            } else {
                $.error(res.message);
            }
        }, 'json');
    });
    $('.is-show-btn').change(function (e) {
        var args = {
            id: $(this).val(),
            is_show: $(this).prop('checked') ? 1 : 0
        };
        $.loading('show');
        POST('/erp/user/set-show', args, function (res) {
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
                POST('/erp/user/set-sort', args, function (res) {
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

    $('.show-more').click(function () {
        $.loading('show');
        var $this = $(this);
        var args = {
            id: $this.data('id'),
            page: $this.data('page')
        };
        POST($this.data('url'), args, function (res) {
            $.loading('hide');
            if (res.code == 200) {
                var html = '';
                var list = res.data;
                for (var i in list) {
                    var v = list[i];
                    html += '<tr>' +
                        '<td>' + orderTypeList[v['order_type']] + '</td>' +
                        '<td>' +
                        '<div>' + v['order_title'] + '</div>' +
                        '<hr style="margin:0;padding:0;">' +
                        '<div>' + v['order_code'] + '</div>' +
                        '</td>' +
                        '<td>' + time2date(v['create_time']) + '</td>' +
                        '<td>' + v['money'] + '</td>' +
                        '<td>' + v['pay_money'] + '</td>' +
                        '<td>' + (v['order_type'] == 1 ? orderStatusList[v['status']] : orderVirtualStatusList[v['status']]) + '</td>' +
                        '<td>' +
                        '<a href="/erp/order/detail?order_id=' + v['order_id'] + '" target="_blank" class="btn btn-sm btn-outline-success">查看</a>' +
                        '</td>' +
                        '</tr>'
                }
                $this.parent('tr').before(html);
                $this.data('page', parseInt(args.page) + 1);
                if(list.length<5){
                    $this.parent('tr').remove();
                }
            } else {
                $.error(res.message);
            }
        }, 'json')
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