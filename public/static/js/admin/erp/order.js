$(function () {
    $('#save-form').validate({
        rules: {
            order_title: {
                required: true
            },
            order_type: {
                required: true
            },
            user_id: {
                required: true
            },
            money: {
                required: true,
                min: 0
            },
            freight_money: {
                required: true,
                min: 0
            },
            rate_money: {
                required: true
            },
            receiver_name: {
                required: '[name=order_type] option:selected[value=1]'
            },
            receiver_mobile: {
                required: '[name=order_type] option:selected[value=1]'
            },
            receiver_address: {
                required: '[name=order_type] option:selected[value=1]'
            },
        },
        messages: {
            order_title: {
                required: '请输入订单标题'
            },
            order_type: {
                required: '请选择订单类型'
            },
            user_id: {
                required: '请选择用户'
            },
            money: {
                required: '货品金额不能为空',
                min: '货品金额不能小于0'
            },
            freight_money: {
                required: '运费不能为空',
                min: '运费不能小于0'
            },
            rate_money: {
                required: '请输入优惠金额'
            },
            receiver_name: {
                required: '请输入收件人姓名'
            },
            receiver_mobile: {
                required: '请输入收件人手机号'
            },
            receiver_address: {
                required: '请输入收件人地址'
            },
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
        POST('/erp/order/delete', args, function (res) {
            $.loading('hide');
            if (res.code == 200) {
                $.success(res.message);
                $this.parents('tr').remove();
            } else {
                $.error(res.message);
            }
        }, 'json');
    });

    //关闭订单
    $('.close-btn').click(function () {
        if (!confirm('是否关闭订单？')) {
            return false;
        }
        let $this = $(this);
        let args = {
            id: $(this).data('id'),
        };
        $.loading('show');
        POST('/erp/order/close', args, function (res) {
            $.loading('hide');
            if (res.code == 200) {
                $.success(res.message);
                $this.remove();
            } else {
                $.error(res.message);
            }
        }, 'json');
    });
    //发货订单
    $('.ship-btn').click(function () {
        var $this = $(this);
        var transportHtml = '<select class="form-control transport-id"><option value="">请选择</option>';
        for (var i in transportList) {
            transportHtml += '<option value="' + i + '">' + transportList[i] + '</option>';
        }
        transportHtml += '</select>';
        var html = '<form><div class="form-group row">' +
            '<label class="col-sm-3 col-form-label">物流方式</label>' +
            '<div class="col-sm-9">' +
            transportHtml +
            '</div>' +
            '</div>' +
            '<div class="form-group row">' +
            '<label class="col-sm-3 col-form-label">物流单号</label>' +
            '<div class="col-sm-9">' +
            '<input type="text" class="form-control transport-order">' +
            '</div>' +
            '</div></form>';
        $.showModal({
            title: '填写物流信息', content: html, width: '30vw', okCallback: function () {
                var args = {
                    id: $this.data('id'),
                    transport_id: $('#modal-event').find('.transport-id').val(),
                    transport_order: $('#modal-event').find('.transport-order').val(),
                };
                if (!args.transport_id.length) {
                    $.error('请选择物流方式');
                    return false;
                }
                if (!args.transport_order.length) {
                    $.error('请输入物流单号');
                    return false;
                }
                $.loading('show');
                POST('/erp/order/ship', args, function (res) {
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
    //确认收货订单
    $('.confirm-btn').click(function () {
        if (!confirm('是否确认收货？')) {
            return false;
        }
        let $this = $(this);
        let args = {
            id: $(this).data('id'),
        };
        $.loading('show');
        POST('/erp/order/receive', args, function (res) {
            $.loading('hide');
            if (res.code == 200) {
                $.success('已收货');
                $this.remove();
            } else {
                $.error(res.message);
            }
        }, 'json');
    });
    //确认使用订单
    $('.confirm-virtual-btn').click(function () {
        if (!confirm('是否确认标记为已使用？')) {
            return false;
        }
        let $this = $(this);
        let args = {
            id: $(this).data('id'),
        };
        $.loading('show');
        POST('/erp/order/receive', args, function (res) {
            $.loading('hide');
            if (res.code == 200) {
                $.success('已使用');
                $this.remove();
            } else {
                $.error(res.message);
            }
        }, 'json');
    });
    //确认支付
    $('.pay-btn').click(function () {
        var $this = $(this);
        var payHtml = '<select class="form-control pay-method"><option value="">请选择</option>';
        for (var i in payMethodList) {
            payHtml += '<option value="' + i + '">' + payMethodList[i] + '</option>';
        }
        payHtml += '</select>';
        var html = '<form><div class="form-group row">' +
            '<label class="col-sm-3 col-form-label">收款方式</label>' +
            '<div class="col-sm-9">' +
            payHtml +
            '</div>' +
            '</div>' +
            '<div class="form-group row">' +
            '<label class="col-sm-3 col-form-label">收款金额</label>' +
            '<div class="col-sm-9">' +
            '<input type="number" class="form-control pay-money">' +
            '</div>' +
            '</div>' +
            '<div class="form-group row">' +
            '<label class="col-sm-3 col-form-label">交易流水号</label>' +
            '<div class="col-sm-9">' +
            '<input type="text" class="form-control transaction-id">' +
            '</div>' +
            '</div>' +
            '</form>';
        $.showModal({
            title: '确认收款', content: html, width: '30vw', okCallback: function () {
                var args = {
                    id: $this.data('id'),
                    pay_method: $('#modal-event').find('.pay-method').val(),
                    pay_money: $('#modal-event').find('.pay-money').val(),
                    transaction_id: $('#modal-event').find('.transaction-id').val(),
                };
                if (!args.pay_method.length) {
                    $.error('请选择收款方式');
                    return false;
                }
                if (!args.pay_money.length) {
                    $.error('请输入收款金额');
                    return false;
                }
                $.loading('show');
                POST('/erp/order/pay', args, function (res) {
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
    $('.search-user').click(function () {
        var $this = $(this);
        getUserSearch({
            'multiple': false,
            'callback': function (data) {
                var info = data['info'];
                $this.val(info['nickname']);
                $this.parent().find('input[name=user_id]').val(info['user_id']);
                $this.removeClass('is-invalid')
            }
        });
    });
    $('.search-coupon').click(function () {
        var $this = $(this);
        var args = {
            user_id: $('input[name=user_id]').val(),
            status: 1
        };
        if (!args.user_id.length) {
            $.error('请先选择用户');
            return false;
        }
        getCouponSearch({
            'args': args,
            'multiple': false,
            'callback': function (data) {
                var total = parseFloat($('input[name=money]').val());
                var rate = parseFloat($('input[name=rate_money]').val());
                var info = data['info'];
                if (info['min_price'] > total + rate) {
                    $.error('所选优惠券不满足最小使用金额，请确认');
                    return false;
                }
                $this.val(info['coupon_name']);
                $this.parent().find('input[name=coupon_id]').val(info['id']);
                $('input[name=rate_money]').val(info['price']);
                showMoney();
                if (!$this.parent().find('.search-clear-btn').get(0)) {
                    $this.after('<span class="search-clear-btn"><i class="glyphicon glyphicon-remove-circle"></i></span>');
                }
            }
        });
    });
    $('.search-product-variation').click(function () {
        var orderType = $('select[name=order_type]').val();
        if (!orderType.length) {
            $.error('请选择订单类型');
            return false;
        }
        getProductVariationSearch({
            'args': {'product_type': orderType},
            'multiple': true,
            'callback': function (data) {
                var html = '';
                for (var i in data) {
                    var info = data[i]['info'];
                    if (!$('.order-detail-variation[data-id="' + info['variation_code'] + '"]').length) {
                        html += '<tr class="order-detail-variation" data-id="' + info['variation_code'] + '">' +
                            '<td>' +
                            '<input type="hidden" class="product_weight" value="' + info['product_weight'] + '">' +
                            '<input type="hidden" class="freight_id" value="' + info['freight_id'] + '">' +
                            info['product_id'] + '</td>' +
                            '<td>' + info['product_name'] + '</td>' +
                            '<td>' + info['variation_code'] + '</td>' +
                            '<td>' + info['rules_value'] + '</td>' +
                            '<td><input type="number" class="form-control number calc-price"></td>' +
                            '<td><input type="number" class="form-control price calc-price" value="' + info['price'] + '"></td>' +
                            '<td class="calc-total"></td>' +
                            '<td><div class="btn btn-dark btn-sm remove-variation-btn"><i class="glyphicon glyphicon-remove"></i> 删除</div></td>' +
                            '</tr>';
                    }
                }
                $('.empty-variation-tr').hide();
                $('.empty-variation-tr').after(html);
            }
        })
    });
    $('.remove-product-variation').click(function () {
        if (!confirm('确认清空数据？')) {
            return false;
        }
        $('tr.order-detail-variation').remove();
        $('.empty-variation-tr').show();
        showMoney();
    });
    $('body').on('blur', '.calc-price', function () {
        var tr = $(this).parents('tr');
        var number = tr.find('.number').val(),
            price = tr.find('.price').val();
        if (number.length && price.length) {
            tr.find('.calc-total').html((number * price).toFixed(2));
            showMoney();
        }
    }).on('blur', 'input[name=rate_money]', function () {
        showMoney();
    }).on('click', '.remove-variation-btn', function () {
        $(this).parents('tr.order-detail-variation').remove();
        if (!$('tr.order-detail-variation').length) {
            $('.empty-variation-tr').show();
        }
        showMoney();
    });
    $('select[name=order_type]').change(function () {
        if ($(this).val() == 1) {
            $('.receiver-group').show();
        } else if ($(this).val() == 2) {
            $('.receiver-group').hide();
        }
        $('tr.order-detail-variation').remove();
        $('.empty-variation-tr').show();
        showMoney();
    });
    $('#save-form').on('click', '.search-clear-btn', function (event) {
        event.preventDefault();
        $(this).parent().find('input').val('');
        $(this).remove();
        $('input[name=rate_money]').val('0.00');
        showMoney();
    });
});

function showMoney() {
    var total = 0;
    var variations = [];
    $('.product-list').find('.order-detail-variation').each(function () {
        var number = $(this).find('.number').val(),
            freightId = $(this).find('.freight_id').val(),
            productWeight = $(this).find('.product_weight').val(),
            price = $(this).find('.price').val();
        if (number.length && price.length) {
            total += number * price;
            variations.push({
                'number': number,
                'freight_id': freightId,
                'product_weight': productWeight,
            });
        }
    });
    var freightFee = parseFloat(getFreightFee(variations));
    $('input[name=freight_money]').val(freightFee);
    total += freightFee;
    total -= parseFloat($('input[name=rate_money]').val());
    $('input[name=money]').val(total.toFixed(2));
}

function saveForm() {
    var form = $('#save-form');
    $('.search-user').removeClass('is-invalid');
    var formData = new FormData();
    var data = form.serializeArray();
    for (var i in data) {
        formData.append(data[i].name, data[i].value);
    }
    if (!formData.get('user_id')) {
        $('.search-user').addClass('is-invalid');
        return false;
    }
    var max = $('.order-detail-variation').length;
    var variations = [];
    var tag = 1;
    for (i = 0; i < max; i++) {
        var tr = $('.order-detail-variation').eq(i);
        var variation = {
            variation_code: tr.attr('data-id'),
            number: tr.find('.number').val(),
            price: tr.find('.price').val()
        };
        if (!variation.number.length || variation.number == 0) {
            tr.find('.number').removeClass('is-valid').addClass('is-invalid');
            tag = 0;
        }
        if (!variation.price.length) {
            tr.find('.price').removeClass('is-valid').addClass('is-invalid');
            tag = 0;
        }
        variations.push(variation);
    }
    if (tag == 0) {
        $.error('商品信息填写有误，请确认');
        return false;
    }
    formData.append('list', JSON.stringify(variations));
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