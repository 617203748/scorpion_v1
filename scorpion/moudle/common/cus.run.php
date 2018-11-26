<?php
/**
 * 功能：模块配置文件
 * ============================================================================
 * 版权所有：郭永恩，李鹏，并保留所有权利。
 * ----------------------------------------------------------------------------
 * 作者：郭永恩，李鹏
 */

if (!defined('RYPDINC')) exit("Request Error!!!");
//获取当前的其它项目目录
function getAllDir()
{
    $arr = scandir(dirname(__DIR__));

    $arrDir = array();
    foreach ($arr as $a) {

        if ($a == '.' || $a == '..') continue;
        $currentDir = getDirRoot($a);
        if (is_dir($currentDir . $a)) {

            $arrDir[] = strtoupper($a);
            $temp = strtoupper($a);
            defined($temp) OR define($temp, $currentDir . $a);
        }
    }
}


//此做法是用于网站实际 目录 和 运行目录隔离 初步达成 隔离 但 css js 无法调用 注意修改 是路径引用问题（css js 等 必须在服务器目录下）
function getDirRoot($dir)
{
    //如果是在当前目录直接返回
    $uri = './';
    $allPath = dirname($_SERVER['SCRIPT_FILENAME']) . '/';
    if (is_dir($allPath . $dir)) {
        return $uri;
    }
    //处理不在当前目录的情况
    $uri = '';
    $dirPath = explode('/', $allPath);
    $dirCount = count($dirPath);
    while ($dirCount) {
        if (is_dir($uri . $dir)) {
            break;
        }
        $uri = '../' . $uri;
        $dirCount--;
    }
    return $uri;
}


function getAllWebDir($value)
{
    /*
     $arr = scandir($value);
     $arrDir = array();
    foreach($arr as $a)
    {
        if($a=='.'||$a=='..') continue;
        $currentDir=getDirRoot($a);
        if(is_dir($currentDir.$a))
        {
            $arrDir[]=strtoupper($a);
            $temp = strtoupper($a.'_ROOT');
            defined($temp) OR define($temp,$currentDir);

        }

    }
    */
    $webCommDir = $_SERVER['DOCUMENT_ROOT'] . '/common/';
    if (is_dir($webCommDir)) {
        $file = $webCommDir . 'config.init.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }

}
//SYSTEM 系统目录
const SYSTEMDIR = 'system';
//定义系统目录
$uriSystem = getDirRoot(SYSTEMDIR);
defined('URI') OR define('URI', $uriSystem);
//找到目录
getAllDir();
getAllWebDir($uriSystem);

// 引入框架
require $uriSystem . SYSTEMDIR . '/run.php';
?>