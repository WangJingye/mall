<?php

namespace admin\extend;

class Constant
{
    const ORDER_STATUS_DELETE = 0;
    const ORDER_STATUS_CREATE = 1;
    const ORDER_STATUS_PAID = 2;
    const ORDER_STATUS_SHIP = 3;
    const ORDER_STATUS_COMPLETE = 4;
    const ORDER_STATUS_CLOSE = 5;
    const ORDER_TYPE_REAL = 1;
    const ORDER_TYPE_VIRTUAL = 2;
    const BILL_TYPE_PAY = 1;
    const BILL_TYPE_REFUND = 2;
    const BILL_TYPE_SPREAD = 3;
    const BILL_RELATION_ORDER = 1;
}