$(function () {
    $('#save-form').validate({
        rules: {
            link_type: {
                required: true
            },
            link: {
                required: true
            }
        },
        messages: {
            link_type: {
                required: '请输入链接到'
            },
            link: {
                required: '请输入链接内容'
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
        $.post('/erp/message-activity-child/delete', args, function (res) {
            $.loading('hide');
            if (res.code == 200) {
                $.success(res.message);
                $this.parents('tr').remove();
            } else {
                $.error(res.message);
            }
        }, 'json');
    });
    $('select[name="link_type"]').change(function () {
        $('.link-type-form').hide();
        $('.link-type-form[data-id="' + $(this).val() + '"]').show();
    });
    $('.link-type-form').on('click', '.search-product-btn', function () {
        getProductSearch({
            'multiple': false,
            'callback': function (data) {
                console.log(data)
                $('.search-product-data').remove();
                var searchTable = $('.search-product-box');
                var html = '<tr class="search-product-data">' +
                    '<td><input type="hidden" class="link" value="' + data['product_id'] + '">' + data['product_id'] + '</td>' +
                    '<td>' + data['product_name'] + '</td>' +
                    '<td>' + (data['pic'] ? '<img src="' + data['pic'] + '" style="width: 40px;height: 40px;">' : '') + '</td>' +
                    '<td><div class="btn btn-sm btn-danger search-product-btn">修改</div></td>' +
                    '</tr>';
                searchTable.find('.search-product-title').after(html)
            }
        });
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
                POST('/erp/message-activity-child/set-sort', args, function (res) {
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
    $('.share-all-btn').click(function () {
        var ids = [];
        var activity = [];
        $('.check-one:checked').each(function () {
            if ($(this).data('status') == 1) {
                ids.push($(this).val());
                if (activity.indexOf($(this).data('p')) == -1) {
                    activity.push($(this).data('p'))
                }
            }
        });
        if (!activity.length) {
            $.error('没有需要发布的内容');
            return false;
        }
        if (activity.length > 1) {
            $.error('所属活动相同的内容才能进行发布');
            return false;
        }
        activity = activityList[activity[0]];
        var html = '<div class="publish-preview"><div class="publish-title">' +
            '<div class="title-left"><img src="' + activity['pic'] + '" class="publish-title-icon"/><div class="publish-title-text">' + activity['title'] + '</div></div><div class="title-time">' + time2date(new Date().getTime() / 1000) + '</div></div>';
        var length = $('.check-one:checked').length;
        for (var i = 0; i < length; i++) {
            var item = $('.check-one:checked').eq(i);
            var tr = item.parents('tr');
            if (item.data('status') != 1) {
                continue;
            }
            if (i == 0) {
                html += '<div class="publish-jumbotron">' +
                    '<img src="' + tr.find('.pic').attr('src') + '" class="jumbotron-image"/>' +
                    '<div class="jumbotron-text">' + tr.find('.content').html() + '</div></div>'
            } else {
                html += '<div class="publish-child">' +
                    '<div class="child-text">' + tr.find('.content').html() + '</div>' +
                    '<img class="child-image" src="' + tr.find('.pic').attr('src') + '"></div>';
            }
        }
        html += '</div>'
        $.showModal({
            title: '内容发布预览', content: html, width: '30vw', okCallback: function () {
                var args = {
                    activity_id: activity['id'],
                    ids: ids.join(','),
                };
                $.loading('show');
                POST('/erp/message-activity-child/publish', args, function (res) {
                    $.loading('hide');
                    if (res.code == 200) {
                        $.success(res.message,function () {
                            location.reload();
                        });
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
    var linkType = formData.get('link_type');
    var link = '';
    if (linkType == 2) {
        link = $('.link-type-form[data-id="' + linkType + '"]').find('input').val();
        if (link === '') {
            $.error('请输入搜索内容');
            return false;
        }
    } else if (linkType == 1) {
        link = $('.link-type-form[data-id="' + linkType + '"]').find('.link').val();
        if (!link) {
            $.error('请选择链接商品');
            return false;
        }
    }
    formData.delete('link');
    formData.append('link', link);
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