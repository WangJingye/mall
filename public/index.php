<?php

define('APP', 'admin');
define('APP_VERSION', 'V1.0.0');
define('BASE_PATH', dirname(__DIR__) . '/');
define('PUBLIC_PATH', dirname(__FILE__) . '/');

define('APP_PATH', BASE_PATH . APP . '/');
define('COMMON_PATH', BASE_PATH . 'common/');
ini_set('date.timezone', 'Asia/Shanghai');
ini_set('display_errors', 'On');
error_reporting(E_ALL);
if (file_exists('index-local.php')) {
    require 'index-local.php';
}
require BASE_PATH . 'vendor/autoload.php';
App::run();