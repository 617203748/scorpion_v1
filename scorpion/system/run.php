<?php
/**
 * 功能说明：<运行>
 * ============================================================================
 * 版权所有：蒲公英商贸有限公司技术部
 * ----------------------------------------------------------------------------
 * 作者：蒲公英技术部
 * ----------------------------------------------------------------------------
 * 日期：2016-03-15
 */

if (!defined('RYPDINC')) exit("Request Error!!!");
// 检测PHP环境
if (version_compare(PHP_VERSION, '5.3.0', '<')) die('Please choose Plase PHP > 5.3.0  !!!!');

define('SYSTEM_PATH', __DIR__ . '/');

//系统配置清单
require_once SYSTEM_PATH . 'config/system.list.config.php';

//自动加载类
Loader::loading();
//最后开启session
Loader::startSession();


// 入口
$action = Factory::setAction(ACTION_ROOT);
$action->run();

?>