<?php

	/**
 	* 功能：域名配置清单
 	* ============================================================================
 	* 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
 	* 作者：郭永恩，李鹏
	*/
	if(!defined('RYPDINC')) exit("Request Error!!!");
	
	//后台管理二级域名
	define('DOMAIN_IMAGES','http://images.bdjw.com/');

	
	define('DOMAIN_WEBAPP','http://webapp.bdjw.com/');

	
	define('HTTP_ROOT','http://www.bdjw.com/');

	// 网址
	//defined('HTTP_ROOT') OR define('HTTP_ROOT',URI); 
	
	//
	defined('PUBLIC_ROOT') OR define('PUBLIC_ROOT',DOMAIN_IMAGES.'public/'); 
	// 网站上传目录
	defined('UPLOAD_ROOT') OR define('UPLOAD_ROOT',DOMAIN_IMAGES.'upload/');

	// 网站上传目录
	defined('VCODE_ROOT') OR define('VCODE_ROOT',DOMAIN_IMAGES.'vcode/');

	// 网站上传目录
	defined('FCKEDIT_ROOT') OR define('FCKEDIT_ROOT',DOMAIN_IMAGES);


	



?>