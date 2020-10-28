<?php

namespace common\extend\redis;
class RedisConnect
{
    /** @var \Redis */
    static $redis;

    public static function instance()
    {
        if (static::$redis == null) {
            $rf = COMMON_PATH . 'config/redis.php';
            if (!file_exists($rf)) {
                throw new \Exception('Redis配置文件redis.php不存在');
            }
            $config = require $rf;
            $redis = new \Redis();
            $redis->connect($config['host'], $config['port']);
            if (isset($config['password'])) {
                $redis->auth($config['password']);
            }
            static::$redis = $redis;
        }
        return static::$redis;
    }
}