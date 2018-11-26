<?php
/**
 * 功能说明：<系统配置>
 * ============================================================================
 * 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 * ----------------------------------------------------------------------------
 * 作者：郭永恩，李鹏
 */
if (!defined('RYPDINC')) exit("Request Error!!!");

header('Content-Type:text/html;charset=utf-8');
header("Cache-control:private");


global $CONFIG;
// 设置时区
date_default_timezone_set('PRC');

// 初始化设置内存
@ini_set('memory_limit','2048M');
// 虚拟服务器时候用
//ini_set('session.cookie_path', '/');
//ini_set('session.cookie_domain', '.sxbdjw.com'); //注意domain.com换成你自己的域名
 

// session过期时间
ini_set('session.gc_maxlifetime', "3600"); // 秒
ini_set("session.cookie_lifetime", "0"); // 秒
ini_set("display_errors", "On");
// 错误日志
// error_reporting(0);
error_reporting(E_ALL);

// 类文件的后缀名
const EXT = '.class.php';

// 路劲分割符
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

// 时间
defined('GPC') or define('GPC', get_magic_quotes_gpc());

//框架目录
defined('SYSTEM_PATH') or define('SYSTEM_PATH', __DIR__ . '/');

// 网站根目录绝对路径
defined('ROOT_PATH') or define('ROOT_PATH', dirname(SYSTEM_PATH) . '/');

////////////////////////// 系统配置start //////////////////////////
// 系统核心类库目录
defined('INCLUDE_PATH') or define('INCLUDE_PATH', realpath(SYSTEM_PATH . 'include') . '/');

// 系统核心数据库
defined('DB') or define('DB', SYSTEM_PATH . 'db/');

// 网站基类目录
defined('BASECLASS') or define('BASECLASS', SYSTEM_PATH . 'baseclass/');

// 系统核心配置
defined('SYS_CONFIG_PATH') or define('SYS_CONFIG_PATH', SYSTEM_PATH . 'config/');

// 系统插件目录
defined('SYS_PLUGINS_PATH') or define('SYS_PLUGINS_PATH', SYSTEM_PATH . 'plugins/');

// 系统模板引擎目录目录
defined('SMARTY_PATH') or define('SMARTY_PATH', INCLUDE_PATH . 'smarty/');
// 系统模板引擎目录目录
defined('SMARTY_PLUGINS_PATH') or define('SMARTY_PLUGINS_PATH', SMARTY_PATH . 'sysplugins/');

// 系统验证码目录
defined('VCODE_PATH') or define('VCODE_PATH', INCLUDE_PATH . 'vcode/');

// 网站图片等上传的公共地方
defined('UPLOAD_PATH') or define('UPLOAD_PATH', ROOT_PATH . 'upload/');

// 网站公共配置地方
defined('PUBLIC_PATH') or define('PUBLIC_PATH', ROOT_PATH . 'public/');

// 网站日志目录
defined('LOG_PATH') or define('LOG_PATH', ROOT_PATH . 'logs/');

// 网站公共配置地方
defined('PUBLIC_CUSLIB_PATH') or define('PUBLIC_CUSLIB_PATH', PUBLIC_PATH . 'cuslib/');

// 网站session公共地方
defined('SESSION_PATH') or define('SESSION_PATH', ROOT_PATH . 'session/');
if (is_writeable(SESSION_PATH) && is_readable(SESSION_PATH)) {
    session_save_path(SESSION_PATH);
}
////////////////////////// 系统配置end //////////////////////////

////////////////////////// 网站配置start //////////////////////////


// 网页运行目录
defined('WEBSITE_ROOT') or define('WEBSITE_ROOT', dirname($_SERVER['SCRIPT_FILENAME']) . '/');
// 网站自定义配置
defined('WEBSITE_CONFIG_ROOT') or define('WEBSITE_CONFIG_ROOT', WEBSITE_ROOT . 'common/');
// 网站action目录
defined('ACTION_ROOT') or define('ACTION_ROOT', WEBSITE_ROOT . 'action/');
// 网站model目录
defined('MODEL_ROOT') or define('MODEL_ROOT', WEBSITE_ROOT . 'model/');
defined('MODEL_DAO_ROOT') or define('MODEL_DAO_ROOT', WEBSITE_ROOT . 'model/dao');
defined('MODEL_DTO_ROOT') or define('MODEL_DTO_ROOT', WEBSITE_ROOT . 'model/dto');
//网站自定义类库目录
defined('CUS_LIBS_ROOT') or define('CUS_LIBS_ROOT', WEBSITE_ROOT . 'cuslibs/');
// 网站check目录
defined('CHECK_ROOT') or define('CHECK_ROOT', WEBSITE_ROOT . 'check/');
////////////////////////// 网站配置end //////////////////////////
	