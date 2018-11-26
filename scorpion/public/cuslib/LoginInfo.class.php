<?php
	/**
 	* 功能说明：<公共登录信息控制器基类>
 	* ============================================================================
 	* 版权所有：kook公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
 	* 作者：kook技术部
	*/

	class LoginInfo
	{
		// 获取所有的登录信息
		public static function getLoginInfo()
		{

			$loginInfo=Tool::getSession(LOGININFO);
			return $loginInfo;
		}


		//	获取登录信息中厨师所有信息
		public static function getLoginCookInfoAll()
		{
			
			$loginInfo=self::getLoginInfo();
			return $loginInfo[COOKS_LOGIN_INFO];
		}


        //	获取登录信息中厨师某个键值信息
		public static function getLoginCookInfo($key)
		{
			
			$loginInfo=self::getLoginInfo();
			return $loginInfo[COOKS_LOGIN_INFO][$key];
		}


		//  获取登录信息中餐厅中某个键值信息
		public static function getLoginShopInfo($key)
		{
			$loginInfo=self::getLoginInfo();
			return $loginInfo[SHOPS_LOGIN_INFO][$key];
		}


		//	获取登录信息中餐厅所有信息
		public static function getLoginShopAll()
		{
			$loginInfo=self::getLoginInfo();
			return $loginInfo[SHOPS_LOGIN_INFO];
		}


		//	获取登录信息中公共所有信息
		public static function getLoginPublicInfoAll()
		{
			$loginInfo=self::getLoginInfo();
			return $loginInfo[PUBLIC_LOGIN_INFO];
		}


		//	获取登录信息中公共某个键值信息
		public static function getLoginPublicInfo($key)
		{
			$loginInfo=self::getLoginInfo();
			return $loginInfo[PUBLIC_LOGIN_INFO][$key];
		}	


	}

?>