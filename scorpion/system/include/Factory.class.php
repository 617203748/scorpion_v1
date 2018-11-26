<?php
/**
 * 功能说明：<工厂方法类>
 * ============================================================================
 * 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 * ----------------------------------------------------------------------------
 * 开发团队：郭永恩，李鹏
 */
if (!defined('RYPDINC')) exit("Request Error!!!");

class Factory
{
    //网站前台入口
    static private $action = null;
    //总部后台入口


    //根据连接地址获取对应的 控制器类
    static public function setAction($action_path)
    {

        $act = (isset($_GET['act']) && !empty($_GET['act'])) ? $_GET['act'] : 'Index';

        if (!file_exists($action_path . ucfirst($act) . 'Action.class.php')) {

            $act = 'Index';
        }

        $class = ucfirst($act) . 'Action';
        self::$action = new $class();
        return self::$action;
    }

}

?>