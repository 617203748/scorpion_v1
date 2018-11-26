<?php
	/**
 	* 功能说明：<全部购物车类>
 	* ============================================================================
 	* 版权所有：山西蒲公英生活商贸有限公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
 	* 开发团队：蒲公英技术部
	*/

	if(!defined('RYPDINC')) exit("Request Error!!!");

	class TotalCart
	{
		private static $instance = null;

		final protected function __construct()
		{

		}

		final protected function __clone()
		{

		}

		// 获取实例
		protected static function getInstance()
		{
			
			if(!(self::$instance instanceof self))
			{
				self::$instance = new self();
			}

			return self::$instance;
		}

		// 把购物车的单例对象放到session里
		public static function getCart()
		{
			if(!isset($_SESSION["web_cart"]) || !($_SESSION["web_cart"] instanceof self))
			{
				$_SESSION["web_cart"] = self::getInstance();
			}

			return $_SESSION["web_cart"];
		}

		/*实例化各个购物车类,打印其属性  默认实例化所有*/
		public function InstanceCart($arr=array())
		{
			//存放所有购物车数据的数组
            $allCartGoods=array();

            if(!count($arr))
            {
            	$arr=$GLOBALS['cartArr'];
            }

            
        	foreach($arr as $key=>$cartClass)
        	{
                $cart=$cartClass::getCart();
                $allCartGoods[$key]=$cart->getAllCart();
        	}

            return $allCartGoods;
		}

		/*获取其中一个的购物数据*/
        public function getOneCartData($cartName)
        {
           $cart=$cartName::getCart();
           return $cart->getAllCart();
        }

	}