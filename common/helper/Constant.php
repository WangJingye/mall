<?php

namespace common\helper;

class Constant
{
    const ORDER_STATUS_DELETE = 0;//删除
    const ORDER_STATUS_CREATED = 1;//已创建，待付款
    const ORDER_STATUS_PAID = 2;//已付款
    const ORDER_STATUS_PENDING = 3;//已成团，待处理
    const ORDER_STATUS_SHIPPED = 4;//已发货
    const ORDER_STATUS_RECEIVED = 5;//已收货,普通订单待评价
    const ORDER_STATUS_COMPLETE = 6;//已完成
    const ORDER_STATUS_CLOSE = 99;//关闭
    const ORDER_TYPE_REAL = 1;
    const ORDER_TYPE_VIRTUAL = 2;
    const ORDER_GROUP_NORMAL = 1;
    const ORDER_GROUP_GROUPON = 2;
    const ORDER_GROUP_FLASHSALE = 3;
    const BILL_TYPE_PAY = 1;
    const BILL_TYPE_REFUND = 2;
    const BILL_TYPE_SPREAD = 3;
    const BILL_RELATION_ORDER = 1;

    const CASH_OUT_STATUS_UNVERIFIED = 1;
    const CASH_OUT_STATUS_ACCEPT = 2;
    const CASH_OUT_STATUS_REJECT = 3;

    const USER_LEVEL_STATUS_UNVERIFIED = 1;
    const USER_LEVEL_STATUS_ACCEPT = 2;
    const USER_LEVEL_STATUS_REJECT = 3;

}