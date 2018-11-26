<?php
/**
 * 功能说明：<后台通用类>
 * ============================================================================
 * 版权所有：蒲公英商贸有限公司技术部
 * ----------------------------------------------------------------------------
 * 作者：蒲公英技术部
 */
if (!defined('RYPDINC')) exit("Request Error!!!");

class Comm
{
    //验证图片是否存在
    public static function ValiPic($pic)
    {
        //如果pic = -1 或读取不出图片
        if ($pic == -1 || $pic == '') {
            //默认kook图片
            $pic = MANAGE_ROOT . DEFAULT_IMAGE;
        } else {
            $pic = UPLOAD_ROOT_MANAGE . $pic;
        }
        return $pic;
    }
}

?>