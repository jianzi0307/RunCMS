<?php
/**
 * ----------------------
 * ConfsettingController.php
 *
 * User: jian0307@icloud.com
 * Date: 2015/5/27
 * Time: 14:01
 * ----------------------
 */
//调试模式
define('APP_DEBUG', true);

//是否从公网链接测试服务器
define('PUBLIC_CONNECT', false);

//默认模块
//define('BIND_MODULE', 'Home');

// 检测PHP环境
if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    die('require PHP > 5.5.0 !');
}

// 定义应用目录
define('APP_PATH', '../Application/');

//composer
require '../vendor/autoload.php';

// 引入ThinkPHP入口文件
require '../ThinkPHP/ThinkPHP.php';
