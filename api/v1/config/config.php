<?php
$runtimeConfig = [];
if (file_exists(APP_PATH . 'common/config/config.php')) {
    $runtimeConfig = include APP_PATH . 'common/config/config.php';
}
$configs = [
    'action_white_list' => [
        'v1/public' => ['*'],
        'v1/order' => ['notify'],
        'v1/search' => ['*'],
        'v1/product' => ['detail', 'comment', 'flash-sale', 'groupon'],
        'v1/test' => ['*']
    ]
];
return array_merge($runtimeConfig, $configs);