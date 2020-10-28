# mall
``php服务端商城框架``

###初始化

路由解析 nginx.conf

基础数据库文件 common/config/database.sql

####数据库配置
在common/config目录下增加db.php文件

```
<?php

return [
    // 数据库服务器地址
    'hostname' => '127.0.0.1',
    // 数据库名
    'database' => 'dbname',
    // 用户名
    'username' => 'username',
    // 密码
    'password' => 'passwd',
    // 端口
    'port' => '3306',
    // 数据库编码默认采用utf8
    'charset'  => 'utf8mb4',
    // 数据库表前缀
    'prefix'   => 'tbl_',
];
```

####Redis配置
在common/config目录下增加redis.php文件
```
<?php

return [
    // redis服务器地址
    'host' => '127.0.0.1',
    // 端口
    'port' => '6379',
    //认证
//    'password'=>'****'
];
```