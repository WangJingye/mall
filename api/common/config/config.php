<?php
$runtimeConfig = [];
if (file_exists(COMMON_PATH . 'config/config.php')) {
    $runtimeConfig = include COMMON_PATH . 'config/config.php';
}
$configs = [
    'apiLog' => '/tmp/api.log'
];
return array_merge($runtimeConfig, $configs);