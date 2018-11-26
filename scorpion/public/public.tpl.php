<?php
/**
 * 功能：网站后台初始化配制文件
 * Athor：郭永恩，李鹏
 */

if (!defined('RYPDINC')) exit("Request Error!!!");


//公共模板路径
define('CURRENT_STYLE_DEFAULT', ROOT_PATH . 'pc/common/templates/default/');
// define('RELATIVE_STYLE_DEFAULT', './common/templates/default/');
//公共footer
define('PUBLIC_STYLE_FOOTER', CURRENT_STYLE_DEFAULT . 'footer.tpl');
//公共header
define('PUBLIC_STYLE_HEADER', CURRENT_STYLE_DEFAULT . 'header.tpl');
//左侧
define('PUBLIC_STYLE_LEFT', CURRENT_STYLE_DEFAULT . 'left.tpl');
//顶部
define('PUBLIC_STYLE_TOP', CURRENT_STYLE_DEFAULT . 'top.tpl');

?>