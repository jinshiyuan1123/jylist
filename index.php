<?php
header("Content-type: text/html; charset=utf-8"); 
// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',true);

// 缓存~runtime.php文件配置，建议始终关闭该选项
define('RuntimePHP_Cache',      false);

// 定义应用目录
define('APP_PATH','./Application/');

// 定义运行时目录
define('RUNTIME_PATH','./Runtime/');

//定义网站物理路径
define('ROOT_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);

//定义程序版本
define('DUX_VER', '2.0.0');

//定义程序时间
define('DUX_TIME', '201510');


//强制手机版
define('MOBILE',True);

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';
