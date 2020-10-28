<?php
$runtimeConfig = [];
if (file_exists(COMMON_PATH . 'config/config.php')) {
    $runtimeConfig = include COMMON_PATH . 'config/config.php';
}
$configs = [];
return array_merge($runtimeConfig, $configs);