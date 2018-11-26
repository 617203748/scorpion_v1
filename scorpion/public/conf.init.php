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


//邮件服务器配置
//SMTP
define('SMTPSERVER', 'smtp.pugoing.cn');
//SMTP端口号
define('SMTPSERVERPORT', '25');
//邮件发送的内容类型
define('SMTPMAILTYPE', 'HTML');
//SMTP用户名
define('SMTPUSERMAIL', 'ordersend@pugoing.cn');
//SMTP密码
define('SMTPPASS', 'lp123456');


//上传路劲   二维码
define('UPDIR', URI . 'upload/');

// 登录信息
define('LOGININFO', md5('LOGININFO'));

// cook_user登录信息
define('COOKS_LOGIN_INFO', md5('COOKS_LOGIN_INFO'));
//	shop_user登录信息
define('SHOPS_LOGIN_INFO', md5('SHOPS_LOGIN_INFO'));
//	cook_user、shop_user公共登录信息
define('PUBLIC_LOGIN_INFO', md5('PUBLIC_LOGIN_INFO'));


// 登录时 选择厨师时的参数
define('COOKTAG', 1);
// 登录时 选择餐厅时的参数
define('SHOPTAG', 2);


//memcached 服务器
defined('MEMCACHE_HOST') OR define('MEMCACHE_HOST', '127.0.0.1');
// defined('MEMCACHE_HOST') OR define('MEMCACHE_HOST', '192.168.1.50');
//memcached 端口号
defined('MEMCACHE_PORT') OR define('MEMCACHE_PORT', 11211);
//值时间过期 0 为不过期
defined('MEMCACHE_EXPIRATION') OR define('MEMCACHE_EXPIRATION', 60 * 60 * 24);
//前缀
defined('MEMCACHE_PREFIX') OR define('MEMCACHE_PREFIX', 'mem_');
//是否压缩数据
defined('MEMCACHE_COMPRESSION') OR define('MEMCACHE_COMPRESSION', FALSE);


define('PUBLICKEY', DB_HOST . DB_PORT . DB_USER . DB_PASS . DB_DRIVER . DB_NAME);


//微信临时目录
define('WECHAT_TEMP', ROOT_PATH . 'wechattemp/');

?>
