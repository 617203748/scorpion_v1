<?php
/**
 * 功能：模块配置文件
 * ============================================================================
 * 版权所有：郭永恩，李鹏，并保留所有权利。
 * ----------------------------------------------------------------------------
 * 作者：郭永恩，李鹏
 */

if (!defined('RYPDINC')) exit("Request Error!!!");

//css js 路径
define('CURRENT_STYLE_JS', WEBSITE_ROOT . 'templates/default/');
define('RELATIVE_STYLE_DEFAULT', '../common/templates/default/');

//返回信息
define('RESPONSE_CODE_ERROR', 404);
define('RESPONSE_CODE_SUCCESS', 0);

define('RESPONSE_DESC', array(
    RESPONSE_CODE_ERROR => '程序报错',
    RESPONSE_CODE_SUCCESS => '操作成功',
));