<?php
if (file_exists(COMMON_PATH . 'config/config.php')) {
    $runtimeConfig = include COMMON_PATH . "config/config.php";
} else {
    $runtimeConfig = [];
}
$configs = [
    'actionNoLoginList' => [
        'system/public' => ['login', 'logout', 'captcha'],
    ],
    'actionWhiteList' => [
        'system/admin' => ['profile', 'change-password', 'change-profile'],
        'system/upload' => ['*'],
        'erp/site-info' => ['index'],
        'erp/user' => ['search','get-order-list'],
        'erp/coupon-user' => ['search'],
        'erp/product' => ['search','variation-search'],
        'erp/category' => ['get-child-list'],
    ]
];
return array_merge($runtimeConfig, $configs);