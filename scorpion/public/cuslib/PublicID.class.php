<?php
	/**
 	* 功能说明：<公共登录信息控制器基类>
 	* ============================================================================
 	* 版权所有：kook公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
 	* 作者：kook技术部
	*/

	class PublicID
	{	

		
		// memcache缓存通信 设置公共的公司Key
		public static function setCompId($v)
		{
			Tool::saveSession('meal_compid',$v);
		}

		// memcache缓存通信 设置公共的餐厅Key
		public static function setShopId($v)
		{
			Tool::saveSession('meal_shopid',$v);
		}

		// memcache缓存通信 设置公共的餐桌Key
		public static function setTableId($v)
		{
			Tool::saveSession('meal_tableid',$v);
		}

		// memcache缓存通信 设置公共的服务员Key
		public static function setServiceId($v)
		{
			Tool::saveSession('meal_serviceid',$v);
		}

		// memcache缓存通信 设置公共的区域Key
		public static function setAreaId($v)
		{
			Tool::saveSession('meal_areaid',$v);
		}

        
        // 获取所有的公共key 组成publicID
		public static function getPublicIDSession()
		{

			$publicID['compid']=Tool::getSession('meal_compid'); 
			$publicID['shopid']=Tool::getSession('meal_shopid');
			$publicID['tableid']=Tool::getSession('meal_tableid');
			$publicID['serviceid']=Tool::getSession('meal_serviceid');
			$publicID['areaid']=Tool::getSession('meal_areaid');

			return $publicID;
		}

		// GET方式接值 获取公共的publicID
		public static function getPublicIDGet()
		{
			
			$publicID['compid']=isset($_GET['compid'])?$_GET['compid'] : -1; 
			$publicID['shopid']=isset($_GET['shopid'])? $_GET['shopid'] : -1;
			$publicID['tableid']=isset($_GET['tableid'])?$_GET['tableid'] : -1;
			$publicID['serviceid']=isset($_GET['empid'])?$_GET['empid'] : -1;
			$publicID['areaid']=isset($_GET['areaid'])?$_GET['areaid'] : -1;

			return $publicID;
		}


        // POST方式接值 获取公共的publicID
		public static function getPublicIDPost()
		{
			$publicID['compid']=isset($_POST['compid'])?$_POST['compid'] : -1; 
			$publicID['shopid']=isset($_POST['shopid'])?$_POST['shopid'] : -1;
			$publicID['tableid']=isset($_POST['tableid'])?$_POST['tableid'] : -1;
			$publicID['serviceid']=isset($_POST['empid'])?$_POST['empid'] : -1;
			$publicID['areaid']=isset($_POST['areaid'])?$_POST['areaid'] : -1;
		
			return $publicID;
		}


        // 选择获取publicID的方式
		public static function getPublicID($where)
		{
			$publicID=null;
			switch (strtoupper($where))
			{
				case 'SESSION':
						 $publicID= self::getPublicIDSession();
						 break;
				case 'GET':
						 $publicID= self::getPublicIDGet();
						 break;
				case 'POST':
						 $publicID= self::getPublicIDPost();
						 break;
				
			}

			return $publicID;

		}

	}

?>