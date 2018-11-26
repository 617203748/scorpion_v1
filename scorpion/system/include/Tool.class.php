<?php
/**
 * 功能说明：<工厂方法类>
 * ============================================================================
 * 版权所有：山西蒲公英生活商贸有限公司，并保留所有权利。
 * ----------------------------------------------------------------------------
 * 开发团队：蒲公英技术部
 * ----------------------------------------------------------------------------
 * 日期：2015.05
 */
if (!defined('RYPDINC')) exit("Request Error!!!");

class Tool
{

    public static $tpl = null;


    // 判断id是否为正整数
    public static function isUnsignedInt($id)
    {
        if (preg_match('/^[1-9]\d*$/', $id)) {
            return $id;
        }
    }

    //解析html标签
    public static function unHtml($str)
    {
        return htmlspecialchars_decode($str);
    }

    //生成html标签
    public static function htmlString($data)
    {
        $string = null;

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $string[$key] = Tool::htmlString($value);  //递归
            }
        } elseif (is_object($data)) {
            foreach ($data as $key => $value) {
                $string->$key = Tool::htmlString($value);  //递归
            }
        } else {
            $string = htmlspecialchars($data);
        }
        return $string;
    }

    //数据库输入防注入过滤
    public static function mysqlString($data)
    {
        //如果是LinuxGPC没开启，mysql_real_escape_string替换成addslashes()
        return !GPC ? addslashes($data) : $data;
    }


    //设置cookie
    public static function saveCookie($key, $value, $day = 365)
    {
        setcookie($key, $value, time() + 3600 * 24 * $day);//,'/','localhost');
    }


    //获取cookie
    public static function getCookie($key)
    {
        if (isset($_COOKIE[$key])) {
            if (!empty($_COOKIE[$key])) {
                return $_COOKIE[$key];
            }
        }

        return null;
    }

    //设置session
    public static function saveSession($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    //获取session
    public static function getSession($key)
    {

        if (isset($_SESSION[$key])) {
            if (!empty($_SESSION[$key])) {
                return $_SESSION[$key];
            }
        }
        return null;
    }

    //清空cookie
    public static function clearCookie($key, $day = 365)
    {
        if (!empty($_COOKIE[$key])) {
            setcookie($key, null, time() - 3600 * 24 * $day);
        }
    }

    //清空所有的session
    public static function clearAllSession()
    {
        session_destroy();
    }

    //清空指定的session
    public static function clearOneSession($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * 生成编码,默认不足左边补零
     * $id : 需要拼接的id
     * $len : 拼接后长度
     * 例 $id=34,$len=4,生成的id=0034
     */
    public static function SetCode($id, $len)
    {
        //$len='2';
        $id = sprintf('%0' . $len . 's', $id);
        return $id;
    }

    /**
     * 产生随机字符串
     *
     * @param    int $length 输出长度
     * @param    string $chars 可选的 ，默认为 0123456789
     * @return   string     字符串
     */
    function random($length, $chars = '0123456789')
    {
        $hash = '';
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
        return $hash;
    }


    public static function checkPath($path)
    {
        if (!is_dir(ROOT_PATH . $path) || !is_writeable(ROOT_PATH . $path)) {
            if (!@mkdir(ROOT_PATH . $path, 0777, true)) {
                Tool::alertBack('警告：主目录创建失败！');
            }
        }

    }


    //拼接编码  $id为要拼接的字符  $char是用什么字符拼装  $length是需要的长度
    public static function getCode($id, $char, $length)
    {
        $len = $length - strlen($id);
        for ($i = 0; $i < $len; $i++) {
            $id = $char . $id;
        }
        return $id;
    }

    public static function objectToArray($e)
    {
        $e = (array)$e;
        foreach ($e as $k => $v) {
            if (gettype($v) == 'resource') return;
            if (gettype($v) == 'object' || gettype($v) == 'array')
                // $e[$k]=(array)objectToArray($v);
                $e[$k] = (array)self::objectToArray($v);
        }
        return $e;
    }

    public static function arrayToObject($e)
    {
        if (gettype($e) != 'array') return;
        foreach ($e as $k => $v) {
            if (gettype($v) == 'array' || getType($v) == 'object')
                // $e[$k]=(object)arrayToObject($v);
                $e[$k] = (object)self::arrayToObject($v);
        }
        return (object)$e;
    }

    //测试对象是否已经被实例化
    public static function check($value, $from = '')
    {
        //$from  在哪个方法中使用
        echo "<pre>";
        var_dump($value);
        echo '</pre>';
    }

    //加密
    public static function encode($v)
    {
        if (isset($_SESSION['TOKEN'])) {
            $str = $_SESSION['TOKEN'] . '_' . $v;
            return base64_encode($str);
        }
        return;
    }


    //解密
    public static function decode($v)
    {
        $str = base64_decode($v);
        $arr = explode('_', $str);
        if (count($arr) > 1) {
            return $arr[1];
        }
    }


    //生成预处理数组
    public static function getPreArr($sql, $value = array())
    {
        return array('sql' => $sql, 'value' => $value);
    }


    //生成Token
    public static function setToken($update = false)
    {

        if (!isset($_SESSION['TOKEN'])) {
            $token = microtime();
            $_SESSION['TOKEN'] = $token;
        }
        if (isset($_SESSION['TOKEN'])) {
            if (empty($_SESSION['TOKEN'])) {
                $token = time();
                $_SESSION['TOKEN'] = $token;
            }

            if ($update) {
                $token = time();
                $_SESSION['TOKEN'] = $token;
            }
        }

    }


    // 字符串加密
    static function getEncryptString($str)
    {
        return sha1($str);
    }


    // 	生成长度为$len的字母和数字的随机数
    static public function GetfourStr($len)
    {
        $chars_array = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",);
        $charsLen = count($chars_array) - 1;
        $outputstr = "";
        for ($i = 0; $i < $len; $i++) {
            $outputstr .= $chars_array[mt_rand(0, $charsLen)];
        }
        return $outputstr;
    }

    //	UUID生成唯一序列
    static public function uuid($prefix = '')
    {
        $chars = md5(uniqid(mt_rand(strtotime(microtime()), 99999999999999999), true));
        $uuid = substr($chars, 0, 8) . '-';
        $uuid .= substr($chars, 8, 4) . '-';
        $uuid .= substr($chars, 12, 4) . '-';
        $uuid .= substr($chars, 16, 4) . '-';
        $uuid .= substr($chars, 20, 12);
        return $prefix . $uuid;
    }


    // 获取唯一不重复的id为组合的最终自定义id(f130eb1a41c99e68052236a9c002cd5d)
    /*
        用法：
        $table = 'cook_user';
        $this->getCustomId($table);

        $table = $table.$compid.$shopid;
    */
    static public function getCustomId($table)
    {

        // 获取指定表最新的自增长id号
        //$id = $this->nextid($table);

        // 生成年月日字符串(20160101)
        //$dateString = date("Ymd");

        //$id=md5($table.microtime().mt_rand(100000,999999));

        $id = md5($table . microtime() . mt_rand(strtotime(microtime()), 99999999999999999) . self::GetfourStr(32) . self::uuid($table));
        // 返回最终的用户id(201601019)
        return $id;
    }

    static public function getCustomIdNumber($workId = 0)
    {
        $idgen = new IdGenerate($workId);
        return $idgen->nextId($workId);
    }

    // MD5
    static public function getMd5($value)
    {
        return md5($value);
    }

    // 截取字符串
    static public function getSubstr($str, $start, $length)
    {
        return substr($str, $start, $length);
    }

    // 导出json数据 写入文件
    public static function exportJsonData($data, $json_file)
    {
        // 把PHP数组转成JSON字符串
        $json_string = json_encode($data);

        // 写入文件
        //file_put_contents('test.json', $json_string);
        file_put_contents($json_file, $json_string);

        return $json_string;
    }
}

?>