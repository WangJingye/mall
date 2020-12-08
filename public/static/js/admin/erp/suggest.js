$(function () {
    $('.remove-btn').click(function () {
        if (!confirm('是否删除此记录？')) {
            return false;
        }
        let $this = $(this);
        let args = {
            id: $(this).data('id'),
        };
        $.loading('show');
        $.post('/erp/suggest/delete', args, function (res) {
            $.loading('hide');
            if (res.code == 200) {
                $.success(res.message);
                $this.parents('tr').remove();
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
            title: '意见反馈回复', content: html, width: '30vw', okCallback: function () {
                var args = {
                    ids: $this.data('id'),
                    reply: $('#modal-event').find('#reply').val(),
                };
                if (!args.reply.length) {
                    $.error('请输入回复内容');
                    return false;
                }
                $.loading('show');
                POST('/erp/suggest/reply', args, function (res) {
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
            $.error('没有需要回复的意见反馈');
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
            title: '意见反馈回复', content: html, width: '30vw', okCallback: function () {
                var args = {
                    ids: ids.join(','),
                    reply: $('#modal-event').find('#reply').val(),
                };
                if (!args.reply.length) {
                    $.error('请输入回复内容');
                    return false;
                }
                $.loading('show');
                POST('/erp/suggest/reply', args, function (res) {
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
});
