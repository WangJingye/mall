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
            comment_id: $(this).data('comment_id'),
        };
        $.loading('show');
        POST('/erp/product-comment/delete', args, function (res) {
            $.loading('hide');
            if (res.code == 200) {
                $.success(res.message);
                $this.parents('tr').remove();
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
        POST('/erp/product-comment/set-show', args, function (res) {
            $.loading('hide');
            if (res.code == 200) {
                $.success(res.message);
            } else {
                $.error(res.message);
            }
        }, 'json');
    });
    $('.share-btn').click(function () {
        var $this = $(this);
        var html = '<form>' +
            '<div class="form-group row">' +
            '<label for="verify-reply" class="col-sm-3 col-form-label">回复内容</label>' +
            '<div class="col-sm-9">' +
            '<textarea class="form-control" name="replay" id="reply"></textarea>' +
            '</div>' +
            '</div></form>';
        $.showModal({
            title: '评论回复', content: html, width: '30vw', okCallback: function () {
                var args = {
                    ids: $this.data('id'),
                    reply: $('#modal-event').find('#reply').val(),
                };
                if (!args.reply.length) {
                    $.error('请输入回复内容');
                    return false;
                }
                $.loading('show');
                POST('/erp/product-comment/reply', args, function (res) {
                    $.loading('hide');
                    if (res.code == 200) {
                        $.success(res.message);
                        $this.remove();
                    } else {
                        $.error(res.message);
                    }
                }, 'json')
            }
        })
    });
    $('.share-all-btn').click(function () {
        var ids = [];
        $('.check-one:checked').each(function () {
            if ($(this).data('status') == 1) {
                ids.push($(this).val());
            }
        });
        if (!ids.length) {
            $.error('没有需要回复的评论');
            return false;
        }
        var html = '<form>' +
            '<div class="form-group row">' +
            '<label for="verify-reply" class="col-sm-3 col-form-label">回复内容</label>' +
            '<div class="col-sm-9">' +
            '<textarea class="form-control" name="replay" id="reply"></textarea>' +
            '</div>' +
            '</div></form>';
        $.showModal({
            title: '评论回复', content: html, width: '30vw', okCallback: function () {
                var args = {
                    ids: ids.join(','),
                    reply: $('#modal-event').find('#reply').val(),
                };
                if (!args.reply.length) {
                    $.error('请输入回复内容');
                    return false;
                }
                $.loading('show');
                POST('/erp/product-comment/reply', args, function (res) {
                    $.loading('hide');
                    if (res.code == 200) {
                        $.success(res.message);
                        $('.check-one:checked').each(function () {
                            $(this).data('status', 2);
                            $(this).parents('tr').find('.share-btn').remove();
                        })
                    } else {
                        $.error(res.message);
                    }
                }, 'json')
            }
        })
    });
    $('.view-btn').click(function () {
        var html='2222';
        $.showModal({
            title: '评论回复', content: html, width: '30vw', okTitle: false, cancelTitle: false
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