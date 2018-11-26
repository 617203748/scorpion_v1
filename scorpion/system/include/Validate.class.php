<?php
/**
 * 功能说明：<验证工具类>
 * ============================================================================
 * 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 * ----------------------------------------------------------------------------
 * 开发团队：太原锐意鹏达科技有限公司
 */
if (!defined('RYPDINC')) exit("Request Error!!!");

class Validate
{

    //从$_POST中获取值
    public static function request($key)
    {
        if (!isset($_REQUEST[$key])) {
            return null;
        }

        if (is_array($_REQUEST[$key])) {
            return $_REQUEST[$key];
        }
        return trim($_REQUEST[$key]);
    }

    //从$_POST中获取值
    public static function post($key)
    {
        if (!isset($_POST[$key])) {
            return null;
        }

        if (is_array($_POST[$key])) {
            return $_POST[$key];
        }
        return trim($_POST[$key]);
    }

    // 从$_GET中获取值
    public static function get($key)
    {
        if (!isset($_GET[$key])) {
            return null;
        }
        if (empty($_GET[$key]) && $_GET[$key] != 0 && $_GET[$key] != "" && $_GET[$key] != null) {
            return null;
        }

        return trim($_GET[$key]);
    }

    //是否为空
    public static function isNull($data)
    {
        if (trim($data) == '' || $data == null) return true;
        return false;
    }

    //数据是否为数字
    public static function isNumber($data)
    {
        if (is_numeric($data)) return true;
        return false;
    }

    //数据是否为正整数
    public static function isUnsignedInt($data)
    {
        if (intval($data) >= 0) return true;
        return false;
    }

    //是否在数字范围内
    public static function isInNumberRange($data, $range, $flag)
    {
        if ($flag == 'min') {
            if (intval($data) < $range) return true;
            return false;
        } elseif ($flag == 'max') {
            if (intval($data) > $range) return true;
            return false;
        } elseif ($flag == 'equals') {
            if (intval($data) != $range) return true;
            return false;
        }
    }

    //是否在长度范围内
    public static function isInLengthRange($data, $length, $flag)
    {
        if ($flag == 'min') {
            if (mb_strlen(trim($data), 'utf-8') < $length) return true;
            return false;
        } elseif ($flag == 'max') {
            if (mb_strlen(trim($data), 'utf-8') > $length) return true;
            return false;
        } elseif ($flag == 'equals') {
            if (mb_strlen(trim($data), 'utf-8') != $length) return true;
            return false;
        }
    }

    //数据是否相等
    public static function isEquals($source, $destination)
    {
        if (trim($source) == trim($destination)) return true;
        return false;
    }

    //验证用户名
    public static function checkUserName($_data)
    {
        if (preg_match('/^[\x80-\xff_a-zA-Z0-9]{2,20}$/', $_data)) return true;
        return false;
    }


    //验证是否只含有中文，字母
    public static function isName($data)
    {
        if (preg_match('/^[a-zA-Z\x{4e00}-\x{9fa5}]+$/u', $data)) return true;
        return false;
    }

    //验证是否为字母
    public static function isEnglish($data)
    {
        if (!preg_match('/^[a-zA-Z}]+$/u', $data)) return true;
        return false;
    }

