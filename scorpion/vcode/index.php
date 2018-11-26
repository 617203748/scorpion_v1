<?php
	/**
 	* 功能：生成验证码
 	* ============================================================================
 	* 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
 	* 作者：郭永恩，李鹏
	*/

	define('RYPDINC', true);
	define('SYSTEM_PATH',dirname(__DIR__).'/system/');
	require_once SYSTEM_PATH.'config/system.php';
	require_once (INCLUDE_PATH.'Loader.php');
	// 入口
	//自动加载类
	Loader::loading();
		//最后开启session
	Loader::startSession();

	$vc = new Captcha();

	$vc->fonts=VCODE_PATH.'fonts/ggbi.ttf';
	$vc->words_txt = VCODE_PATH.'words/words.txt';
	$vc->words_jpg = VCODE_PATH.'words/vdcode.jpg';
	$vc->safe_codetype = '1';
	$vc->safe_width = '65';
	$vc->safe_wheight = '25';
	$vc->create();

	Tool::saveSession('lglp_verify_code_value',$vc->getCode());
 
?>