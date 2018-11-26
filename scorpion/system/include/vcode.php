<?php
define('RYPDINC', true);

//定义验证码根目录
if (is_dir('vcode')) {
    include '../config/public.php';
    define('APPLICATION_PATH', WEBSITE . '/company/');
    include SYS_PATH . 'config/init.inc.php';
    include realpath('vcode') . '/captcha.class.php';
}


$vc = new Captcha();
$vc->safe_codetype = '1';
$vc->safe_width = '65';
$vc->safe_wheight = '25';
$vc->create();

$_SESSION['cn_pugoing_code_value'] = $vc->getCode();

?>