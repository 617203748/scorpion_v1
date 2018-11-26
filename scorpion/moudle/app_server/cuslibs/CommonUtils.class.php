<?php

/**
 * Created by PhpStorm.
 * User: zzz
 * Date: 2017/2/24
 * Time: 11:24
 */
class CommonUtils
{
    //判断是否全是中文
    public static function isChinese($str)
    {
        if (preg_match('/[\x7f-\xff]/', $str)) {
            //echo '含有中文';
            return true;
        } else {
            //echo '没有中文';
            return false;
        }
    }

    //判断字符串以什么开头
    public static function startWith($str, $pattern)
    {
        if (strpos($str, $pattern) === 0) {
            return true;
        } else {
            return false;
        }
    }
}