<style>
    .main-panel-content {
        padding: 1rem 2rem;
        background-color: #e9ecef;
    }

    .main-panel {
        width: 100%;
    }

    .main-panel-one {
        border: 1px solid #ccc;
        background-color: #fff;
        padding: 1rem;
        display: inline-flex;
        justify-content: space-between;
        width: 10rem;
        margin-bottom: 0.5rem;
        margin-top: 0.5rem;
    }

    .main-panel .iconfont {
        font-size: 2rem;
    }

    .main-panel .main-panel-icon {
        color: #28a745;
        padding-right: 0.5rem;
    }

    .main-panel .main-panel-title {
        color: #666
    }

    .main-panel .main-panel-text {
        font-size: 1rem;
    }

    .detail-title {
        text-align: center;
        background-color: #e9ecef;
        padding: 0.5rem;
    }

    .detail-info {
        border-left: 1px solid #ddd;
        border-right: 1px solid #ddd;
        display: inline-flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        padding: 0.5rem;
        height: 5rem;
    }

    .detail-info:last-child {
        border-bottom: 1px solid #ddd;
        display: inline-flex;
        width: 100%;
        padding: 0.5rem;
    }

    .detail-info-number {
        color: #dc3545
    }

    .detail-info-title {
        font-weight: bold;
        margin: 0.5rem;
    }

    .detail-info-one {
        width: 25%;
        text-align: center;
    }

    .undo-info {
        width: 100%;
    }

    .content-info {
        width: 100%;
        margin-bottom: 2rem;
    }

    .product-info, .user-info {
        margin-top: 2rem;
    }

    .product-info, .user-info {
        width: 100%;
    }

    a {
        color: #000;
    }

    a:hover {
        text-decoration: none;
    }

    @media (min-width: 1050px) {

        .main-panel {
            display: inline-flex;
            justify-content: space-between;
        }

        .main-panel-one {
            border: 1px solid #ccc;
            background-color: #fff;
            padding: 1.5rem;
            display: inline-flex;
            justify-content: space-between;
            width: 12rem;
        }

        .content-info {
            display: inline-flex;
            justify-content: space-between;
        }

        .detail-info-content-one {
            height: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .detail-info-content-two {
            height: 4rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .product-info {
            width: 33%;
        }

        .user-info {
            width: 66%;
        }
    }

</style>
<div class="main-panel-content">
    <div class="main-panel">
        <div class="main-panel-one">
            <div class="main-panel-icon">
                <i class="iconfont icon-order"></i>
            </div>
            <div class="main-panel-text-box">
                <div class="main-panel-title text-nowrap">今日订单总数</div>
                <div class="main-panel-text text-nowrap"><?= $this->data['order_total'] ?></div>
            </div>
        </div>
        <div class="main-panel-one">
            <div class="main-panel-icon">
                <i class="iconfont icon-pay"></i>
            </div>
            <div class="main-panel-text-box">
                <div class="main-panel-title text-nowrap">今日销售总额</div>
                <div class="main-panel-text text-nowrap">¥<?= number_format($this->data['today_sales'], 2) ?></div>
            </div>
        </div>
        <div class="main-panel-one">
            <div class="main-panel-icon">
                <i class="iconfont icon-money"></i>
            </div>
            <div class="main-panel-text-box">
                <div class="main-panel-title text-nowrap">昨日销售总额</div>
                <div class="main-panel-text text-nowrap">¥<?= number_format($this->data['yesterday_sales'], 2) ?></div>
            </div>
        </div>
        <div class="main-panel-one">
            <div class="main-panel-icon">
                <i class="iconfont icon-sales"></i>
            </div>
            <div class="main-panel-text-box">
                <div class="main-panel-title text-nowrap">近7天销售总额</div>
                <div class="main-panel-text text-nowrap">¥<?= number_format($this->data['week_sales'], 2) ?></div>
            </div>
        </div>
    </div>
</div>
<div class="content-info">
    <div class="product-info">
        <div class="detail-title">商品总览</div>
        <div class="detail-info">
            <div class="detail-info-one">
                <div class="detail-info-number"><?= $this->data['product_total'] ?></div>
                <div>商品总数</div>
            </div>
            <div class="detail-info-one">
                <div class="detail-info-number"><?= $this->data['product_offline'] ?></div>
                <div>已下架</div>
            </div>
            <div class="detail-info-one">
                <div class="detail-info-number"><?= $this->data['product_online'] ?></div>
                <div>上架中</div>
            </div>
        </div>
    </div>
    <div class="user-info">
        <div class="detail-title">用户总览</div>
        <div class="detail-info">
            <div class="detail-info-one">
                <div class="detail-info-number"><?= $this->data['today_user_normal'] ?></div>
                <div>今日新增会员</div>
            </div>
            <div class="detail-info-one">
                <div class="detail-info-number"><?= $this->data['yesterday_user_normal'] ?></div>
                <div>昨日新增会员</div>
            </div>
            <div class="detail-info-one">
                <div class="detail-info-number"><?= $this->data['month_user_normal'] ?></div>
                <div>本月新增会员</div>
            </div>
            <div class="detail-info-one">
                <div class="detail-info-number"><?= $this->data['user_total_normal'] ?></div>
                <div>会员总数</div>
            </div>
        </div>
    </div>
</div>
<div class="undo-info">
    <div class="detail-title">待处理事务</div>
    <div class="detail-info" style="height: 8rem">
        <div class="detail-info-one">
            <div class="detail-info-title">实物订单</div>
            <a href="<?= \App::$urlManager->createUrl('erp/order/index', [
                'status' => \admin\extend\Constant::ORDER_STATUS_CREATED,
                'order_type' => \admin\extend\Constant::ORDER_TYPE_REAL
            ]) ?>"
               class="detail-info-content-one">待付款订单（<span
                        class="detail-info-number"><?= $this->data['order_real_unpaid'] ?></span>）
            </a>
            <a href="<?= \App::$urlManager->createUrl('erp/order/index', [
                    'status' => \admin\extend\Constant::ORDER_STATUS_PENDING,
                    'order_type' => \admin\extend\Constant::ORDER_TYPE_REAL
                ]
            ) ?>"
               class="detail-info-content-one">待发货订单（<span
                        class="detail-info-number"><?= $this->data['order_real_undelviver'] ?></span>）
            </a>
        </div>
        <div class="detail-info-one">
            <div class="detail-info-title">虚拟订单</div>
            <a href="<?= \App::$urlManager->createUrl('erp/order/index', [
                'status' => \admin\extend\Constant::ORDER_STATUS_CREATED,
                'order_type' => \admin\extend\Constant::ORDER_TYPE_VIRTUAL
            ]) ?>"
               class="detail-info-content-one">待确认收款订单（<span
                        class="detail-info-number"><?= $this->data['order_virtual_unpaid'] ?></span>）
            </a>
            <a href="<?= \App::$urlManager->createUrl('erp/order/index', [
                'status' => \admin\extend\Constant::ORDER_STATUS_PENDING,
                'order_type' => \admin\extend\Constant::ORDER_TYPE_VIRTUAL
            ]) ?>"
               class="detail-info-content-one">待确认使用订单（<span
                        class="detail-info-number"><?= $this->data['order_virtual_unused'] ?></span>）
            </a>
        </div>
    </div>
</div>
