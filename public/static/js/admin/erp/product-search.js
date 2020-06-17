var searchObj = {
    'args': {},
    'callback': null,
    'multiple': true,
};

function getProductSearch(option) {
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
    $.post('/erp/product/search', searchObj.args, function (res) {
        $.loading('hide');
        if (res.code == 200) {
            if ($('.product-modal-event').get(0)) {
                $('.product-modal-event').remove();
            }
            var html = '<div id="modal-event" class="product-search-event">' +
                '<div class="modal-event-backdrop"></div>' +
                '<div class="modal-event-show">' +
                '<div class="modal-event-header">' +
                '<div class="modal-event-header-title">选择商品</div>' +
                '<div class="modal-event-header-close">' +
                '<i class="glyphicon glyphicon-remove"></i></div>' +
                '</div>' +
                '<form class="search-product-form">' + res.data.html + '</form></div></div>';
            $(document.body).append(html);
            var modalEventView = $('.product-search-event');
            modalEventView.show();
        } else {
            $.error(res.message);
        }
    }, 'json');
}

function chooseProduct(callback, multiple) {
    try {
        var checkBox = $('.product-search-event').find('input.check-one[type=checkbox]:checked');
        if (checkBox.length == 0) {
            throw new Error('请选择商品!');
        }
        if (!multiple) {
            if (checkBox.length > 1) {
                throw new Error('仅能选择一个商品!');
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
            'product_id': $(this).val(),
            'product_name': tr.find('.product_name').html(),
            'pic': tr.find('.pic').data('value'),
            'spu': tr.find('.spu').html(),
            'info': $(this).data('info')
        });
    });
    if (!multiple) {
        argsList = argsList[0];
    }
    callback(argsList);
}

function getProductSearchWithPage(page) {
    var form = $('.product-search-event').find('.search-product-form');
    form.find('input[name=page]').val(page);
    var args = form.serialize();
    $.post('/erp/product/search', args, function (result) {
        if (result.code == 200) {
            form.html(result.data.html);
        } else {
            $.error(result.message);
        }
    }, 'json');
}


function getProductVariationSearch(option) {
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
    $.post('/erp/product/variation-search', searchObj.args, function (res) {
        $.loading('hide');
        if (res.code == 200) {
            if ($('.product-variation-modal-event').get(0)) {
                $('.product-variation-modal-event').remove();
            }
            var html = '<div id="modal-event" class="product-variation-search-event">' +
                '<div class="modal-event-backdrop"></div>' +
                '<div class="modal-event-show">' +
                '<div class="modal-event-header">' +
                '<div class="modal-event-header-title">选择商品SKU</div>' +
                '<div class="modal-event-header-close">' +
                '<i class="glyphicon glyphicon-remove"></i></div>' +
                '</div>' +
                '<form class="search-product-variation-form">' + res.data.html + '</form></div></div>';
            $(document.body).append(html);
            var modalEventView = $('.product-variation-search-event');
            modalEventView.show();
        } else {
            $.error(res.message);
        }
    }, 'json');
}

function chooseProductVariation(callback, multiple) {
    try {
        var checkBox = $('.product-variation-search-event').find('input.check-one[type=checkbox]:checked');
        if (checkBox.length == 0) {
            throw new Error('请选择商品!');
        }
        if (!multiple) {
            if (checkBox.length > 1) {
                throw new Error('仅能选择一个商品!');
            }
        }
    } catch (err) {
        $.error(err.message);
        return false;
    }
    var argsList = [];
    checkBox.each(function () {
        argsList.push({
            'variation_id': $(this).val(),
            'info': $(this).data('info')
        });
    });
    if (!multiple) {
        argsList = argsList[0];
    }
    callback(argsList);
}

function getProductVariationSearchWithPage(page) {
    var form = $('.product-variation-search-event').find('.search-product-variation-form');
    form.find('input[name=page]').val(page);
    var args = form.serialize();
    $.post('/erp/product/variation-search', args, function (result) {
        if (result.code == 200) {
            form.html(result.data.html);
        } else {
            $.error(result.message);
        }
    }, 'json');
}


$(function () {
    $('body').on('click', '.product-search-event .page-link', function () {
        getProductSearchWithPage($(this).data('key'));
    }).on('click', '.modal-event-header-close,.modal-event-close', function (e) {
        e.preventDefault();
        $('.product-search-event').remove();
    }).on('click', '.product-search-event .modal-event-confirm-one', function () {
        chooseProduct(searchObj.callback, searchObj.multiple);
        $('.product-search-event').remove();
    }).on('click', '.product-search-event .modal-event-confirm-multiple', function (e) {
        chooseProduct(searchObj.callback, searchObj.multiple);
    }).on('click', '.product-search-tr', function (e) {
        if (e.target.classList[0] != 'check-one') {
            var checkBox = $(this).find('.check-one');
            checkBox.prop('checked', !checkBox.prop('checked'));
        }
    }).on('click', '.search-form .product-search-btn', function () {
        var form = $('.product-search-event').find('.search-product-form');
        getProductSearchWithPage(form.find('input[name=page]').val());
    }).on('click', '.product-variation-search-event .page-link', function () {
        getProductVariationSearchWithPage($(this).data('key'));
    }).on('click', '.modal-event-header-close,.modal-event-close', function (e) {
        e.preventDefault();
        $('.product-variation-search-event').remove();
    }).on('click', '.product-variation-search-event .modal-event-confirm-one', function () {
        chooseProductVariation(searchObj.callback, searchObj.multiple);
        $('.product-variation-search-event').remove();
    }).on('click', '.product-variation-search-event .modal-event-confirm-multiple', function (e) {
        chooseProductVariation(searchObj.callback, searchObj.multiple);
    }).on('click', '.product-variation-search-tr', function (e) {
        if (e.target.classList[0] != 'check-one') {
            var checkBox = $(this).find('.check-one');
            checkBox.prop('checked', !checkBox.prop('checked'));
        }
    }).on('click', '.search-form .product-variation-search-btn', function () {
        var form = $('.product-variation-search-event').find('.search-product-variation-form');
        getProductVariationSearchWithPage(form.find('input[name=page]').val());
    });
});
