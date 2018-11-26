<?php
/**
 * 功能：全局配置文件
 * ============================================================================
 * 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 * ----------------------------------------------------------------------------
 * 作者：郭永恩，李鹏
 */

if (!defined('RYPDINC')) exit("Request Error!!!");

// 数据库服务器配置 mysql
define('DB_TYPE', 'mysql');
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');

define('DB_USER', 'root');
define('DB_PASS', 'root');

// 调整链接引擎 (mysqli | pdo)
define('DB_DRIVER', 'pdo');
define('DB_NAME', 'taxi_new');


// 是否开启日志
define('IS_START_LOG', true);


//上传路劲   二维码
define('UPDIR', URI . 'upload/');

// 登录信息
define('LOGININFO', md5('LOGININFO'));


//memcached 服务器
defined('MEMCACHE_HOST') OR define('MEMCACHE_HOST', '127.0.0.1');
defined('MEMCACHE_PORT') OR define('MEMCACHE_PORT', 11211);
defined('MEMCACHE_EXPIRATION') OR define('MEMCACHE_EXPIRATION', 60 * 60 * 24);
defined('MEMCACHE_PREFIX') OR define('MEMCACHE_PREFIX', 'mem_');
defined('MEMCACHE_COMPRESSION') OR define('MEMCACHE_COMPRESSION', FALSE);


define('PUBLICKEY', DB_HOST . DB_PORT . DB_USER . DB_PASS . DB_DRIVER . DB_NAME);

?>