    // 验证中文
    public static function checkChn($_data)
    {
        if (preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $_data)) return true;
        return false;
    }

    //验证是否只含有中文，字母，数字
    public static function isNotSpecialChar($data)
    {
        if (preg_match('/^[\x{4e00}-\x{9fa5}]|([A-Z]|[a-z]|\d)$/u', $data)) return true;
        return false;

    }


    //验证是否只含有中文，字母，数字   例如：200 、2300-4500  /^([0-9]+[-~][0-9]+)|\d+$/;
    public static function isSalaryRange($data)
    {

        //if (preg_match('/^[\x{4e00}-\x{9fa5}A-Za-z0-9~-]+$/u',$data)) return true;
        if (preg_match('/^([0-9]+[-~][0-9]+)|\d+$/u', $data)) return true;
        return false;

    }

    //验证时间范围   例如：12:00~19:00  12:00-19:00
    public static function isDateRange($data)
    {
        if (preg_match('/^\d{2}:\d{2}[-~]\d{2}:\d{2}$/', $data)) return true;
        return false;

    }

    //验证是否只含有数字，减号，波浪号   例如：23-40 23~30
    public static function isAgeRange($data)
    {
        if (preg_match('/^[0-9~-]+$/u', $data)) return true;
        return false;

    }


    //验证密码   数字和字母
    public static function checkPwd($_data)
    {
        if (preg_match('/[a-zA-Z0-9]/', $_data)) return true;
        return false;
    }

    //验证电子邮件
    public static function checkEmail($_data)
    {
        if (preg_match('/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/', $_data)) return true;
        return false;
    }

    //验证邮编
    public static function checkZipCode($_data)
    {
        if (preg_match('/^[0-9]{6}$/', $_data)) return true;
        return false;
    }

    //验证电话
    public static function checkPhone($_data)
    {
        if (preg_match('/^(([0\+]\d{2,3}-)?(0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/', $_data)) return true;
        return false;
    }

    //验证手机
    public static function checkMobile($_data)
    {
        if (preg_match('/^[1][2345789]\d{9}$/', $_data)) return true;
        return false;
    }

    //验证成绩(0-100,小数点后两位)
    public static function checkScore($_data)
    {
        if (preg_match('/^(100|[1-9]?[0-9](\.[0-9]{1,2})?)$/', $_data)) return true;
        return false;
    }


    //验证qq
    public static function checkQQ($_data)
    {
        if (preg_match('/^[1-9][0-9]{4,}$/', $_data)) return true;
        return false;
    }

    // 验证营业执照
    public static function checkLicence($_data)
    {
        if (preg_match('/^\d{15}$/', $_data)) return true;
        return false;
    }

    //判断是否属手机游览网站的程序
    public static function is_mobile()
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $mobile_agents = Array("240x320", "acer", "acoon", "acs-", "abacho", "ahong",
            "airness", "alcatel", "amoi", "android", "anywhereyougo.com", "applewebkit/525", "applewebkit/532",
            "asus", "audio", "au-mic", "avantogo", "becker", "benq", "bilbo", "bird",
            "blackberry", "blazer", "bleu", "cdm-", "compal", "coolpad", "danger", "dbtel",
            "dopod", "elaine", "eric", "etouch", "fly", "fly_", "fly-", "go.web", "goodaccess",
            "gradiente", "grundig", "haier", "hedy", "hitachi", "htc", "huawei", "hutchison",
            "inno", "ipad", "ipaq", "ipod", "jbrowser", "kddi", "kgt", "kwc", "lenovo", "lg ", "lg2", "lg3", "lg4", "lg5", "lg7", "lg8", "lg9", "lg-", "lge-", "lge9", "longcos",
            "maemo", "mercator", "meridian", "micromax", "midp", "mini", "mitsu", "mmm", "mmp",
            "mobi", "mot-", "moto", "nec-", "netfront", "newgen", "nexian", "nf-browser", "nintendo",
            "nitro", "nokia", "nook", "novarra", "obigo", "palm", "panasonic", "pantech", "philips",
            "phone", "pg-", "playstation", "pocket", "pt-", "qc-", "qtek", "rover", "sagem", "sama",
            "samu", "sanyo", "samsung", "sch-", "scooter", "sec-", "sendo", "sgh-", "sharp", "siemens",
            "sie-", "softbank", "sony", "spice", "sprint", "spv", "symbian", "tablet", "talkabout",
            "tcl-", "teleca", "telit", "tianyu", "tim-", "toshiba", "tsm", "up.browser", "utec",
            "utstar", "verykool", "virgin", "vk-", "voda", "voxtel", "vx", "wap", "wellco", "wig browser",
            "wii", "windows ce", "wireless", "xda", "xde", "zte");
        $is_mobile = false;
        foreach ($mobile_agents as $device) {
            if (stristr($user_agent, $device)) {
                $is_mobile = true;
                break;
            }
        }
        return $is_mobile;
    }

    //验证身份证号-从长度判断
    public static function checkIdCard($_data)
    {
        $preg = '/(^\d{15}$)|(^\d{17}([0-9]|X)$)/';
        if (preg_match($preg, $_data)) return true;
        return false;
    }

    //验证身份证号-根据国家标准GB 11643-1999
    public static function checkIdcardGB($idcard)
    {
        if (strlen($idcard) != 18 && strlen($idcard) != 15) {
            return false;
        }

        if (strlen($idcard) == 18) {
            $idcard_base = substr($idcard, 0, 17);

            //加权因子
            $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
            //校验码对应值
            $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
            $checksum = 0;
            for ($i = 0; $i < strlen($idcard_base); $i++) {
                $checksum += substr($idcard_base, $i, 1) * $factor[$i];
            }
            $mod = $checksum % 11;
            $verify_number = $verify_number_list[$mod];

            if ($verify_number != substr($idcard, 17, 1)) {
                return false;
            } else {
                return true;
            }
        }

        if (strlen($idcard) == 15) {
            // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
            if (array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) !== false) {
                $idcard = substr($idcard, 0, 6) . '18' . substr($idcard, 6, 9);
            } else {
                $idcard = substr($idcard, 0, 6) . '19' . substr($idcard, 6, 9);
            }
            $idcard = $idcard . idcard_verify_number($idcard);

            $idcard_base = substr($idcard, 0, 17);

            //加权因子
            $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
            //校验码对应值
            $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
            $checksum = 0;
            for ($i = 0; $i < strlen($idcard_base); $i++) {
                $checksum += substr($idcard_base, $i, 1) * $factor[$i];
            }
            $mod = $checksum % 11;
            $verify_number = $verify_number_list[$mod];

            if ($verify_number != substr($idcard, 17, 1)) {
                return false;
            } else {
                return true;
            }
        }
    }

    /*
    if(is_mobile()){ //跳转至wap分组
    //这里就可以执行手机游览的代码了。
    }else{
    //这里是正常访问后的代码
    }
    */

    //是否存在cookie
    public static function isExistCookie($key)
    {
        if (empty($_COOKIE[$key])) {
            return false;
        }

        return true;
    }

    //是否验证成功
    public static function isValidate()
    {

        /*
        $end = 1381338671;

        $start = strtotime(date("Y-m-d H:i:s"));

        if($end > $start){
            return true;
        }

        return false;
        */

        return true;
    }


}

?>