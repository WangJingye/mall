<?php
$runtimeConfig = [];
if (file_exists(APP_PATH . 'common/config/config.php')) {
    $runtimeConfig = include APP_PATH . 'common/config/config.php';
}
$configs = [
    'action_white_list' => [
        'v1/public' => ['*'],
    ]
];
return array_merge($runtimeConfig, $configs);