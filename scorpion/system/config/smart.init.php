<?php
/**
 * 功能说明：<全局插件配置>
 * ============================================================================
 * 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 * ----------------------------------------------------------------------------
 * 作者：郭永恩，李鹏
 */
if (!defined('RYPDINC')) exit("Request Error!!!");

////////////////////////// smarty模板引擎配置start //////////////////////////

// 设置模板目录
defined('TEMPLATE_DIR') or define('TEMPLATE_DIR', WEBSITE_ROOT . 'templates/default/');
// 设置编译目录
defined('COMPILE_DIR') or define('COMPILE_DIR', WEBSITE_ROOT . 'compile/');
// 设置缓存目录
defined('CACHE_DIR') or define('CACHE_DIR', WEBSITE_ROOT . 'cache/');
// 是否打开缓存
defined('CACHING') or define('CACHING', false);
// 缓存存活时间（秒）
defined('CACHE_LIFETIME') or define('CACHE_LIFETIME', 60);
// 调试，生产环境不适合用
defined('DEBUGGING') or define('DEBUGGING', false);
// 强迫编译 (生产环境不适合用,要用 false)
defined('FORCE_COMPILE') or define('FORCE_COMPILE', false);

////////////////////////// smarty模板引擎目录配置end //////////////////////////
?>
