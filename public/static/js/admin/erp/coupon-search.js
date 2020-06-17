var searchObj = {
    'args': {},
    'callback': null,
    'multiple': true,
};

function getCouponSearch(option) {
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
    $.post('/erp/coupon-user/search', searchObj.args, function (res) {
        $.loading('hide');
        if (res.code == 200) {
            if ($('.coupon-modal-event').get(0)) {
                $('.coupon-modal-event').remove();
            }
            var html = '<div id="modal-event" class="coupon-search-event">' +
                '<div class="modal-event-backdrop"></div>' +
                '<div class="modal-event-show">' +
                '<div class="modal-event-header">' +
                '<div class="modal-event-header-title">选择优惠券</div>' +
                '<div class="modal-event-header-close">' +
                '<i class="glyphicon glyphicon-remove"></i></div>' +
                '</div>' +
                '<form class="search-coupon-form">' + res.data.html + '</form></div></div>';
            $(document.body).append(html);
            var modalEventView = $('.coupon-search-event');
            modalEventView.show();
        } else {
            $.error(res.message);
        }
    }, 'json');
}


function chooseCoupon(callback, multiple) {
    try {
        var checkBox = $('.coupon-search-event').find('input.check-one[type=checkbox]:checked');
        if (checkBox.length == 0) {
            throw new Error('请选择优惠券!');
        }
        if (!multiple) {
            if (checkBox.length > 1) {
                throw new Error('仅能选择一个张优惠券!');
            }
        }
    } catch (err) {
        $.error(err.message);
        return false;
    }
    var argsList = [];
    checkBox.each(function () {
        argsList.push({
            'coupon_id': $(this).val(),
            'info': $(this).data('info')
        });
    });
    if (!multiple) {
        argsList = argsList[0];
    }
    callback(argsList);
}


function getCouponSearchWithPage(page) {
    var form = $('.coupon-search-event').find('.search-coupon-form');
    form.find('input[name=page]').val(page);
    var args = form.serialize();
    $.post('/erp/coupon/search', args, function (result) {
        if (result.code == 200) {
            form.html(result.data.html);
        } else {
            $.error(result.message);
        }
    }, 'json');
}

$(function () {
    $('body').on('click', '.coupon-search-event .page-link', function () {
        getCouponSearchWithPage($(this).data('key'));
    }).on('click', '.modal-event-header-close,.modal-event-close', function (e) {
        e.preventDefault();
        $('.coupon-search-event').remove();
    }).on('click', '.coupon-search-event .modal-event-confirm-one', function () {
        chooseCoupon(searchObj.callback, searchObj.multiple);
        $('.coupon-search-event').remove();
    }).on('click', '.coupon-search-event .modal-event-confirm-multiple', function (e) {
        chooseCoupon(searchObj.callback, searchObj.multiple);
    }).on('click', '.coupon-search-tr', function (e) {
        if (e.target.classList[0] != 'check-one') {
            var checkBox = $(this).find('.check-one');
            checkBox.prop('checked', !checkBox.prop('checked'));
        }
    }).on('click','.search-form .coupon-search-btn',function(){
        var form = $('.coupon-search-event').find('.search-coupon-form');
        getCouponSearchWithPage(form.find('input[name=page]').val());
    })
});
