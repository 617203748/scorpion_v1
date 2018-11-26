<?php
if (!defined('RYPDINC')) exit("Request Error!!!");
/********************系统加载 start**********************/

//域名配置清单
//require_once dirname(SYSTEM_PATH).'/public/domain.list.php';//PUBLIC_PATH.'domain.list.php';
//系统核心配制
require_once SYSTEM_PATH . 'config/system.php';


//模板引擎配制
require_once SYS_CONFIG_PATH . 'smart.init.php';

//域名配置清单
require_once PUBLIC_PATH . 'domain.list.php';

//域名配置清单
require_once SYS_CONFIG_PATH . 'public.init.php';

//网站公共配制 前后台公用
require_once PUBLIC_PATH . 'conf.init.php';
//网站配制
require_once PUBLIC_PATH . 'web.config.php';


//内网配制
require_once PUBLIC_PATH . 'inner.config.php';


//插件目录
require_once SYS_CONFIG_PATH . 'plugins.init.php';


//公共目录
require_once SYS_CONFIG_PATH . 'common.inc.php';
//pc网站公共模板 头部，底部 ，菜单
require_once PUBLIC_PATH . 'public.tpl.php';

//自动加载类
require_once(INCLUDE_PATH . 'Loader.php');


//网站 自定义配制
require_once WEBSITE_CONFIG_ROOT . 'cus_conf.init.php';

/********************系统加载 end**********************/
?>