<?php
/**
 * 功能说明：<全局插件配置>
 * ============================================================================
 * 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 * ----------------------------------------------------------------------------
 * 作者：郭永恩，李鹏
 */
if (!defined('RYPDINC')) exit("Request Error!!!");

////////////////////////// 文本编辑器路径配置start //////////////////////////
//插件目录
//defined('SYS_PLUGINS_ROOT') OR define('SYS_PLUGINS_ROOT', DOMAIN_IMAGES.'system/plugins/');
defined('SYS_PLUGINS_ROOT') OR define('SYS_PLUGINS_ROOT', FCKEDIT_ROOT . 'system/plugins/');
//JS跨域
define('K_DOMAIN', PUBLIC_ROOT . 'js/domain.js');
defined('FCK_BASE_ROOT') or define('FCK_BASE_ROOT', SYS_PLUGINS_ROOT . 'fckeditor/');
defined('FCK_JS_ROOT') or define('FCK_JS_ROOT', SYS_PLUGINS_ROOT . 'fckeditor/fckeditor.js');
defined('FCK_WIDTH') or define('FCK_WIDTH', '100%');
defined('FCK_HEIGHT') or define('FCK_HEIGHT', '600px');


////////////////////////// 文本编辑器路径配置end //////////////////////////


////////////////////////// 二维码 start //////////////////////////

//require_once 'phpqrcode/phpqrcode.php';
include_once SYS_PLUGINS_PATH . 'phpqrcode/qrlib.php';
include_once SYS_PLUGINS_PATH . 'phpexcel/Classes/PHPExcel.php';


////////////////////////// 二维码 end //////////////////////////
?>