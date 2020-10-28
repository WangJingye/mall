<?php
$runtimeConfig = [];
if (file_exists(COMMON_PATH . 'config/config.php')) {
    $runtimeConfig = include COMMON_PATH . 'config/config.php';
}
$configs = [
    'action_white_list' => [
        'v1/public' => ['*'],
    ]
];
return array_merge($runtimeConfig, $configs);