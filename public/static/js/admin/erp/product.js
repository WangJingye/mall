$(function () {
    $('#save-form').validate({
        rules: {
            product_name: {
                required: true
            },
            product_type: {
                required: true
            },
            brand_id: {
                required: true
            },
            status: {
                required: true
            },
            product_weight: {
                required: true,
                number: true,
            },
            sort: {
                required: true,
                number: true,
            }
        },
        messages: {
            product_name: {
                required: '请输入商品名称'
            },
            product_type: {
                required: '请选择商品类型'
            },
            brand_id: {
                required: '请选择品牌'
            },
            status: {
                required: '请选择商品状态'
            },
            product_weight: {
                required: '请输入商品重量',
                number: '商品重量只能是数字',
            },
            sort: {
                required: '请输入排序值',
                number: '排序值只能是数字',
            }
        },
        submitHandler: function () {
            try {
                event.preventDefault();
                saveForm();
            } catch (ex) {
                console.log(ex.message)
            }
            return false;
        }
    });

    $('.remove-btn').click(function () {
        if (!confirm('是否删除此记录？')) {
            return false;
        }
        let $this = $(this);
        let args = {
            product_id: $(this).data('product_id'),
        };
        $.loading('show');
        POST('/erp/product/delete', args, function (res) {
            $.loading('hide');
            if (res.code == 200) {
                $.success(res.message);
                $this.parents('tr').remove();
            } else {
                $.error(res.message);
            }
        }, 'json');
    });
    $('.set-status-btn').click(function () {
        let $this = $(this);
        let tr = $(this).parents('tr');
        let args = {
            product_id: $this.data('id'),
            status: $this.data('status')
        };
        $.loading('show');
        POST($this.data('url'), args, function (res) {
            $.loading('hide');
            if (res.code == 200) {
                $.success(res.message);
                if (args.status == 1) {
                    var data = {
                        'btn_class': 'btn-danger',
                        'class_name': 'glyphicon-remove-circle',
                        'status': '2',
                        'name': '下架',
                        'title': '上架',
                    };
                } else if (args.status == 2) {
                    var data = {
                        'btn_class': 'btn-success',
                        'class_name': 'glyphicon-ok-circle',
                        'status': '1',
                        'name': '上架',
                        'title': '下架',
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

    $('.product-category-select-group').on('change', 'select.category-select', function () {
        var $this = $(this);
        var group = $this.parents('.product-category-select-group');
        group.find('select.category-select').removeClass('cate-target');
        $this.addClass('cate-target');
        var flag = 0;
        group.find('select.category-select').each(function () {
            if (flag === 1) {
                $(this).select2('destroy').remove();
            } else if ($(this).hasClass('cate-target')) {
                flag = 1;
            }
        });
        var cList = null;
        if ($(this).val().length) {
            if (categoryList.hasOwnProperty($(this).val())) {
                cList = categoryList[$(this).val()];
            }
        }
        if (cList != null) {
            var selectHtml = '<select class="form-control select2 category-select">' +
                '<option value="">请选择</option>';
            for (var i in cList) {
                selectHtml += '<option value="' + i + '">' + cList[i] + '</option>';
            }
            selectHtml += '</select>';
            group.find('.sr-only').before(selectHtml);
            group.find('select.category-select').last().select2();
        }
    });
    $('.add-product-params-btn').click(function () {
        var html = '<tr class="params-data">' +
            '<td><input type="text" class="form-control params-name" placeholder="输入名称"></td>' +
            '<td><input type="text" class="form-control params-value" placeholder="输入内容"></td>' +
            '<td><div class="btn btn-outline-danger btn-sm params-remove-btn">删除</div></td>' +
            '</tr>';
        $('.params-content').find('.last-tr').before(html);
    });
    $('.product-params-box').on('click', '.params-remove-btn', function () {
        $(this).parents('tr').remove();
    });
    $('.variation-custom').on('click', '.add-rule-btn', function () {
        var html = ' <div class="variation-box">' +
            '<input type="text" class="form-control variation-name" placeholder="规格名">' +
            '<input type="text" class="form-control variation-value" oninput="checkRules(this)" placeholder="规格值">' +
            '<span class="add-rule-btn">+</span>' +
            '<span class="remove-rule-btn">-</span>' +
            '</div>';
        $(this).parents('.variation-box').after(html);
    }).on('click', '.remove-rule-btn', function () {
        if ($(this).parents('.variation-custom').find('.variation-box').length <= 1) {
            $.error('最少保留一个！');
            return false;
        }
        $(this).parents('.variation-box').remove();
        showSku();
    }).on('blur', '.variation-name', function () {
        var names = [];
        $('.variation-custom').find('.variation-name').each(function () {
            if ($(this).val().length && names.indexOf($(this).val()) == -1) {
                names.push($(this).val());
            }
        });
        if (names.length > $('.variation-custom').attr('data-max')) {
            $(this).val('');
            return false;
        }
        showSku();
    }).on('blur', '.variation-value', function () {
        showSku();
    });
    $('.user-id-group input[type=radio]').change(function () {
        if ($(this).val() == 1) {
            $('.search-user-group').show();
        } else {
            $('.search-user-group').hide();
        }
    });
    $('.search-user-group').on('click', '.search-user-btn', function () {
        getUserSearch({
            'args': {level: 2},
            'multiple': false,
            'callback': function (data) {
                $('.search-user-data').remove();
                var searchUserTable = $('.search-user-box');
                var info = data['info'];
                var html = '<tr class="search-user-data">' +
                    '<td><input type="hidden" class="user_id" value="' + info['user_id'] + '">' + info['nickname'] + '</td>' +
                    '<td>' + info['company_name'] + '</td>' +
                    '<td>' + info['telephone'] + '</td>' +
                    '<td><div class="btn btn-sm btn-danger search-user-btn">修改</div></td>' +
                    '</tr>';
                searchUserTable.find('.search-user-title').after(html)
            }
        });
    });
    $('.view-operation-btn').click(function () {
        var $this = $(this);
        var args = {
            product_id: $this.data('id')
        };
        if ($('.ajaxDropDownView[data-id="' + args.product_id + '"]').get(0)) {
            $('.ajaxDropDownView[data-id="' + args.product_id + '"]').toggle();
            return false;
        }
        $.loading('show');
        POST($this.data('url'), args, function (res) {
            $.loading('hide');
            if (res.code == 200) {
                $('.ajaxDropDownView').remove();
                $this.parents('tr').after(res.data.html);
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
                POST('/erp/product/set-sort', args, function (res) {
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
    $('input[name=product_type]').change(function () {
        if ($(this).val() == 2) {
            $('.freight_group').hide();
            $('.product_weight_group').hide();
        } else {
            $('.freight_group').show();
            $('.product_weight_group').show();
        }
    });
});

function showSku() {
    var rules = getRules();
    var ruleValues = [];
    var ruleNames = [];
    var key = 0;
    for (var i in rules) {
        ruleValues = getSpec(ruleValues, rules[i]['value']);
        ruleNames[key] = i;
        key++;
    }
    var html = '';
    ruleNames = ruleNames.join(',');
    var existRuleValues = [];
    $('.product_variation_list').find('.has-rule-sku').each(function () {
        var rValue = $(this).find('.rules-value').val();
        if ($(this).attr('data-key') != ruleNames) {
            $(this).remove();
        } else if (ruleValues.indexOf(rValue) == -1) {
            $(this).remove();
        } else {
            existRuleValues.push(rValue);
        }
    });
    if (ruleValues.length) {
        $('.empty-rule-sku').hide()
    } else {
        $('.empty-rule-sku').show()
    }
    for (var i in ruleValues) {
        if (existRuleValues.indexOf(ruleValues[i]) != -1) {
            continue;
        }
        html += '<tr class="has-rule-sku" data-key="' + ruleNames + '">' +
            '<td>' +
            '<input type="hidden" class="rules-name" value="' + ruleNames + '">' +
            '<input type="hidden" class="rules-value" value="' + ruleValues[i] + '">' + ruleValues[i] +
            '</td>' +
            '<td><input type="hidden" name="variation_code" value=""></td>' +
            '<td><input type="number" class="form-control price" placeholder="销售价"></td>' +
            '<td><input type="number" class="form-control market_price" placeholder="划线价"></td>' +
            '<td><input type="number" class="form-control stock" placeholder="stock"></td>' +
            '</tr>';
    }
    $('.empty-rule-sku').after(html);
}


function getRules() {
    var rules = [];
    $('.variation-custom').find('.variation-box').each(function () {
        var name = $(this).find('.variation-name').val();
        var value = $(this).find('.variation-value').val();
        if (!name.length || !value.length) {
            return false;
        }
        if (!rules.hasOwnProperty(name)) {
            rules[name] = {'name': name, 'value': []};
        }
        if (rules[name]['value'].indexOf(value) == -1) {
            rules[name]['value'].push(value);
        }
    });
    return rules;
}

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
    if (formData.get('product_type') == 1) {
        if (!$('input[name=product_weight]').val().length) {
            $.error('请输入商品重量');
            return false;
        }
        if (!$('select[name=freight_id]').val().length) {
            $.error('请选择物流运费模版');
            return false;
        }
    } else {
        formData.delete('product_weight');
        formData.delete('freight_id');
    }
    var max = $('select.category-select').length;
    var cvList = [];
    for (i = 0; i < max; i++) {
        var categoryValue = $('select.category-select').eq(i).val();
        cvList.push(categoryValue);
        if (!categoryValue.length) {
            $.error('请选择完整分类');
            return false;
        }
    }
    formData.append('category_id', JSON.stringify(cvList));
    max = $('.product-params-box').find('tr.params-data').length;
    var productParams = [];
    for (i = 0; i < max; i++) {
        let tr = $('.product-params-box').find('tr.params-data').eq(i);
        let name = tr.find('.params-name').val();
        let value = tr.find('.params-value').val();
        if (name.length && value.length) {
            productParams.push({'name': name, 'value': value});
        }
    }
    formData.append('product_params', JSON.stringify(productParams));
    var rules = getRules();
    var r = [];
    for (i in rules) {
        r.push(rules[i]);
    }
    rules = r;
    formData.append('rules', JSON.stringify(rules));
    var variations = [];
    var map = {'price': '销售价', 'market_price': '划线价', 'stock': '库存'};
    if (rules.length == 0) {
        let tr = $('.product_variation_list').find('.empty-rule-sku');
        let variation = {};
        variation['variation_code'] = tr.find('input[name=variation_code]').val();
        variation['rules_name'] = '';
        variation['rules_value'] = '';
        for (i in map) {
            variation[i] = tr.find('.' + i).val().trim();
            if (!variation[i].length) {
                tr.find('.' + i).removeClass('is-valid').addClass('is-invalid');
            }
        }
        for (i in map) {
            if (!variation[i].length) {
                $.error('请确认无规格商品信息完整');
                return false;
            }
        }
        variations.push(variation);
    } else {
        max = $('.product_variation_list').find('.has-rule-sku').length;
        for (j = 0; j < max; j++) {
            let tr = $('.product_variation_list').find('.has-rule-sku').eq(j);
            let variation = {};
            variation['variation_code'] = tr.find('input[name=variation_code]').val();
            variation['rules_name'] = tr.find('.rules-name').val().trim();
            variation['rules_value'] = tr.find('.rules-value').val().trim();
            var tag = 0;
            for (i in map) {
                variation[i] = tr.find('.' + i).val().trim();
                if (variation[i].length) {
                    tag++;
                }
            }
            if (tag == 0) {
                continue;
            }
            if (tag == 3) {
                variations.push(variation);
            } else {
                for (i in map) {
                    if (!variation[i].length) {
                        tr.find('.' + i).removeClass('is-valid').addClass('is-invalid');
                    }
                }
                for (i in map) {
                    if (!variation[i].length) {
                        $.error('请确认SKU信息完整');
                        return false;
                    }
                }
            }
        }
    }
    if (!variations.length) {
        $.error('请至少输入一个SKU记录');
        return false;
    }
    formData.append('variations', JSON.stringify(variations));
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

//规格组合
function getSpec(arr1, arr2) {
    if (arr1.length == 0) {
        return arr2;
    }
    var res = [];
    for (var i in arr1) {
        for (var j in arr2) {
            res.push(arr1[i] + ',' + arr2[j]);
        }
    }
    return res;
}

function checkRules(obj) {
    if ($(obj).val().indexOf(',') != -1) {
        $(obj).val($(obj).val().replace(',', ''))
    }
}

