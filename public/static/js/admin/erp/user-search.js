var searchObj = {
    'args': {},
    'callback': null,
    'multiple': true,
};

function getUserSearch(option) {
    var mustProperties = [
        'callback'
    ];
    for (var i in mustProperties) {
        if (!option.hasOwnProperty(mustProperties[i])) {
            throw new Error(mustProperties[i] + ' must not be required');
        }
    }
    for (var i in option) {
        searchObj[i] = option[i];
    }
    searchObj.args['multiple'] = searchObj.multiple ? 1 : 0;
    $.loading('show');
    $.post('/erp/user/search', searchObj.args, function (res) {
        $.loading('hide');
        if (res.code == 200) {
            if ($('.user-modal-event').get(0)) {
                $('.user-modal-event').remove();
            }
            var html = '<div id="modal-event" class="user-search-event">' +
                '<div class="modal-event-backdrop"></div>' +
                '<div class="modal-event-show">' +
                '<div class="modal-event-header">' +
                '<div class="modal-event-header-title">选择用户</div>' +
                '<div class="modal-event-header-close">' +
                '<i class="glyphicon glyphicon-remove"></i></div>' +
                '</div>' +
                '<form class="search-user-form">' + res.data.html + '</form></div></div>';
            $(document.body).append(html);
            var modalEventView = $('.user-search-event');
            modalEventView.show();
        } else {
            $.error(res.message);
        }
    }, 'json');
}


function chooseUser(callback, multiple) {
    try {
        var checkBox = $('.user-search-event').find('input.check-one[type=checkbox]:checked');
        if (checkBox.length == 0) {
            throw new Error('请选择用户!');
        }
        if (!multiple) {
            if (checkBox.length > 1) {
                throw new Error('仅能选择一个用户!');
            }
        }
    } catch (err) {
        $.error(err.message);
        return false;
    }
    var argsList = [];
    checkBox.each(function () {
        var tr = $(this).parents('tr');
        argsList.push({
            'user_id': $(this).val(),
            'nickname': tr.find('.nickname').html(),
            'info': $(this).data('info')
        });
    });
    if (!multiple) {
        argsList = argsList[0];
    }
    callback(argsList);
}


function getUserSearchWithPage(page) {
    var form = $('.user-search-event').find('.search-user-form');
    form.find('input[name=page]').val(page);
    var args = form.serialize();
    $.post('/erp/user/search', args, function (result) {
        if (result.code == 200) {
            form.html(result.data.html);
        } else {
            $.error(result.message);
        }
    }, 'json');
}

$(function () {
    $('body').on('click', '.user-search-event .page-link', function () {
        getUserSearchWithPage($(this).data('key'));
    }).on('click', '.modal-event-header-close,.modal-event-close', function (e) {
        e.preventDefault();
        $('.user-search-event').remove();
    }).on('click', '.user-search-event .modal-event-confirm-one', function () {
        chooseUser(searchObj.callback, searchObj.multiple);
        $('.user-search-event').remove();
    }).on('click', '.user-search-event .modal-event-confirm-multiple', function (e) {
        chooseUser(searchObj.callback, searchObj.multiple);
    }).on('click', '.user-search-tr', function (e) {
        if (e.target.classList[0] != 'check-one') {
            var checkBox = $(this).find('.check-one');
            checkBox.prop('checked', !checkBox.prop('checked'));
        }
    }).on('click','.search-form .user-search-btn',function(){
        var form = $('.user-search-event').find('.search-user-form');
        getUserSearchWithPage(form.find('input[name=page]').val());
    })
});
