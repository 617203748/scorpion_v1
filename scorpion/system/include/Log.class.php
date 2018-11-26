<?php
/**
 * 功能说明：<日志类>
 * ============================================================================
 * 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 * ----------------------------------------------------------------------------
 * 作者：郭永恩，李鹏
 */
if (!defined('RYPDINC')) exit("Request Error!!!");

class Log
{
    // 是否开启日志
    private static $debug = IS_START_LOG;
    // 日志文件名
    private static $logFileName = "current.log";
    // 日志存放路径
    private static $logPath = LOG_PATH;

    // 写日志的
    public static function write($content)
    {
        if (!self::$debug) {
            return;
        }
        // 检测日志路径是否存在
        if (!self::checkPath()) {
            echo "日志目录创建失败！";
            return;
        }

        $content .= "\r\n";

        // 获取日志文件名
        $log = self::getLogFileName();

        // 以追加方式打开文件
        $f = fopen($log, 'ab');

        fwrite($f, $content);

        fclose($f);
    }

    /**
     *
     * @param [type] $path
     * @param [type] $file
     * @param [type] $content
     * @return void
     */
    static public function writeFile($path, $file, $content)
    {
        self::$logPath = $path;
        self::$logFileName = $file;
        // 检测日志路径是否存在
        if (!self::checkPath()) {
            echo "日志目录创建失败！";
            return;
        }
        $content .= "\r\n";
        // 获取日志文件名
        $log = self::$logPath . self::$logFileName;
        // 以追加方式打开文件
        $f = fopen($log, 'ab');
        fwrite($f, $content);
        fclose($f);
    }


    //验证目录
    private static function checkPath()
    {
        // 不是目录或没有写权限
        if (!is_dir(self::$logPath) || !is_writeable(self::$logPath)) {
            if (!@mkdir(self::$logPath, 0777, true)) {
                return false;
            }
        }

        return true;
    }

    // 备份日志
    private static function backupLog()
    {
        // 原始日志名
        $log = self::$logPath . self::$logFileName;

        // 备份日志名(以年月日时分秒+随机数命名)
        $backupFile = self::$logPath . date('Y_m_d_H_i_s_') . mt_rand(10000, 99999) . '.log';

        return rename($log, $backupFile);

    }

    // 返回日志文件名
    private static function getLogFileName()
    {
        $log = self::$logPath . self::$logFileName;

        // 如果文件不存在，则创建日志文件
        if (!file_exists($log)) {
            touch($log);

            return $log;
        }

        // 如果存在，则判断日志大小
        $size = filesize($log);
        if ($size <= 1024 * 1024) {
            return $log;
        }

        // 日志大于1M，则重命名日志文件
        if (!self::backupLog()) {
            return $log;
        } else {
            touch($log);

            return $log;
        }
    }
}

?>
